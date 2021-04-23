# Starhotel manager web application


## Setup 
1. CMD to folder  _temp/docker/??? --> ***Build Docker***
    - folder db : *** docker build -t romidb ./ ***
    - folder web : *** docker build -t romiweb ./ ***

2. Run 2 Docker
    - docker run -d -v D:\Hotel\dockerdb\romi:/var/lib/mysql --name romidb romidb
    - docker run -d --link romidb:romidb  -v your-path\romi:/var/www/html --name romiweb romiweb 

3. Create a folder at the same level as the project dockerdb / romidb

### config
```
port of db : 3306 : 3309
port of web: 80 : 8008
port of XDebug in web hostname/port : 9009:9000
```
> General in web : XDEBUG_CONFIG : remote_host=192.168.1.8 ( ip adress wirless)

4. Setting mysql db: (***Exec by Kitematic***)
```GRANT ALL PRIVILEGES ON romidb.* TO 'root'@'%' WITH GRANT OPTION; ```
- Allow conection DB 
    - GRANT ALL PRIVILEGES ON . TO 'root'@'%' IDENTIFIED BY 'debian' WITH GRANT OPTION;
    - mysql -h 172.0.0.1 -u root -p

5. Install php local > 5.5 
6. composer config -g -- disable-tls true
7. composer install --> from kimatic 
8. Configure .env and .env.test files.
9. Configure migrations-db.php
    - create version to migrate : ***php migrations.php generate***
    - Perform migration to feed data : ***php migrations.php migrate***

10. Deploy schema
```
php vendor/bin/doctrine orm:schema-tool:create
php vendor/bin/doctrine orm:schema-tool:update --force
php vendor/bin/doctrine orm:schema-tool:drop --force
php vendor/bin/doctrine orm:clear-cache:metadata
add folder var/doctrine; var/logs
```

11. Setting XDebug: 
- if hasn't file xdebug.ini, Create file xdebug.ini
```
cp /var/www/html/20-xdebug.ini 
cp /etc/php/7.0/apache2/conf.d/20-xdebug.ini /var/www/html/_temp
```

- if had flie xdebug.ini
```
cat /etc/php/7.0/apache2/conf.d/20-xdebug.ini
cp /var/www/html/_temp/20-xdebug.ini /etc/php/7.0/apache2/conf.d/
```

```
Config file launch.json 
{
    // Use IntelliSense to learn about possible attributes.
    // Hover to view descriptions of existing attributes.
    // For more information, visit: https://go.microsoft.com/fwlink/?linkid=830387
    "version": "0.2.0",
    "configurations": [
        {
            "name": "Listen for XDebug",
            "type": "php",
            "request": "launch",
            "pathMappings": {
                "/var/www/html":"${workspaceRoot}",
            },
            "port": 9000
        },
        {
            "name": "Launch currently open script",
            "type": "php",
            "request": "launch",
            "program": "${file}",
            "cwd": "${fileDirname}",
            "port": 9000
        }
    ]
}

```

12. Setting another composer: 
    - Composer ACL: 
        - composer require zendframework/zend-permissions-acl
        - https://framework.zend.com/blog/2017-05-09-zend-permissions-acl.html
    - Composer swift mailer: 
        - composer require andrewdyer/slim3-mailer
        - https://github.com/andrewdyer/slim3-mailer
