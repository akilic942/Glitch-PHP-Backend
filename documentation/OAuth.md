
# OAuth
For the repo, there are several OAuth methods that can be used to authenticate users. Each one simply requires updating the .env and redirecting the user to an auth route. Read the documentation below on how to customize OAuth on the site.

## Basic Concepts
A few reoccurring concepts for the OAuth implementation will be described below to help you with your setup.

**Route To OAuth**
The route in which the user will go into their browser that will begin the authentication process for that service.

**Callback Url**
In many services, there will be a URL to redirect the user back to inside the application after they have completed the other process on the other service. Often this URL should be added to the allowed callbacks in the service and should be set the .env as the [SERVICE]_REDIRECT_URI.

**[SERVICE]_REDIRECT_BACK_TO_SITE**
If no redirect has been explicitly set to execute after the authentication has occurred, this should be the URL to redirect the user to after the OAuth process is complete.

**Override Default Redirect URI**

By default, the redirect URI that the user will be redirected to after the OAuth is completed is set in the above configuration. But this value can be overridden by adding the query parameter 'redirect=[uri]' to the query string in the initial auth route.

For example, if authenticating to Facebook and you want the user to return to the route '/different/page', the redirect URI can be overridden as such:

`http://www.example.com/auth/facebook/redirect?redirect=http://www.example.com/different/page`

**ENV File Params**
The parameters should be placed inside a .env file or in the production environment and should be passed in as an environment variable.

## Important Note On-Time Login Tokens

The OAuth's presented in the documentation can not only be used for registering the user but a JWT from the service can be stored and use to access the service on behalf of the user. To use the service not as login but to associate a JWT with a user, you must pass a one-time login token to the redirect uri.

For example, if the OAuth was Facebook, call the initial redirect with the one-time login token as such:

`https://www.example.com/auth/facebook/redirect?token=xxxxxxxx`

With the token, the user will be logged and the data retrieved from the OAuth will be associated with that user.

## Important Note On Logging In The User In After The Redirect

When a user authenticates on this backend site and is redirected to the frontend site, they will NOT be logged on to the frontend application yet if they did NOT use a one-time login token as indicated above. Instead, the redirect will pass a one-time login token back to the frontend that must be exchanged for the user's authentication token.

For example, if the redirect URI back to the frontend is `http://www.example.com/myaccount`, the auth process will append the following parameter:

`http://www.example.com/myaccount?loginToken=xxxxxxxx`

That login token can be exchanged for a JSON Web Token by calling the route to your backend at `auth/oneTimeLoginWithToken`.

## Services

### Facebook

**Route To OAuth**
https://[domain]/auth/facebook/redirect

**Callback Url**
https://[domain]/auth/facebook/callback

**ENV File Params**

    FACEBOOK_CLIENT_ID=
    FACEBOOK_CLIENT_SECRET=
    FACEBOOK_REDIRECT_URI=
    FACEBOOK_REDIRECT_BACK_TO_SITE=
***

### Twitch

**Route To OAuth**
https://[domain]/auth/twitch/redirect

**Callback Url**
https://[domain]/auth/twitch/callback

**ENV File Params**

    TWITCH_CLIENT_ID=
    TWITCH_CLIENT_SECRET=
    TWITCH_REDIRECT_URI=
    TWTICH_REDIRECT_BACK_TO_SITE=

***
### Youtube

Be aware, youtube does not have the user's email address back..

**Route To OAuth**
https://[domain]/auth/youtube/redirect

**Callback Url**
https://[domain]/auth/youtube/callback

**ENV File Params**

    YOUTUBE_CLIENT_ID=
    YOUTUBE_CLIENT_SECRET=
    YOUTUBE_REDIRECT_URI=
    YOUTUBE_REDIRECT_BACK_TO_SITE=
***
### Stripe
The Stripe is NOT an OAuth for logging in, but connecting an account to Stripe Connect. This also requires that the user be logged.

**Route To OAuth**
https://[domain]/auth/stripe/redirect

**Callback Url**
https://[domain]/auth/stripe/callback

**ENV File Params**

    STRIPE_CLIENT_ID=
    STRIPE_CLIENT_KEY=
    STRIPE_CLIENT_SECRET=
    STRIPE_REDIRECT_URI=
    STRIPE_REDIRECT_BACK_TO_SITE=

***
### Google

**Credentials**
To obtain Google's credentials:

1. Head over to Google Developers Console at [https://console.developers.google.com/](https://console.developers.google.com/)
2.Click on the Credentials link
3. Click on the 'Credentials' and select the 'OAuth Client ID'
4. During the creation process, the application type is a Web Application and the Authorized redirect URIs enter your Google Callback URL.
5. After creation, you will recieve your client id and secret.
6. Make sure in the Google OAuth Scope, you are enabling access to the users name and email.

**Route To OAuth**
https://[domain]/auth/google/redirect

**Callback Url**
https://[domain]/auth/google/callback

**ENV File Params**

    GOOGLE_CLIENT_ID=
    GOOGLE_CLIENT_SECRET=
    GOOGLE_REDIRECT_URI=
    GOOGLE_REDIRECT_BACK_TO_SITE=

***
### Microsoft

**Route To OAuth**
https://[domain]/auth/youtube/redirect

**Callback Url**
https://[domain]/auth/youtube/callback

**ENV File Params**

    MICROSOFT_CLIENT_ID=
    MICROSOFT_CLIENT_SECRET=
    MICROSOFT_REDIRECT_URI=
    MICROSOFT_REDIRECT_BACK_TO_SITE=

***
### Microsoft Teams

**Route To OAuth**
https://[domain]/auth/teams/redirect

**Callback Url**
https://[domain]/auth/teams/callback

**ENV File Params**

    TEAMSERVICE_CLIENT_ID=
    TEAMSERVICE_CLIENT_SECRET=
    TEAMSERVICE_REDIRECT_URI=
    TEAMSERVICE_REDIRECT_BACK_TO_SITE=


