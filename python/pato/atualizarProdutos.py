import sqlite3
import requests
from bs4 import BeautifulSoup

# Função para coletar os dados de um produto a partir da página
def coletar_dados_produto_pagina(url):
    response = requests.get(url)
    soup = BeautifulSoup(response.content, 'html.parser')

    try:
        # Nome do produto
        nome = soup.select_one('h1.h4.product-name span[itemprop="name"]').get_text(strip=True)

        # Preço do produto
        preco_element = soup.select_one('span[itemprop="price"]')
        if preco_element:
            preco_texto = preco_element.get_text(strip=True)
            preco = float(preco_texto.replace('R$', '').replace('.', '').replace(',', '.').strip())
        else:
            preco = None

        return {'nome': nome, 'preco': preco}

    except Exception as e:
        print(f"Erro ao coletar dados da página {url}: {e}")
        return None

# Função para verificar e atualizar os produtos no banco de dados
def verificar_e_atualizar_produtos_no_banco():
    conn = sqlite3.connect('../../database/database.sqlite')
    cursor = conn.cursor()

    # Selecionar todas as URLs da tabela loja_online e os dados correspondentes
    cursor.execute('''
        SELECT produtos.id, produtos.nome, loja_online.valor, loja_online.urlLoja
        FROM produtos
        JOIN loja_online ON produtos.loja_online_id = loja_online.id
    ''')
    produtos_no_banco = cursor.fetchall()

    for produto_banco in produtos_no_banco:
        produto_id, nome_banco, preco_banco, url = produto_banco
        print(f"Verificando produto com URL: {url}")

        # Coletar os dados atuais do produto a partir da página
        dados_produto_pagina = coletar_dados_produto_pagina(url)

        if dados_produto_pagina:
            nome_atualizado = dados_produto_pagina['nome']
            preco_atualizado = dados_produto_pagina['preco']

            # Verificar se houve mudança no nome ou no preço
            if nome_atualizado != nome_banco:
                print(f"Nome diferente encontrado. Atualizando de '{nome_banco}' para '{nome_atualizado}'")
                cursor.execute('''
                    UPDATE produtos
                    SET nome = ?
                    WHERE id = ?
                ''', (nome_atualizado, produto_id))
                conn.commit()

            if preco_atualizado != preco_banco:
                print(f"Preço diferente encontrado. Atualizando de {preco_banco} para {preco_atualizado}")
                cursor.execute('''
                    UPDATE loja_online
                    SET valor = ?
                    WHERE id = (SELECT loja_online_id FROM produtos WHERE id = ?)
                ''', (preco_atualizado, produto_id))
                conn.commit()

        else:
            print(f"Não foi possível coletar os dados do produto com URL: {url}")

    conn.close()

# Executar a verificação e atualização dos produtos no banco de dados
verificar_e_atualizar_produtos_no_banco()
