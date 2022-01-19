## Fun House

- app calculates how many single manning minutes there were in my shop each day per week.

### Install:
1) Download the app by running:
   ```shell
   git clone git@github.com:dipsor/FunHouse.git
    ``` 
2) install php dependencies
   ```shell
    composer install
    ```
3) build local env:

   ``` rename .env.example to .env ```
   ```shell
    docker-compose up
    ```
   **If database container fails during first build, stop the containers and run again** ```docker-compose up```
   **or simply restart the database container**
4) change the host you will use to access this app
   ```shell
    sudo nano /etc/hosts 
    ```
    - inside of host file add this line:
    ```shell
      127.0.0.1 fun-house.test
    ```
5) ssh to container by running:
   ```shell
     docker exec -it funhouse-app-1 bash
    ```
6) inside the container migrate db:
   ```shell
      php artisan migrate
   ```
7) create ```database.sqlite``` file in database folder
8) test app by running in the container:
    ```shell
      vendor/bin/phpunit
    ```
