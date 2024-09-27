import requests
from bs4 import BeautifulSoup
import json
import sqlite3
import time

# Função para conectar ao banco de dados
def connect_db(db_name='../database/database.sqlite'):
    return sqlite3.connect(db_name)

# Scraper para o site Pato
class PatoScraper:
    def collect_products(self, url):
        response = requests.get(url)
        soup = BeautifulSoup(response.content, 'html.parser')
        produtos = soup.select('article.product')
        resultados = []
        for produto in produtos:
            try:
                # Nome do produto
                nome = produto.select_one('h3.tit').get_text(strip=True)

                # Link do produto
                link = produto.select_one('a')['href']

                # Preço à vista
                preco_element = produto.select_one('span.h1.text-success')
                if preco_element:
                    preco_texto = preco_element.get_text(strip=True)
                    preco = float(preco_texto.replace('R$', '').replace('.', '').replace(',', '.').strip())
                    moeda = 'BRL'
                else:
                    preco = None

                if preco:  # Apenas adiciona o produto se houver preço
                    resultados.append({
                        'nome': nome,
                        'preco': preco,
                        'moeda': moeda,
                        'link': link,
                        'disponibilidade': 1
                    })
            except Exception as e:
                print("Erro ao processar um produto:", e)
        return resultados

# Scraper para o site Kabum
class KabumScraper:
    def collect_products(self, url):
        response = requests.get(url)
        soup = BeautifulSoup(response.content, 'html.parser')
        script_tag = soup.find('script', id='__NEXT_DATA__')

        if not script_tag:
            return []

        data = json.loads(script_tag.string)
        catalog_data = json.loads(data['props']['pageProps']['data'])
        catalog_data_inner = catalog_data['catalogServer']['data']

        resultados = []
        for item in catalog_data_inner:
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
                    if price_with_discount:  # Apenas adiciona o produto se houver preço
                        resultados.append({
                            'nome': name,
                            'preco': price_with_discount,
                            'moeda': currency,
                            'link': product_url,
                            'disponibilidade': 1
                        })

        return resultados

# Função para salvar os produtos, preços e links no banco
def salvar_produtos_no_banco(produtos):
    conn = connect_db()
    cursor = conn.cursor()

    for produto in produtos:
        try:
            # Verificar se o link já existe na tabela loja_online
            cursor.execute('''
                SELECT id FROM loja_online WHERE urlLoja = ?
            ''', (produto['link'],))
            loja_online_result = cursor.fetchone()

            if loja_online_result:
                print(f"Produto com o link {produto['link']} já existe na tabela loja_online. Não será salvo novamente.")
                continue  # Pula para o próximo produto

            # Inserir uma nova entrada na tabela loja_online
            cursor.execute('''
                INSERT INTO loja_online (urlLoja, valor, moeda, created_at, updated_at)
                VALUES (?, ?, ?, datetime('now'), datetime('now'))
            ''', (produto['link'], produto['preco'], produto['moeda']))
            loja_online_id = cursor.lastrowid  # Recuperar o ID da loja online inserida

            # Inserir os dados na tabela produtos
            cursor.execute('''
                INSERT INTO produtos (nome, loja_online_id, disponibilidade, created_at, updated_at)
                VALUES (?, ?, ?, datetime('now'), datetime('now'))
            ''', (produto['nome'], loja_online_id, produto['disponibilidade']))
            produto_id = cursor.lastrowid  # ID do produto recém-criado

            # Verificar disponibilidade e salvar na tabela estoque
            if produto['disponibilidade'] == 1:
                cursor.execute('''
                    INSERT INTO estoque (produto_id, created_at, updated_at)
                    VALUES (?, datetime('now'), datetime('now'))
                ''', (produto_id,))

            # Salvar as alterações
            conn.commit()
        except sqlite3.Error as e:
            print(f"Erro ao inserir o produto no banco de dados: {e}")
            conn.rollback()

    conn.close()

# Função para processar páginas de produtos, detectando qual scraper usar (Pato ou Kabum)
def processar_paginas(url_base, max_paginas=1):
    if "kabum.com.br" in url_base:
        scraper = KabumScraper()
    elif "patoloco.com.br" in url_base:
        scraper = PatoScraper()
    else:
        print(f"Site não suportado: {url_base}")
        return

    pagina = 1
    todos_produtos = []
    paginas_sem_produtos = 0  # Contador de páginas consecutivas sem produtos

    while True:
        url = f"{url_base}?page_number={pagina}"
        produtos_da_pagina = scraper.collect_products(url)

        if not produtos_da_pagina:
            paginas_sem_produtos += 1
            print(f"Nenhum produto encontrado na página {pagina}. {paginas_sem_produtos} páginas sem produtos.")

            # Se houver 10 páginas consecutivas sem produtos, parar o scraping para essa URL
            if paginas_sem_produtos >= 10:
                print(f"Encerrando scraping para {url_base} após {paginas_sem_produtos} páginas sem produtos.")
                break
        else:
            paginas_sem_produtos = 0  # Reinicia o contador se encontrar produtos
            todos_produtos.extend(produtos_da_pagina)
            print(f"Página {pagina} processada para URL: {url_base}")

        # Parar se ultrapassar o número máximo de páginas definido
        if pagina >= max_paginas:
            print(f"Limite máximo de páginas ({max_paginas}) atingido para {url_base}.")
            break

        pagina += 1
        time.sleep(2)  # Pausa para evitar sobrecarga no servidor

    # Salvar todos os produtos coletados no banco de dados
    if todos_produtos:
        salvar_produtos_no_banco(todos_produtos)
        print(f"Total de produtos coletados e salvos para {url_base}: {len(todos_produtos)}")
    else:
        print(f"Nenhum produto foi coletado para a URL {url_base}.")

# URLs para processar
urls_para_processar = [
    "https://patoloco.com.br/produtos/placa-de-video",
    "https://patoloco.com.br/produtos/processadores",
    "https://patoloco.com.br/produtos/placas-mae",
    "https://patoloco.com.br/produtos/memorias",
    "https://patoloco.com.br/produtos/ssd",
    "https://patoloco.com.br/produtos/ssd-m2",
    "https://patoloco.com.br/produtos/coolers-e-watercoolers",
    "https://patoloco.com.br/produtos/fontes",
    "https://www.kabum.com.br/hardware/placa-de-video-vga",
    "https://www.kabum.com.br/hardware/processadores",
    "https://www.kabum.com.br/hardware/placas-mae",
    "https://www.kabum.com.br/hardware/memoria-ram",
    "https://www.kabum.com.br/hardware/ssd-2-5/ssd-sata",
    "https://www.kabum.com.br/hardware/coolers/water-cooler",
    "https://www.kabum.com.br/hardware/coolers/air-cooler",
    "https://www.kabum.com.br/hardware/fontes",
]

# Processar todas as URLs
for url in urls_para_processar:
    processar_paginas(url)
