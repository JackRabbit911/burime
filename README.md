# burime
## Установка
1. `git clone https://github.com/JackRabbit911/Docker_php8.2_mysql5.7 burime`
2. `./install.sh`
   1. `rm -r .git`
   2. `mkdir site.zone`
   3. `cd site.zone`
   4. `mkdir htdocs system`
   5. `git clone https://github.com/JackRabbit911/burime www`
   6. `cd system`
   7. `composer require alpha-zeta/framework`
   8. `cd ../www`
   9. `ln -s ./public ../htdocs/www`
   10. `mkdir -p storage\logs storage\sessions storage\uploads`
   11. `chmod -R 777 storage`
3.  `mv <path>/.env .env` 
4. отредактировать .env
5. `./console mk:db`
6. `npm i`
7. `http://localhost/todb`
8. импорт из backup.sql.gz
9. `http://localhost`
