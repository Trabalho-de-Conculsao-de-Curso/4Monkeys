from .verificador_kabum import VerificadorProdutoKabum
from .verificador_pato import VerificadorProdutoPato

if __name__ == "__main__":
    verificador_kabum = VerificadorProdutoKabum()
    verificador_kabum.processar_produtos()

    verificador_pato = VerificadorProdutoPato()
    verificador_pato.processar_produtos()
