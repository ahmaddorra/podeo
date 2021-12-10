# podeo
## What to do:
### cd into the project then please run the following
```
docker-compose build
docker-compose up
docker exec podeo bash -c "cd /var/www/podeo;php artisan storage:link; php artisan migrate:install; php artisan migrate:fresh"
```
 
**Please dont use php artisan serve
the website is configured to work with apache 
index.php is at the root folder.....

Docker was really coooooooolll, i will definitely use it in the future
thank youuu for your time and
for giving me the opportunity
**
