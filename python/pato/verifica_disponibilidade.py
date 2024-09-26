import sqlite3
import requests
from bs4 import BeautifulSoup

# Função para verificar disponibilidade do produto no site
def verificar_disponibilidade_produto(url):
    response = requests.get(url)
    soup = BeautifulSoup(response.content, 'html.parser')

    # Verifica se há uma mensagem de produto indisponível
    produto_indisponivel = soup.select_one('div.alert span.alert.alert-danger')

    if produto_indisponivel and "indisponível" in produto_indisponivel.get_text(strip=True).lower():
        return False

    # Verifica se a página contém o erro 404 (página não encontrada)
    pagina_erro_404 = soup.select_one('div.text-center h1')
    if pagina_erro_404 and pagina_erro_404.get_text(strip=True) == "404":
        return False

    return True


# Função para verificar a disponibilidade dos produtos já salvos no banco de dados
def verificar_disponibilidade_no_banco():
    conn = sqlite3.connect('../../database/database.sqlite')
    cursor = conn.cursor()

    # Selecionar todas as URLs da tabela loja_online e seus IDs
    cursor.execute('SELECT id, urlLoja FROM loja_online')
    produtos_no_banco = cursor.fetchall()

    for produto in produtos_no_banco:
        loja_online_id, url = produto
        print(f"Verificando disponibilidade do produto com URL: {url}")

        # Verificar se o produto ainda está disponível
        disponivel = verificar_disponibilidade_produto(url)
        if not disponivel:
            print(f"Produto com URL {url} está indisponível. Alterando disponibilidade para 0.")

            # Altera a disponibilidade do produto para 0 na tabela produtos
            cursor.execute('UPDATE produtos SET disponibilidade = 0 WHERE loja_online_id = ?', (loja_online_id,))
            conn.commit()

            # Verifica se o produto está presente na tabela estoque
            cursor.execute('SELECT id FROM estoque WHERE produto_id IN (SELECT id FROM produtos WHERE loja_online_id = ?)', (loja_online_id,))
            estoque_item = cursor.fetchone()

            # Se o produto estiver no estoque, removê-lo
            if estoque_item:
                print(f"Removendo produto com loja_online_id {loja_online_id} da tabela estoque.")
                cursor.execute('DELETE FROM estoque WHERE produto_id = (SELECT id FROM produtos WHERE loja_online_id = ?)', (loja_online_id,))
                conn.commit()

        else:
            print(f"Produto com URL {url} está disponível.")

            # Verifica se o produto está marcado como indisponível no banco
            cursor.execute('SELECT disponibilidade FROM produtos WHERE loja_online_id = ?', (loja_online_id,))
            disponibilidade_atual = cursor.fetchone()

            # Se o produto estiver marcado como indisponível (0) no banco, atualiza para 1
            if disponibilidade_atual and disponibilidade_atual[0] == 0:
                print(f"Produto com URL {url} está disponível, mas marcado como indisponível no banco. Alterando disponibilidade para 1.")
                cursor.execute('UPDATE produtos SET disponibilidade = 1 WHERE loja_online_id = ?', (loja_online_id,))
                conn.commit()

                # Verificar se o produto já está no estoque, caso contrário, adicioná-lo
                cursor.execute('SELECT id FROM estoque WHERE produto_id IN (SELECT id FROM produtos WHERE loja_online_id = ?)', (loja_online_id,))
                estoque_item = cursor.fetchone()

                if not estoque_item:
                    print(f"Adicionando produto com loja_online_id {loja_online_id} de volta ao estoque.")
                    cursor.execute('''
                        INSERT INTO estoque (produto_id, created_at, updated_at)
                        VALUES ((SELECT id FROM produtos WHERE loja_online_id = ?), CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)
                    ''', (loja_online_id,))
                    conn.commit()

    conn.close()

# Executar a verificação
verificar_disponibilidade_no_banco()
