import requests
from bs4 import BeautifulSoup
import json
import sqlite3

# Função para conectar ao banco de dados
def connect_db(db_name='../../database/database.sqlite'):
    return sqlite3.connect(db_name)

# Função para extrair dados do produto
def extract_product_data(item):
    name = item.get('name')
    price_with_discount = item.get('priceWithDiscount')
    image_url = item.get('image')
    currency = "BRL"  # Assumindo que a moeda é BRL

    if image_url:
        parts = image_url.split('/')
        if len(parts) >= 8:
            product_id = parts[6]  # ID do produto

        else:
            product_id = parts[-2] if len(parts) > 2 else None


        if product_id:
            product_url = f"https://www.kabum.com.br/produto/{product_id}"
            return name, price_with_discount, currency, product_url

    return None

# Função para normalizar a URL
def normalize_url(url):
    return url.split('?')[0].rstrip('/')

# Função para verificar se o produto já existe no banco de dados
def product_exists(cursor, name, price_with_discount, product_url):
    normalized_url = normalize_url(product_url)
    cursor.execute('''
        SELECT id FROM loja_online
        WHERE urlLoja = ? AND valor = ? AND EXISTS (
            SELECT 1 FROM produtos WHERE nome = ? AND loja_online_id = loja_online.id
        )
    ''', (normalized_url, price_with_discount, name))
    return cursor.fetchone() is not None

# Função para salvar dados no banco de dados
def save_product(cursor, name, price_with_discount, currency, product_url):
    if not product_exists(cursor, name, price_with_discount, product_url):
        # Inserir o produto na tabela loja_online
        cursor.execute('''
            INSERT INTO loja_online (urlLoja, valor, moeda, created_at, updated_at)
            VALUES (?, ?, ?, datetime('now'), datetime('now'))
        ''', (product_url, price_with_discount, currency))

        loja_online_id = cursor.lastrowid

        # Inserir o produto na tabela produtos com disponibilidade = 1
        cursor.execute('''
            INSERT INTO produtos (nome, loja_online_id, disponibilidade, created_at, updated_at)
            VALUES (?, ?, 1, datetime('now'), datetime('now'))
        ''', (name, loja_online_id))

        produto_id = cursor.lastrowid

        # Inserir o produto na tabela estoque (somente produtos disponíveis)
        cursor.execute('''
            INSERT INTO estoque (produto_id, created_at, updated_at)
            VALUES (?, datetime('now'), datetime('now'))
        ''', (produto_id,))

    else:
        print(f"Produto já existe no banco de dados: {name} - {product_url}")

# Função principal para processar todas as páginas de uma categoria com URL específica
def scrape_all_pages(base_url, headers, facet_filters, sort='most_searched', page_size=20):
    page_number = 1
    total_products_collected = 0

    conn = connect_db()
    cursor = conn.cursor()

    while True:
        url = f'{base_url}?facet_filters={facet_filters}&sort={sort}&page_number={page_number}&page_size={page_size}'
        response = requests.get(url, headers=headers)

        if response.status_code != 200:
            print(f"Erro ao acessar o site: {response.status_code}")
            break

        soup = BeautifulSoup(response.content, 'html.parser')
        script_tag = soup.find('script', id='__NEXT_DATA__')

        if script_tag:
            data = json.loads(script_tag.string)
            catalog_data = json.loads(data['props']['pageProps']['data'])
            catalog_data_inner = catalog_data['catalogServer']['data']

            if not catalog_data_inner:
                print("Nenhum produto encontrado na página, encerrando.")
                break

            for item in catalog_data_inner:
                product_data = extract_product_data(item)
                if product_data:
                    save_product(cursor, *product_data)
                    total_products_collected += 1

        page_number += 1

    conn.commit()
    conn.close()

    print(f"Total de produtos coletados da categoria {base_url}: {total_products_collected}")

# Função para processar várias categorias com URLs específicas
def scrape_multiple_categories_with_filters(categories, headers):
    for category, filters in categories:
        scrape_all_pages(category, headers, facet_filters=filters)

# Configurações e execução
categories_with_filters = [
    ('https://www.kabum.com.br/hardware/placa-de-video-vga', 'eyJrYWJ1bV9wcm9kdWN0IjpbInRydWUiXX0='),
    ('https://www.kabum.com.br/hardware/processadores', 'eyJrYWJ1bV9wcm9kdWN0IjpbInRydWUiXX0='),  # 1169
     ('https://www.kabum.com.br/hardware/ssd-2-5/ssd-sata', 'eyJrYWJ1bV9wcm9kdWN0IjpbInRydWUiXX0='),
        ('https://www.kabum.com.br/hardware/coolers/water-cooler', 'eyJrYWJ1bV9wcm9kdWN0IjpbInRydWUiXX0='),
         ('https://www.kabum.com.br/hardware/coolers/air-cooler', 'eyJrYWJ1bV9wcm9kdWN0IjpbInRydWUiXX0='),
    ('https://www.kabum.com.br/hardware/fontes', 'eyJrYWJ1bV9wcm9kdWN0IjpbInRydWUiXX0='),
    ('https://www.kabum.com.br/hardware/memoria-ram', 'eyJDb21wYXRpYmlsaWRhZGUiOlsiRGVza3RvcCJdfQ=='),
    ('https://www.kabum.com.br/hardware/placas-mae', 'eyJrYWJ1bV9wcm9kdWN0IjpbInRydWUiXX0='),
]

headers = {
    'User-Agent': 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:129.0) Gecko/20100101 Firefox/129.0'
}

scrape_multiple_categories_with_filters(categories_with_filters, headers)
