<?php


namespace App\Invirtu;

use Http\Client\Exception;
use stdClass;

class InvirtuAuth extends InvirtuResource
{
    

    /**
     * Register a user to the main site. This will not associate them when an organizer account.
     *
     * @see    https://developers.bingewave.com/docs/auth#register
     * 
     * @param  array $data Data that is passed to the endpoint. Check the documentation on the data that can be passed.
     * @param  array $query that will be passed as query parameters.
     * 
     * @return stdClass
     * @throws Exception
     */
    public function register(array $data, array $query = array())
    {

        return $this->client->post('/auth/register', $data, $query);
    }

    /**
     * Logs the user into the main site and returns a JWT. This will not associate them when an organizer account.
     *
     * @see    https://developers.bingewave.com/docs/auth#login
     * 
     * @param  array $data Data that is passed to the endpoint. Check the documentation on the data that can be passed.
     * @param  array $query that will be passed as query parameters.
     * 
     * @return stdClass
     * @throws Exception
     */
    public function login(array $data, array $query = array())
    {

        return $this->client->post('/auth/login', $data, $query);
    }

    /**
     * Register a user's account with an organizers account. The returned JWT for the user will only have access the associated organizer resources, and the route requires an organizers token.
     *
     * @see    https://developers.bingewave.com/docs/auth#registertoorganizer
     * 
     * @param  array $data Data that is passed to the endpoint. Check the documentation on the data that can be passed.
     * @param  array $query that will be passed as query parameters.
     * 
     * @return stdClass
     * @throws Exception
     */
    public function registerToOrganizer(array $data, array $query = array())
    {

        return $this->client->post('/auth/registerToOrganizer', $data, $query);
    }

    /**
     * Logs a user in and the account will be associated an organizers account. The returned JWT for the user will only have access the associated organizer resources, and the route requires an organizers token.
     *
     * @see    https://developers.bingewave.com/docs/auth#logintoorganizer
     * 
     * @param  array $data Data that is passed to the endpoint. Check the documentation on the data that can be passed.
     * @param  array $query that will be passed as query parameters.
     * 
     * @return stdClass
     * @throws Exception
     */
    public function loginToOrganizer(array $data, array $query = array())
    {

        return $this->client->post('/auth/loginToOrganizer', $data, $query);
    }

    /**
     * If a user does not have an account, this function will create one. If they have an account is will return the data and JWT for the account.
     * The returned JWT for the user will only have access the associated organizer resources, and the route requires an organizers token.
     *
     * @see    https://developers.bingewave.com/docs/auth#synctoorganizer
     * 
     * @param  array $data Data that is passed to the endpoint. Check the documentation on the data that can be passed.
     * @param  array $query that will be passed as query parameters.
     * 
     * @return stdClass
     * @throws Exception
     */
    public function syncToOrganizer(array $data, array $query = array())
    {

        return $this->client->post('/auth/syncToOrganizer', $data, $query);
    }

    /**
     * Checks to see if an organizers token is valid. No information is passed and the token that is checked is the one passed in the headers.
     *
     * @see    https://developers.bingewave.com/docs/auth#validateorganizertoken
     * 
     * 
     * @return stdClass
     * @throws Exception
     */
    public function validateOrganizerToken()
    {
        return $this->client->post('/auth/validateOrganizerToken', [], []);
    }

    /**
     * Checks to see if an account's token is valid. No information is passed and the token that is checked is the one passed in the headers.
     *
     * @see    https://developers.bingewave.com/docs/auth#validateaccounttoken
     * 
     * 
     * @return stdClass
     * @throws Exception
     */
    public function validateAccountToken()
    {
        return $this->client->post('/auth/validateOrganizerToken', [], []);
    }

    /**
     * Invalidates an organizer token so that it is no longer usable. No information is passed and the token that is invalidated is the one passed in the headers.
     *
     * @see    https://developers.bingewave.com/docs/auth#invalidateorganizertoken
     * 
     * 
     * @return stdClass
     * @throws Exception
     */
    public function invValidateOrganizerToken()
    {
        return $this->client->post('/auth/invalidateOrganizerToken', [], []);
    }


    /**
     * Invalidates an account token so that it is no longer usable. No information is passed and the token that is invalidated is the one passed in the headers.
     *
     * @see    https://developers.bingewave.com/docs/auth#invalidateaccounttoken
     * 
     * 
     * @return stdClass
     * @throws Exception
     */
    public function invValidateAccountToken()
    {
        return $this->client->post('/auth/invalidateAccountToken', [], []);
    }

   
}
