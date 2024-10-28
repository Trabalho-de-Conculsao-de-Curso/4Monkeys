import schedule
import time
from .verificador_kabum import VerificadorProdutoKabum
from .verificador_pato import VerificadorProdutoPato

def executar_verificadores():
    verificador_kabum = VerificadorProdutoKabum()
    verificador_kabum.processar_produtos()

    verificador_pato = VerificadorProdutoPato()
    verificador_pato.processar_produtos()

if __name__ == "__main__":
    # Agenda a execução a cada 1 minuto
    schedule.every(1).minutes.do(executar_verificadores)

    # Mantém o script rodando indefinidamente
    while True:
        schedule.run_pending()
        time.sleep(1)  # Aguarda 1 segundo antes de verificar novamente
