import sqlite3
import logging
from settings import DB_NAME

def connect_db():
    try:
        return sqlite3.connect(DB_NAME)
    except sqlite3.Error as e:
        logging.error(f"Erro ao conectar ao banco de dados: {e}")
        return None

def salvar_produtos_no_banco(produtos):
    conn = connect_db()
    cursor = conn.cursor()

    for produto in produtos:
        try:
            cursor.execute('''
                SELECT id FROM loja_online WHERE urlLoja = ?
            ''', (produto['link'],))
            loja_online_result = cursor.fetchone()

            if loja_online_result:
                logging.info(f"Produto com o link {produto['link']} já existe. Ignorado.")
                continue

            cursor.execute('''
                INSERT INTO loja_online (urlLoja, valor, moeda, created_at, updated_at)
                VALUES (?, ?, ?, datetime('now'), datetime('now'))
            ''', (produto['link'], produto['preco'], produto['moeda']))
            loja_online_id = cursor.lastrowid

            cursor.execute('''
                INSERT INTO produtos (nome, loja_online_id, disponibilidade, created_at, updated_at)
                VALUES (?, ?, ?, datetime('now'), datetime('now'))
            ''', (produto['nome'], loja_online_id, produto['disponibilidade']))
            produto_id = cursor.lastrowid

            if produto['disponibilidade'] == 1:
                cursor.execute('''
                    INSERT INTO estoque (produto_id, created_at, updated_at)
                    VALUES (?, datetime('now'), datetime('now'))
                ''', (produto_id,))

            conn.commit()
        except sqlite3.Error as e:
            logging.error(f"Erro ao inserir produto: {e}")
            conn.rollback()

    conn.close()
