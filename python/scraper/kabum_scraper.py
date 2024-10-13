import requests
from bs4 import BeautifulSoup
import json

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
            currency = "BRL"

            if image_url:
                parts = image_url.split('/')
                if len(parts) >= 8:
                    product_id = parts[6]
                else:
                    product_id = parts[-2] if len(parts) > 2 else None

                if product_id:
                    product_url = f"https://www.kabum.com.br/produto/{product_id}"
                    if price_with_discount:
                        resultados.append({
                            'nome': name,
                            'preco': price_with_discount,
                            'moeda': currency,
                            'link': product_url,
                            'disponibilidade': 1
                        })

        return resultados
