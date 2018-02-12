**Instalace aplikace**

vytvořte novou databazi.

v souboru **app\config.neon** změňte přihlášení do databáze


    database:
        driver: mysql
        host: localhost
        dbname: tree
        user: root
        password:

Stačí pak zadat adresu aplikace a tabulka se vytvoří sama. 

Instalační sql data jsou v souboru
**app\install.sql**
