import sqlite3
import requests
from bs4 import BeautifulSoup
from abc import ABC, abstractmethod

# Classe abstrata para verificador de produtos
class VerificadorProdutos(ABC):
    def processar_produtos(self):
        # Primeiro, verificar a disponibilidade dos produtos e atualizar os dados
        self.verificar_e_atualizar_disponibilidade_no_banco()

    def verificar_e_atualizar_disponibilidade_no_banco(self):
        conn = sqlite3.connect('../database/database.sqlite')
        cursor = conn.cursor()

        # Selecionar todas as URLs da tabela loja_online e os dados correspondentes
        cursor.execute('''
            SELECT produtos.id, produtos.nome, loja_online.valor, loja_online.urlLoja, produtos.disponibilidade
            FROM produtos
            JOIN loja_online ON produtos.loja_online_id = loja_online.id
        ''')
        produtos_no_banco = cursor.fetchall()

        # Filtrar URLs específicas do site
        produtos_no_banco = self.filtrar_urls(produtos_no_banco)

        for produto_banco in produtos_no_banco:
            produto_id, nome_banco, preco_banco, url, disponibilidade_atual = produto_banco
            print(f"Verificando produto com URL: {url}")

            # Coletar os dados atuais do produto a partir da página
            dados_produto_pagina = self.coletar_dados_produto_pagina(url)
            disponivel = self.verificar_disponibilidade_produto(url)

            if not disponivel:
                # Produto indisponível - Remover do estoque e atualizar a disponibilidade no banco
                print(f"Produto com URL {url} está indisponível. Alterando disponibilidade para 0.")
                cursor.execute('UPDATE produtos SET disponibilidade = 0 WHERE id = ?', (produto_id,))
                conn.commit()

                # Remover do estoque se estiver presente
                cursor.execute('SELECT id FROM estoque WHERE produto_id = ?', (produto_id,))
                estoque_item = cursor.fetchone()
                if estoque_item:
                    print(f"Removendo produto com ID {produto_id} da tabela estoque.")
                    cursor.execute('DELETE FROM estoque WHERE produto_id = ?', (produto_id,))
                    conn.commit()

            else:
                # Produto disponível - Atualizar informações se necessário
                if dados_produto_pagina:
                    nome_atualizado = dados_produto_pagina['nome']
                    preco_atualizado = dados_produto_pagina['preco']

                    # Verificar e atualizar o nome
                    if nome_atualizado != nome_banco:
                        print(f"Nome diferente encontrado. Atualizando de '{nome_banco}' para '{nome_atualizado}'")
                        cursor.execute('''
                            UPDATE produtos
                            SET nome = ?
                            WHERE id = ?
                        ''', (nome_atualizado, produto_id))
                        conn.commit()

                    # Verificar e atualizar o preço
                    if preco_atualizado != preco_banco:
                        print(f"Preço diferente encontrado. Atualizando de {preco_banco} para {preco_atualizado}")
                        cursor.execute('''
                            UPDATE loja_online
                            SET valor = ?
                            WHERE id = (SELECT loja_online_id FROM produtos WHERE id = ?)
                        ''', (preco_atualizado, produto_id))
                        conn.commit()

                    # Verificar se o produto estava marcado como indisponível e atualizá-lo
                    if disponibilidade_atual == 0:
                        print(f"Produto com URL {url} está disponível, mas marcado como indisponível no banco. Alterando disponibilidade para 1.")
                        cursor.execute('UPDATE produtos SET disponibilidade = 1 WHERE id = ?', (produto_id,))
                        conn.commit()

                        # Adicionar ao estoque se não estiver presente
                        cursor.execute('SELECT id FROM estoque WHERE produto_id = ?', (produto_id,))
                        estoque_item = cursor.fetchone()
                        if not estoque_item:
                            print(f"Adicionando produto com ID {produto_id} de volta ao estoque.")
                            cursor.execute('''
                                INSERT INTO estoque (produto_id, created_at, updated_at)
                                VALUES (?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)
                            ''', (produto_id,))
                            conn.commit()

                else:
                    # Não foi possível coletar os dados do produto - Remover do banco
                    print(f"Não foi possível coletar os dados do produto com URL: {url}. Removendo do banco.")
                    cursor.execute('DELETE FROM produtos WHERE id = ?', (produto_id,))
                    cursor.execute('DELETE FROM loja_online WHERE id = (SELECT loja_online_id FROM produtos WHERE id = ?)', (produto_id,))
                    conn.commit()

        conn.close()

    @abstractmethod
    def verificar_disponibilidade_produto(self, url):
        pass

    @abstractmethod
    def coletar_dados_produto_pagina(self, url):
        pass

    @abstractmethod
    def filtrar_urls(self, produtos):
        pass

# Subclasse para Kabum
class VerificadorProdutoKabum(VerificadorProdutos):
    def verificar_disponibilidade_produto(self, url):
        response = requests.get(url)
        soup = BeautifulSoup(response.content, 'html.parser')

        # Verifica se o SVG com a classe "IconUnavailable" está presente na página
        produto_indisponivel_svg = soup.select_one('svg.IconUnavailable')

        # Verifica se a div que contém a mensagem de indisponibilidade está presente
        produto_indisponivel_texto = soup.select_one('div#main-content div.sc-4e2d4867-0.kfefWY div.sc-4e2d4867-1.dPPiZL span.sc-4e2d4867-2.ccbyaK')

        # Se o SVG ou o texto de indisponibilidade for encontrado, o produto está indisponível
        if produto_indisponivel_svg or (produto_indisponivel_texto and "Ops... Produto indisponível!" in produto_indisponivel_texto.get_text(strip=True)):
            return False

        return True

    def coletar_dados_produto_pagina(self, url):
        response = requests.get(url)
        soup = BeautifulSoup(response.content, 'html.parser')

        try:
            # Nome do produto
            nome = soup.select_one('div.col-purchase h1').get_text(strip=True)

            # Preço do produto
            preco_element = soup.select_one('h4.finalPrice')
            if preco_element:
                preco_texto = preco_element.get_text(strip=True)
                preco = float(preco_texto.replace('R$', '').replace('.', '').replace(',', '.').strip())
            else:
                preco = None

            return {'nome': nome, 'preco': preco}

        except Exception as e:
            print(f"Erro ao coletar dados da página {url}: {e}")
            return None

    def filtrar_urls(self, produtos):
        # Retorna apenas os produtos com URLs que pertencem ao domínio da Kabum
        return [produto for produto in produtos if produto[3].startswith('https://www.kabum.com.br')]

# Subclasse para Pato Loco
class VerificadorProdutoPato(VerificadorProdutos):
    def verificar_disponibilidade_produto(self, url):
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

    def coletar_dados_produto_pagina(self, url):
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

    def filtrar_urls(self, produtos):
        # Retorna apenas os produtos com URLs que pertencem ao domínio do Pato Loco
        return [produto for produto in produtos if produto[3].startswith('https://patoloco.com.br')]

# Executar a verificação e atualização para Kabum
verificador_kabum = VerificadorProdutoKabum()
verificador_kabum.processar_produtos()

# Executar a verificação e atualização para Pato Loco
verificador_pato = VerificadorProdutoPato()
verificador_pato.processar_produtos()
