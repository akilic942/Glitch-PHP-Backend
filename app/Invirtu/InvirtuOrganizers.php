<?php


namespace App\Invirtu;

use Http\Client\Exception;
use stdClass;

class InvirtuOrganizers extends InvirtuResource
{
    /**
     * Retrieve a list of organizers. Requires an account JWT and will retrieve the organizers in relation to that JWT.
     *
     * @see    https://developers.bingewave.com/docs/organizers#list
     * 
     * @param  array $options that will be passed as query parameters.
     * 
     * @return stdClass
     * @throws Exception
     */
    public function list(array $query = [])
    {

        return $this->client->get('/organizers', $query);
    }

    /**
     * Creates a new organizer account. Only an account that is been verified is allowed to create organizer accounts.
     *
     * @see    https://developers.bingewave.com/docs/organizers#create
     * 
     * @param  array $data Data that is passed to the endpoint. Check the documentation on the data that can be passed.
     * @param  array $query that will be passed as query parameters.
     * 
     * @return stdClass
     * @throws Exception
     */
    public function create(array $data, array $query = [])
    {

        return $this->client->post('/organizers', $data, $query);
    }

    /**
     * Updates the information related to an organizer account.
     *
     * @see    https://developers.bingewave.com/docs/organizers#update
     * 
     * @param  string $organizer_id The id of the organizer account to update.
     * @param  array $data Data that is passed to the endpoint. Check the documentation on the data that can be passed.
     * @param  array $query that will be passed as query parameters.
     * 
     * @return stdClass
     * @throws Exception
     */
    public function update(string $organizer_id, array $data = [], array $query = [])
    {

        return $this->client->put('/organizers/' . $organizer_id, $data, $query);
    }

    /**
     * View a single organizer account.
     *
     * @see    https://developers.bingewave.com/docs/organizers#view
     * 
     * @param  string $organizer_id The id of the organizer account to retrieve.
     * @param  array $query that will be passed as query parameters.
     * 
     * @return stdClass
     * @throws Exception
     */
    public function view(string $organizer_id, array $query = [])
    {

        return $this->client->get('/organizers/' . $organizer_id, $query);
    }

    /**
     * Set a user to specific role. Be aware that users can have multiple roles.
     *
     * @see    https://developers.bingewave.com/docs/organizersmanage#setuser
     * 
     * @param  string $organizer_id The id of the organizer account that the user is associated with.
     * @param  array $data Data that is passed to the endpoint. Check the documentation on the data that can be passed.
     * @param  array $query that will be passed as query parameters.
     * 
     * @return stdClass
     * @throws Exception
     */
    public function setToRole(string $organizer_id, array $post = [],array $query = [])
    {

        return $this->client->post('/organizers/' . $organizer_id . '/setUserToRole', $post, $query);
    }

    /**
     * Removes a user from a specific role. Be aware that users can have multiple roles.
     *
     * @see    https://developers.bingewave.com/docs/organizersmanage#removeuser
     * 
     * @param  string $organizer_id The id of the organizer account that the user is associated with.
     * @param  array $data Data that is passed to the endpoint. Check the documentation on the data that can be passed.
     * @param  array $query that will be passed as query parameters.
     * 
     * @return stdClass
     * @throws Exception
     */
    public function removeFromRole(string $organizer_id, array $post = [],array $query = [])
    {

        return $this->client->post('/organizers/' . $organizer_id . '/removeUserFromRole', $post, $query);
    }


}
