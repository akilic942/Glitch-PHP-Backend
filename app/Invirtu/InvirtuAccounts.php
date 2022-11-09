<?php

namespace App\Invirtu;

use Http\Client\Exception;
use stdClass;

class InvirtuAccounts extends InvirtuResource
{

    /**
     * Retrieve a list of user accounts that is associated with an organizer.
     *
     * @see    https://developers.bingewave.com/docs/accounts#list
     * @param  array $options
     * @return stdClass
     * @throws Exception
     */
    public function list(array $query = [])
    {
        return $this->client->get('/accounts', $query);
    }

    /**
     * View a users profile
     *
     * @see    https://developers.bingewave.com/docs/accounts#profile
     * @param  array $options
     * @return stdClass
     * @throws Exception
     */
    public function profile(string $user_id, array $query)
    {
        return $this->client->gets('/accounts/' . $user_id, $query);
    }

    /**
     * Updates a user account. A user can only update their own account and their auth token must be used.
     *
     * @see    https://developers.bingewave.com/docs/accounts#update
     * @param  string $id
     * @return stdClass
     * @throws Exception
     */
    public function update(array $data, array $query = array())
    {

        return $this->client->put('/accounts', $data, $query);
    }

    /**
     * Gets the current user by their auth token.
     *
     * @see    https://developers.bingewave.com/docs/accounts#me
     * @param string $id
     * @param array $options
     * @return stdClass
     */
    public function me(array $query = array())
    {
        return $this->client->get('/accounts/me', $query);
    }

    /**
     * If a user has RSVPed to a Live Event or has purchased tickets, they can retrieve them here.
     *
     * @see    https://developers.bingewave.com/docs/accounts#mytickets
     * @param string $id
     * @return stdClass
     */
    public function myTickets($id)
    {
        return $this->client->get('/accounts/mytickets');
    }

    /**
     * Sets a preference option that will be associated with the user. 
     * DO NOT store sensitive information about the user (ie: auth tokens) as they will be accessible to everyone.
     *
     * @see    https://developers.bingewave.com/docs/accounts#setpreference
     * 
     * @param  string $value The value to search by e.g. An email address

     * @return stdClass
     * @throws Exception
     */
    public function setPreference(string $account_id, array $data, array $query = [])
    {
        return $this->client->post('/accounts/'. $account_id .'/setPreference', $data, $query);
    }

    /**
     * Removes a preference option that will is associated with the user.
     * 
     * @see    https://developers.bingewave.com/docs/accounts#removepreference
     * 
     * @param  string $value The value to search by e.g. An email address

     * @return stdClass
     * @throws Exception
     */
    public function removePreference(string $account_id, string $key, array $data, array $query = [])
    {
        return $this->client->delete('/accounts/'. $account_id .'/removePreference/' . $key, $data, $query);
    }

    /**
     * Sets a secure preference option that will be associated with the user. 
     * This preferences stored here will not be made available to public and only accessible to the owner of the account with their auth token.
     *
     * @see    https://developers.bingewave.com/docs/accounts#setsecurepreference
     * 
     * @param  string $value The value to search by e.g. An email address

     * @return stdClass
     * @throws Exception
     */
    public function setSecurePreference(string $account_id, array $data, array $query = [])
    {
        return $this->client->post('/accounts/'. $account_id .'/setSecurePreference', $data, $query);
    }

    /**
     * Removes a preference option that will is associated with the user.
     * 
     * @see    https://developers.bingewave.com/docs/accounts#removepreference
     * 
     * @param  string $value The value to search by e.g. An email address

     * @return stdClass
     * @throws Exception
     */
    public function removeSecurePreference(string $account_id, string $key, array $data, array $query = [])
    {
        return $this->client->delete('/accounts/'. $account_id .'/removeSecurePreference/' . $key, $data, $query);
    }

    
}
