  

<div  align="center">

<h1>EasyAdmin_Auth</h1>

</div>

  

### Routes

  

*  `/login` - allow you to connect to EasyAdmin Back-office.

*  `/logout` - disconnect and redirection to /login.

  

*  `/_secret_backend` - Back-office access (protected)

  

*  `/forgot` - Send you an email with a link to change your password

  

*  `/reset/{token}` - Link send by email

  

*  `/api` - Api Platform (protected)

  

*  `/map` -Access to map (protected)

  
  

### Installation

  

* Rename .env.example as .env and modify these lines with your credentials :

  
   * MAILER_URL=gmail://username:password@localhost?encryption=tls&auth_mode=oauth

   * DATABASE_URL=mysql://username:password@127.0.0.1:3306/db_name

   * APP_RECAPTCHA_SITE_KEY=your_recaptcha_site_key

   * APP_RECAPTCHA_SECRET_KEY=your_recaptcha_secret_key

   * API_LOCATIONIQ_KEY=your_locationiq_api_key

*  ***composer install*** to install dependency

  

*  ***./bin/console doctrine:database:create*** to create database

  

*  ***./bin/console doctrine:schema:update --force*** to create Entity

  

*  ***php bin/console doctrine:fixtures:load*** to apply seed data to database

  

* Then you can access EasyAdmin_Auth via localhost or with command ***php -S 0.0.0.0:8001 -t public***

  

### Connection

  

* Login : admin@gmail.com

* Password: 54Br*Y781

  

### Notes

-- You can modify user seeds in src/DataFixtures/UserFixtures.php </hr>

-- This app use LocationIQ geocoder provider to return coordinates from an adress </hr>

-- Feel free to change provider in Service/GetCoordinates.php dont forget to modify API_LOCATIONIQ_KEY in   
 &nbsp; &nbsp;.env 