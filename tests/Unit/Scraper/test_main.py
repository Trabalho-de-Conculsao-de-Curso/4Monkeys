import pytest
from unittest.mock import patch, MagicMock
from main import processar_paginas

# Dados simulados de produtos para os scrapers
produtos_mock_kabum = [
    {'nome': 'Produto Kabum 1', 'preco': 100.00, 'moeda': 'BRL', 'link': 'https://kabum.com.br/produto/1', 'disponibilidade': 1},
    {'nome': 'Produto Kabum 2', 'preco': 200.00, 'moeda': 'BRL', 'link': 'https://kabum.com.br/produto/2', 'disponibilidade': 1}
]

produtos_mock_pato = [
    {'nome': 'Produto Pato 1', 'preco': 300.00, 'moeda': 'BRL', 'link': 'https://patoloco.com.br/produto/1', 'disponibilidade': 1},
    {'nome': 'Produto Pato 2', 'preco': 400.00, 'moeda': 'BRL', 'link': 'https://patoloco.com.br/produto/2', 'disponibilidade': 1}
]

@pytest.fixture
def mock_kabum_scraper():
    with patch('main.KabumScraper') as MockScraper:
        instance = MockScraper.return_value
        instance.collect_products.return_value = produtos_mock_kabum
        yield instance

@pytest.fixture
def mock_pato_scraper():
    with patch('main.PatoScraper') as MockScraper:
        instance = MockScraper.return_value
        instance.collect_products.return_value = produtos_mock_pato
        yield instance

@pytest.fixture
def mock_salvar_produtos():
    with patch('main.salvar_produtos_no_banco') as mock_salvar:
        yield mock_salvar

# Testando o scraping para Kabum
def test_processar_paginas_kabum(mock_kabum_scraper, mock_salvar_produtos):
    url = "https://www.kabum.com.br/produtos"
    processar_paginas(url)

    # Verifica se o scraper foi chamado com a URL correta
    mock_kabum_scraper.collect_products.assert_called()


# Testando o scraping para PatoLoco
def test_processar_paginas_pato(mock_pato_scraper, mock_salvar_produtos):
    url = "https://patoloco.com.br/produtos"
    processar_paginas(url)

    # Verifica se o scraper foi chamado com a URL correta
    mock_pato_scraper.collect_products.assert_called()



# Testando quando não há produtos retornados
def test_processar_paginas_sem_produtos(mock_kabum_scraper, mock_salvar_produtos):
    mock_kabum_scraper.collect_products.return_value = []

    url = "https://www.kabum.com.br/produtos"
    processar_paginas(url)

    # Verifica que a função salvar_produtos_no_banco não foi chamada porque não havia produtos
    mock_salvar_produtos.assert_not_called()

# Testando o limite máximo de páginas
def test_processar_paginas_limite_paginas(mock_kabum_scraper, mock_salvar_produtos):
    # Simulando o comportamento de várias páginas
    mock_kabum_scraper.collect_products.side_effect = lambda url: produtos_mock_kabum if 'page_number=1' in url else []

    url = "https://www.kabum.com.br/produtos"
    processar_paginas(url, max_paginas=2)

    # Verifica se o scraper foi chamado apenas duas vezes
    assert mock_kabum_scraper.collect_products.call_count == 2

    # Verifica se os produtos da primeira página foram salvos
    mock_salvar_produtos.assert_called_with(produtos_mock_kabum)

# Testando o encerramento após páginas sem produtos
def test_processar_paginas_max_paginas_sem_produtos(mock_kabum_scraper, mock_salvar_produtos):
    # Simulando que nenhuma página retorna produtos
    mock_kabum_scraper.collect_products.return_value = []

    url = "https://www.kabum.com.br/produtos"
    processar_paginas(url, max_paginas_sem_produtos=3)

    # Verifica que a função salvar_produtos_no_banco não foi chamada porque não havia produtos
    mock_salvar_produtos.assert_not_called()
    assert mock_kabum_scraper.collect_products.call_count == 3  # Porque max_paginas_sem_produtos é 3
