import sqlite3
import requests
from bs4 import BeautifulSoup
import time

# Função para coletar produtos de uma página
def coletar_produtos_da_pagina(url):
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

            resultados.append({
                'nome': nome,
                'preco': preco,
                'moeda': moeda if preco else None,
                'link': link
            })
        except Exception as e:
            print("Erro ao processar um produto:", e)
    return resultados

# Função para salvar os produtos, preços e links no banco
def salvar_produtos_no_banco(produtos):
    # Conexão
    conn = sqlite3.connect('../database/database.sqlite')
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

            if produto['preco'] is None:
                # Produto sem preço, não salva
                print(f"Produto {produto['nome']} está sem preço. Será ignorado.")
                continue  # Pula para o próximo produto

            # Inserir uma nova entrada na tabela loja_online
            cursor.execute('''
                INSERT INTO loja_online (urlLoja, valor, moeda, created_at, updated_at)
                VALUES (?, ?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)
            ''', (produto['link'], produto['preco'], produto['moeda']))
            loja_online_id = cursor.lastrowid  # Recuperar o ID da loja online inserida

            # Inserir os dados na tabela produtos
            cursor.execute('''
                INSERT INTO produtos (nome, loja_online_id, created_at, updated_at)
                VALUES (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)
            ''', (produto['nome'], loja_online_id))

            # Salvar as alterações
            conn.commit()
        except sqlite3.Error as e:
            print(f"Erro ao inserir o produto no banco de dados: {e}")
            conn.rollback()

    conn.close()

# Função para processar as páginas de produtos
def processar_paginas(url_base, max_paginas=1):
    pagina = 1
    todos_produtos = []

    while pagina <= max_paginas:
        url = f"{url_base}?page_number={pagina}"
        produtos_da_pagina = coletar_produtos_da_pagina(url)

        if not produtos_da_pagina:
            break

        # Adicionar os produtos da página à lista total de produtos
        todos_produtos.extend(produtos_da_pagina)
        print(f"Página {pagina} processada para URL: {url_base}")
        pagina += 1
        time.sleep(2)

    # Salvar todos os produtos coletados no banco de dados
    salvar_produtos_no_banco(todos_produtos)
    print(f"Total de produtos coletados e salvos para {url_base}: {len(todos_produtos)}")

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
]

# Processar todas as URLs
for url in urls_para_processar:
    processar_paginas(url)
