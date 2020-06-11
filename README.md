# Construtor de app web por tabela

Este projeto tem como objetivo construir um app MVC com from em HTML, JavaScript e CSS simples com apenas 1 click.
Para tanto você deve modelar sua aplicação apartir do banco de dados, criando as tabelas e relacionamentos diretamente.

## Segurança e Vulnerabilidades
Este projeto foi desenvolvido por mim em 2013 somente para estudo, ele não contempla nenhum tipo de login, token ou senha, 
porém isso pode ser facilmente implementado pelo desenvolvedor que utiliza-lo. Pois após ser executado ele faz todo o 
processo chato de criação do esqueleto do projeto. Criando as entidades controladores de visões bem como acesso facil e 
rapido ao banco de dados.

## Licença de uso
Este projeto é livre para ser usado e alterado.

## Informações tecnicas:

    ## Instalação
    ```
    #Primeiro configure seu banco de bados com tabelas e relacionamentos
     dentro da pasta engineer tem um script (DBTest.sql) com uma base de dados exemplo 
     para ajuda-lo a se orientar na criação do banco de dados.
    
    $ git clone git@github.com:andrelokal/project-builder.git
    
    #dentro da pasta engineer
    $ cp config.ini.example config.ini
    $ nano config.ini
    
    #altere o arquivo colocando suas informações e salve "CTRL O" depois "CTRL X"
    
    #de permissões de criação escrita e leitura na pasta do projeto.
    #exemplo:
    
    $ sudo chmod 777 -R /var/www/meusite
    
    #depois de finalizada as devidas configurações acesse:
    
    http://meusite/engineer
    
    #após terminar a instalações você pode apagar a pasta engineer
    
    ```
### Requisitos:
    PHP instalado MySQL e Nginx ou Apache
    Módulo PDO para o MySql Habilitado  
    
## Autor
    * André Martos - andrelokal@gmail.com





