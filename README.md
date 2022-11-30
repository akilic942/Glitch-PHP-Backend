
# Glitch Gaming Platform PHP Backend
##### Powered By Invirtu

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

  

When this tutorial is completed, the site will be available at http://api.glitch.local. Then clone the repo to your local computer.


    git clone https://github.com/Glitch-Gaming-Platform/Glitch-PHP-Backend.git

  

Go into the clone folder and copy the sample .env.sample file to .env as such:

    cd glitch-php-backend
    
    cp .env.example .env

Inside the `.env` file, you will need your Invirtu/BingeWave Organizer ID and Access Token. To obtain that:

1. Register for Invirtu/BingewWave here: [https://developers.bingewave.com/](https://developers.bingewave.com/)

2. After you have successfully completed the registration process, click on the Organizer link at the top

3. Click into Tokens for the organizer account you want to use and create a token

4. Copy the JSON Web Token into the `INVIRTU_ORGANIZER_TOKEN` in the .env, and the Organizer ID into `INVIRTU_ORGANIZER_ID` also in the .env file.

5. Optional - leave the `INVIRTU_DEFAULT_TEMPALTE_ID` for using the default template, or on BingeWave/Invirtu, create your own template.
  

**Important Note**: If you plan on running unit tests, copy the above value into the `.env.testing` file as well.

  

For the next step, please make sure to have docker installed. If you do not have docker installed, you can download it and set it up here: [https://docs.docker.com/desktop/](https://docs.docker.com/desktop/). Afterward, you can start the application using this on your command line:

    docker-compose up
    
If you have an error that is something like 'failed to build Mailhog', the error is caused because we are using the arm64 version of GO. To fix, head over to the Go development site as [https://go.dev/dl/](https://go.dev/dl/), and find the install package that fits your computer. Then in the Dockerfile, change the go installation process for your version and try to run `docker-compose up` again.

Once the container has successfully started with all the services, in a separate tab on your command line, log into the container and go the the `/code` directory:

    docker exec -it glitch_php bash
    
    cd /code

The `/code` directory is the main directory for the application. Now run the following in the application:

    composer install
    
    php artisan migrate
    
    php artistan db:seed
    
    php artisan key:generate
    
    php artisan jwt:secret
  
#### Important Final Step
For the final step, in your browser go to https://api.glitch.local . You will receive a warning about an untrusted SSL Certificate. Accept the certificate and proceed to the site. If you do not accept the certificate for your browser, you may experience problems connecting from the frontend site.

Your backend is now setup! The backend is meant to connect with the frontend of the site at this repo: [https://github.com/Glitch-Gaming-Platform/Glitch-React-Frontend](https://github.com/Glitch-Gaming-Platform/Glitch-React-Frontend).

  

## Interfacing With The API

  

This application is only a RESTFUL API; it does not have a GUI. Therefore the best way to interface with the API is through Postman. If you have not already downloaded Postman, you can do so here: [https://www.postman.com/](https://www.postman.com/).

  

To access the majority of the routes in the application, you need an API key. With Postman, lets register a user so we can retrieve their JSON Web Token for Authentication. Go to the POST request in Postman and send a POST request to:

  

http://api.glitch.local/api/auth/register

  

You will receive an error response, hopefully, one that says, "you need first name, last name, etc.)". Enter those values in the Body of the request until there are no more errors and you successfully register. When you do successfully register, you will receive an auth_token.

In Postman's Authorization tab, select the Authorization type to 'Bearer Token' and enter the token. Then, you can access the rest of the routes with that token.

## Environment Values To Fill In
To have the most complete experience, especially with the frontend, you should make sure your .env values are fill in. **These values are not required!** The values you should fill in are:

    INVIRTU_ORGANIZER_ID=
    INVIRTU_ORGANIZER_TOKEN=
    INVIRTU_DEFAULT_TEMPALTE_ID=e4d6c282-51cd-4911-850e-c94a25de758d
    
    
    FACEBOOK_CLIENT_ID=
    FACEBOOK_CLIENT_SECRET=
    FACEBOOK_REDIRECT_URI=
    FACEBOOK_REDIRECT_BACK_TO_SITE=
    
    
    TWITCH_CLIENT_ID=
    TWITCH_CLIENT_SECRET=
    TWITCH_REDIRECT_URI=
    TWITCH_REDIRECT_BACK_TO_SITE=
    
    
    YOUTUBE_CLIENT_ID=
    YOUTUBE_CLIENT_SECRET=
    YOUTUBE_REDIRECT_URI=
    YOUTUBE_REDIRECT_BACK_TO_SITE=
    
    
    AWS_ACCESS_KEY_ID=
    AWS_SECRET_ACCESS_KEY=
    AWS_DEFAULT_REGION=
    AWS_BUCKET=
      


## Testing

If you are going to make changes to that platform and submit a PR, it may pass the testing that has been implemented. If you look at the `/tests`, there are quite a few tests for everything ranging from models to routes.

Please be sure to add your tests the appropriate area for the functionality that you add or change.

  

## OAuth Accounts (Twitch, Facebook, Youtube)

  

One of the goals with Platform is the ability the easiliy authenticate and then restream to other platforms. To accomplished, follow the OAuth steps below.

  

### Youtube

  

To authenticate with Youtube, a developer account with Google is required. Most of the information to authenticate with you is in this [Article On Authenticating With You](https://developers.google.com/youtube/v3/guides/auth/server-side-web-apps).

  

To sum it up:

1. Create a developer account with Google

2. Follow the guide above on creating an application for Youtube with the correct scope permissions

3. After going through all the steps in creating an application, click on Credentials

4. Creat the credentials for a web applications. But sure to allow the correct auth redirects

5. Once you obtain the client id and secret, put it into your .env file GOOGLE_OAUTH section.

  

### Twitch

  

Users can authenticate with Twitch on the website. To do so:

  

1. Head over to [Twitch Developers Site](https://dev.twitch.tv/)

2. Register for an account or login if you already have a developers accounts.

3. Go the "Register Your Application" section.

4. Register a new application and when you do, make sure you auth redirect are correct.

5. After the application is registered, click into it and get the Client ID and create a new Client Screent

6. Copy those values into your .env file int he YOUTUBE_OAUTH section.

  

### Facebook

  

Users can authenticate with Facebook on the website, with the Goal of automatically streaming to Facebook one day. To authenticate with Facebook:

  

1. Header over to [Facebook Developers Site](https://developers.facebook.com/)

2. Register for a developers account or login and click on My Apps if already have a developers account.

3. Create a new application and select Gaming as the category.

  

## License

  

The project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
