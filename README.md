# Glitch Gaming Platform PHP Backend
This repository holds the backend REST API of Glitch Gaming Platform, which is written in PHP. The Glitch Gaming Platform is an open source project to help people easily create their own esports websites.

## Understanding the Basics

### Laravel
Laravel is a popular MVC (Model View Controller) written in PHP that is used for developing scalable applications. The Laravel framework in this application is used as a RESTFul API for the application.

#### Invirtu/BingeWave

Invirtu is a Live Media as a Service platform for developing live streaming, video conferencing, audio conferencing, and AR apps through no-code and low-code. It will handle all of the live-streaming components in Glitch.

## Installing Locally
To install locally and run, you will require the following:

 1. Docker to be installed on your local instance
 2. Knowledge of basic command line
 3. Organizer Account with Invirtu/BingeWave
 4. Postman for connecting with APIs

First, in your `/etc/hosts`, add the following line to allow your local version to have an address:

    127.0.0.1 api.glitch.local

Then clone the repo to your local computer.

    git clone https://github.com/Glitch-Gaming-Platform/Glitch-PHP-Backend.git

Go into the clone folder and copy the sample .env.sample file to .env as such:

    cd glitch-php-backend
    cp .env.sample .env

Inside the `.env` file, you will need your Invirtu/BingeWave Organizer ID and Access Token. To obtain that:

1. Register for Invirtu/BingewWave here: [https://developers.bingewave.com/](https://developers.bingewave.com/)
2. After you have successfully completed the registration process, click on the Organizer link at the top
3. Click into Tokens for the organizer account you want to use and create a token
4. Copy the JSON Web Token into the `INVIRTU_ORGANIZER_TOKEN` in the .env, and the Organizer ID into `INVIRTU_ORGANIZER_ID` also in the .env file.
5. Optional - leave the `INVIRTU_DEFAULT_TEMPALTE_ID` for using the default template, or on BingeWave/Invirtu, create your own template.

**Important Note**: If you plan on running unit tests, copy the above value into the `.env.testing` file as well.

For the next step, please make sure to have docker installed. If you do not have docker installed, you can download it and set it up here: [https://docs.docker.com/desktop/](https://docs.docker.com/desktop/). Afterward, you can start the application using this on your command line:

    docker-compose up

Once the container has successfully started with all the services, in a separate tab on your command line, log into the container:

    docker exec -it glitch_php bash
    cd /code

The `/code` directory is the main directory for the application. Now run the following in the application:

    php artisan migrate
    php artistan db:seed
    php artisan key:generate

And you are done with installing the API with Laravel.

## Interfacing With The API

This application is only a RESTFUL API; it does not have a GUI. Therefore the best way to interface with the API is through Postman. If you have not already downloaded Postman, you can do so here: [https://www.postman.com/](https://www.postman.com/).

To access the majority of the routes in the application, you need an API key. With Postman, lets register a user so we can retrieve their JSON Web Token for Authentication. Go to the POST request in Postman and send a POST request to:

http://api.glitch.local/api/auth/register

You will receive an error response, hopefully, one that says, "you need first name, last name, etc.)". Enter those values in the Body of the request until there are no more errors and you successfully register. When you do successfully register, you will receive an auth_token.

In Postman's Authorization tab, select the Authorization type to 'Bearer Token' and enter the token. Then, you can access the rest of the routes with that token.

## License

  The project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).