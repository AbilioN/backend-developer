# Loopa Desafio

Esta api consiste numa aplicação para fechar vendas a partir de um arquivo de texto conforme especificado na descrição do desafio, o arquivo teste é o sales.txt que encontra-se na raiz deste repositório.




## Instalação:

Para instalar e usar esta api basta clonar este repositório e rodar o comando "composer install" para baixar as dependências, caso o terminal não reconheça este comando deve-se instalar gerenciador de dependências Composer em https://getcomposer.org/download/, após a instalação é necessário abrir o terminal na pasta raiz do projeto e rodar o comando: "php -S localhost:8000 -t ./public", após servir a api na porta 8000, basta criar um request no Postman ou Insomnia com o método POST para a rota http://localhost:8000/upload e enviar no body da requisição apenas uma chave chamada file do tipo arquivo, e adicionar o arquivo sales.txt que encontra-se na raiz deste repositorio.
