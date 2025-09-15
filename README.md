# burime
## Установка
1. `git clone https://github.com/JackRabbit911/Docker_php8.2_mysql5.7 burime`
2. `cd burime`
3. `./install.sh`
   1. `rm -r .git`
   2. `mkdir site.zone`
   3. `cd site.zone`
   4. `mkdir htdocs system`
   5. `git clone https://github.com/JackRabbit911/burime www`
   6. `cd system`
   7. `composer require alpha-zeta/framework`
   8. `cd ../htdocs`
   9. `ln -s ../www/public www`
   10. `cd ../www`
   11. `mkdir -p storage/logs storage/sessions storage/uploads`
   12. `chmod -R 777 storage`
4.  `mv <path>/.env .env` 
5. отредактировать .env
6. `./console mk:db`
7. `npm i`
8. `http://localhost/todb`
9. импорт из backup.sql.gz
10. `http://localhost`
