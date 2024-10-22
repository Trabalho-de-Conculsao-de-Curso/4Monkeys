# 4Monkey

4Monkey é um sistema de busca e sujestão de desktop, onde possui dois tipos de usuarios (Visitante e Premium), os usuarios selecionam 1 ou 3 tipos de software e o sistema retorna 3 conjuntos de desktop classificados em Bronze, Silver e Gold, cada conjunto atende os requisitos do software de acordo com o custo beneficio de cada categoria, o usuario Visitante tem acesso a uma descrição do desktop simple onde apresenta apenas os nomes de cada Hardware, já o usuario Premium recebe a lista detalhada de cada conjunto com nome, preço, URL para compra de cada hardware, tem direitos tambem a valor total de cada desktop, historico de pesquisa, grafico de desempenho de cada desktop.

## 🚀 Começando

Essas instruções permitirão que você obtenha uma cópia do projeto em operação na sua máquina local para fins de desenvolvimento e teste.

Consulte **[Implantação](#-implanta%C3%A7%C3%A3o)** para saber como implantar o projeto.

### 📋 Pré-requisitos

Versão minima do PHP 8.0


### 🔧 Instalação de depencias iniciais em Sistema Operacional Windows

Dependencias necessarias para Desenvolvimento do Sistema.

Instalar Composer:

```
composer install
```

Instalar NPM:

```
npm install
```

## ⚙️ Preparação e População do Banco de Dados

Para popular o Banco de Dados é necessario alguns parametros.

Criar Arquivo Database:

```
New-Item -Path "database/database.sqlite" -ItemType File
```
Criar tabelas:

```
php artisan migrate
```

Populando com Seeder do Projeto:

```
php artisan db:seed
```

## ⚙️ Preparação e População com Python no Banco de Dados

Preparação de ambiente
Validar se possui Python instalado:

```
python --version
```

Caso não tenha Python instalado [instale](https://www.python.org/) e siga os de preparação de [variavel](https://www.python.org/) de ambiente

Verifique se pip está instalado (gerenciador de pacotes do Python):
```
pip --version
```

Se pip não estiver disponível, execute:
```
python -m ensurepip --upgrade
```

Instalar biblioteca BeautifulSoup:
```
pip install beautifulsoup4
```


População de Banco de Dados
Acesse a pasta do projeto .\python\scraper
Executar Scraper:

```
python main.py
```

Verificação e praparação de Ambiente para utilizar Scraper Atualiza:
Acesse a pasta do projeto .\python

Verifique se possui o Schedule:

```
pip show schedule
```

Caso não possua execute:

```
pip install schedule
```

Criar Ambiente Virtual:

```
python -m venv venv
```

Ativando Ambiente Virual:

```
.\venv\Scripts\Activate.ps1
```

Instalar biblioteca BeautifulSoup:
```
pip install beautifulsoup4
```

Executar Scraper para atualização de Produto:

```
python -m verificador.main
```

Inicie a aplicação e acesse a rota \produtos para validar se o scraper foi realizado com sucesso.


### 🔩 Analise os testes de ponta a ponta

Os testes unitarios do PHP validam os Controllers e suas funções.

```
php artisan test
```

Os testes em Python validam a mineração de dados e o acesso as URL.

Validar o Scrapar de mineração.

```
$env:PYTHONPATH="C:\Projetos\4Monkey\python\scraper"; pytest -v -s tests/Unit/Scraper
```

Validar o Scrapar de atualização.

```
$env:PYTHONPATH="C:\Projetos\4Monkey\python"; pytest -v -s tests/Unit/VerificadorScraper
```

## 📦 Implantação

Após clonar o repositorio, é necessario alterar o arquivo .env.exemple para .env. (Sujestão copar o arquivo .env.exemple para outra pasta renomear para .env e colar novamente na pasta do projeto).

## 🛠️ Construído com

Ferramentas e Tecnologias utilizadas no Projeto.

* [PHP](https://www.php.net/) - Linguagem de WEB Back-End.
* [Laravel](https://laravel.com/) - Framework WEB Back-End.
* [Python](https://www.python.org/) - Linguagem para Mineração de Dados.
* [BeautifulSoup4](https://pypi.org/project/beautifulsoup4/) - Biblioteca para Mineração sem Interface Grafica.
* [Tailwind CSS](https://tailwindcss.com/) - Freameword WEB Front-End.
* [MaryUI](https://mary-ui.com/) - Componentes de Interface de Usuário do Laravel Blade.
* [SQLite](https://www.sqlite.org/) - Branco de Dados.


## 📌 Videos

Videos das apresentações do sistema ao decorrer do projeto

[Apresentação 1](https://www.youtube.com/watch?v=-zsy4z6TcYU)

[Apresentação 2](https://www.youtube.com/watch?v=J8_N4JVouqo)


## ✒️ Autores

Mencione todos aqueles que ajudaram a levantar o projeto desde o seu início

* **Carlos Jobbins Junior** - *Full Stack e Documentação* - [Carlos Jobbins](https://github.com/juniorjobbs)
* **Eduardo Borges Matsunaga** - *Full Stack e Documentação* - [Eduardo Matsunaga](https://github.com/Eduardo-Matsunaga)
* **Matheus Vinicius Ferreira** - *Full Stack e Documentação* - [Matheus Vinicius](https://github.com/MateusVferreira)
* **Matheus Moura de Matos** - *Full Stack e Documentação* - [Matheus Moura](https://github.com/MatheusMouraMatos)

Unidade Organizacional do Projeto [4Monkey](https://github.com/Trabalho-de-Conculsao-de-Curso).
