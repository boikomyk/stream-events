# stream-events
### This application is aimed at showing streamers a list of events that happened during their stream.


The following steps are required to start the project:

1. Find the .env file in the project root folder or create .env.local and set values to the following variables
    - DB_\* - database configuration
    - FACEBOOK_CLIENT_\* and GOOGLE_CLIENT_\* - third-party authorization configuration
2. In the root folder, run the following commands
```bash
composer install && npm install
php artisan migrate
npm run dev && php artisan serve
```


#### [Video presentation](https://drive.google.com/file/d/17Bk2CjRFCjluAe6n6uzCoF9E--WO7qj9/view?usp=sharing)
