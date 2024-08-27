import sqlite3
import requests
from bs4 import BeautifulSoup

# Função para verificar disponibilidade do produto
def verificar_disponibilidade_produto(url):
    response = requests.get(url)
    soup = BeautifulSoup(response.content, 'html.parser')

    # Verificar se a div .row contém a div .alert com a mensagem de indisponibilidade
    alerta_div = soup.select_one('div.row div.alert span.alert.alert-danger')
    if alerta_div and "indisponível" in alerta_div.get_text(strip=True).lower():
        return False

    return True

# Função para verificar a disponibilidade dos produtos já salvos no banco de dados
def verificar_disponibilidade_no_banco():

    conn = sqlite3.connect('../../database/database.sqlite')
    cursor = conn.cursor()

    # Selecionar todas as URLs da tabela loja_online
    cursor.execute('SELECT id, urlLoja FROM loja_online')
    produtos_no_banco = cursor.fetchall()

    for produto in produtos_no_banco:
        loja_online_id, url = produto
        print(f"Verificando disponibilidade do produto com URL: {url}")

        # Verificar se o produto ainda está disponível
        if not verificar_disponibilidade_produto(url):
            print(f"Produto com URL {url} está indisponível. Removendo do banco de dados.")

            # Remover o produto da tabela produtos e da tabela loja_online
            cursor.execute('DELETE FROM produtos WHERE loja_online_id = ?', (loja_online_id,))
            cursor.execute('DELETE FROM loja_online WHERE id = ?', (loja_online_id,))
            conn.commit()
        else:
            print(f"Produto com URL {url} está disponível.")

    conn.close()


verificar_disponibilidade_no_banco()
