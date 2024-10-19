import time
from urllib.parse import urlencode
from database import salvar_produtos_no_banco
from pato_scraper import PatoScraper
from kabum_scraper import KabumScraper
from settings import urls_para_processar

def processar_paginas(url_base, max_paginas=10, filters=None, max_paginas_sem_produtos=3):
    if "kabum.com.br" in url_base:
        scraper = KabumScraper()
        if filters:
            base_url_with_filters = f"{url_base}?{urlencode(filters)}"
        else:
            base_url_with_filters = url_base
        pagina_formatada = "&page_number={}"  # Formato para Kabum
    elif "patoloco.com.br" in url_base:
        scraper = PatoScraper()
        base_url_with_filters = url_base
        pagina_formatada = "/{}"  # Formato correto para o Pato
    else:
        print(f"Site não suportado: {url_base}")
        return

    pagina = 1
    todos_produtos = []
    paginas_sem_produtos = 0

    while True:
        url = f"{base_url_with_filters}{pagina_formatada.format(pagina)}"
        print(url)
        produtos_da_pagina = scraper.collect_products(url)
        print(produtos_da_pagina)

        if not produtos_da_pagina:
            paginas_sem_produtos += 1
            print(f"Nenhum produto encontrado na página {pagina}. {paginas_sem_produtos} páginas sem produtos.")
            if paginas_sem_produtos >= max_paginas_sem_produtos:
                print(f"Encerrando scraping para {url_base} após {paginas_sem_produtos} páginas sem produtos.")
                break
        else:
            paginas_sem_produtos = 0
            todos_produtos.extend(produtos_da_pagina)
            print(f"Página {pagina} processada para URL: {url_base}")

        if pagina >= max_paginas:
            print(f"Limite máximo de páginas ({max_paginas}) atingido para {url_base}.")
            break

        pagina += 1
        time.sleep(2)

    if todos_produtos:
        salvar_produtos_no_banco(todos_produtos)
        print(f"Total de produtos coletados e salvos para {url_base}: {len(todos_produtos)}")
    else:
        print(f"Nenhum produto foi coletado para a URL {url_base}.")

# Processar todas as URLs com seus filtros
for item in urls_para_processar:
    processar_paginas(item["url"], filters=item["filters"])
