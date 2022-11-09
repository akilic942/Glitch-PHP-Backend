<?php


namespace App\Invirtu;

use Http\Client\Exception;
use stdClass;

class InvirtuEvents extends InvirtuResource
{


    /**
     * List live events, requires an organizer ID.
     *
     * @see    https://developers.bingewave.com/docs/events#list
     * @param  string $id
     * @return stdClass
     * @throws Exception
     */
    public function list(array $query = [])
    {
        return $this->client->get('/events' , $query);
    }

    /**
     * Gets a event by its ID
     *
     * @see    https://developers.bingewave.com/docs/events#view
     * @param  string $id
     * @return stdClass
     * @throws Exception
     */
    public function get(string $event_id, array $query = [])
    {
        return $this->client->get('/events/' . $event_id , $query);
    }

    public function create(array $data = [])
    {

        return $this->client->post('/events', $data);
    }

    /**
     * Update a live event by its ID.
     *
     * @see    https://developers.bingewave.com/docs/events#update
     * @param  string $id
     * @return stdClass
     * @throws Exception
     */
    public function update(string $event_id, array $data, array $query = [])
    {
        return $this->client->put('/events/' . $event_id , $data, $query);
    }

    /**
     * Delete a live event. Delete is different from cancelling, which will send out a message.
     *
     * @see    https://developers.bingewave.com/docs/events#delete
     * @param  string $id
     * @return stdClass
     * @throws Exception
     */
    public function delete(string $event_id, array $data, $query = [])
    {
        return $this->client->delete('/events/' . $event_id , $data, $query);
    }


    /**
     * Retrieve a list of users that are associated with this live event. Will include all user roles.
     * This is different from online users, as these users do not have to active.
     *
     * @see    https://developers.bingewave.com/docs/status#participants
     * @param  string $id
     * @return stdClass
     * @throws Exception
     */
    public function getParticipants(string $event_id, array $query = [])
    {
        return $this->client->get('/events/' . $event_id .'/getParticipants' , $query);
    }

    /**
     * Retrieve a list of users that are currently online for the associated live event.
     * This is different from getParticipants, and the other method does not require users to be active.
     *
     * @see    https://developers.bingewave.com/docs/status#onlineusers
     * @param  string $id
     * @return stdClass
     * @throws Exception
     */
    public function getOnlineUsers(string $event_id, array $query = [])
    {
        return $this->client->get('/events/' . $event_id .'/getOnlineUsers' , $query);
    }

    /**
     * Retrieves a user's status based on the user's id. 
     * Will tell if the user is a moderator, panelist, participant and other status-related information.
     *
     * @see    https://developers.bingewave.com/docs/status#getstatus
     * @param  string $id
     * @return stdClass
     * @throws Exception
     */
    public function getUserStatus(string $event_id, string $account_id, array $query = [])
    {
        return $this->client->get('/events/' . $event_id .'/getUserStatus/' . $account_id , $query);
    }

    /**
     * Set the user with the role of moderator/host.
     * Please keep in mind, users can have multiple roles at the same time, and this will only affect the current role.
     *
     * @see    https://developers.bingewave.com/docs/status#makemoderator
     * @param  string $id
     * @return stdClass
     * @throws Exception
     */
    public function makeModerator(string $event_id, array $data, array $query = [])
    {
        return $this->client->post('/events/' . $event_id .'/makeModerator' , $data, $query);
    }

    /**
     * Remove the user from the role of moderator/hosts.
     * Please keep in mind, users can have multiple roles at the same time, and this will only affect the current role.
     *
     * @see    https://developers.bingewave.com/docs/status#removemoderator
     * @param  string $id
     * @return stdClass
     * @throws Exception
     */
    public function removeModerator(string $event_id, array $data, array $query = [])
    {
        return $this->client->delete('/events/' . $event_id .'/removeModerator' , $data, $query);
    }

    /**
     * Set the user with the role of panelist.
     * Please keep in mind, users can have multiple roles at the same time, and this will only affect the current role.
     *
     * @see    https://developers.bingewave.com/docs/status#makepanelist
     * @param  string $id
     * @return stdClass
     * @throws Exception
     */
    public function makePanelist(string $event_id, array $data, array $query = [])
    {
        return $this->client->post('/events/' . $event_id .'/makePanelist' , $data, $query);
    }

    /**
     * Remove the user from the role of panelist.
     * Please keep in mind, users can have multiple roles at the same time, and this will only affect the current role.
     *
     * @see    https://developers.bingewave.com/docs/status#removepanelist
     * @param  string $id
     * @return stdClass
     * @throws Exception
     */
    public function removePanelist(string $event_id, array $data, array $query = [])
    {
        return $this->client->delete('/events/' . $event_id .'/removePanelist' , $data, $query);
    }

     /**
     * Set the user with the role of participant.
     * Please keep in mind, users can have multiple roles at the same time, and this will only affect the current role.
     *
     * @see    https://developers.bingewave.com/docs/status#makeparticipant
     * @param  string $id
     * @return stdClass
     * @throws Exception
     */
    public function makeParticipant(string $event_id, array $data, array $query = [])
    {
        return $this->client->post('/events/' . $event_id .'/makeParticipant' , $data, $query);
    }

    /**
     * Remove the user from the role of participant.
     * Please keep in mind, users can have multiple roles at the same time, and this will only affect the current role.
     *
     * @see    https://developers.bingewave.com/docs/status#removeparticipant
     * @param  string $id
     * @return stdClass
     * @throws Exception
     */
    public function removeParticipant(string $event_id, array $data, array $query = [])
    {
        return $this->client->delete('/events/' . $event_id .'/removeParticipant' , $data, $query);
    }

    /**
     * Set set user as block, which will prevent them taking any actions for the current live event.
     * Please keep in mind, users can have multiple roles at the same time, and this will only affect the current role.
     *
     * @see    https://developers.bingewave.com/docs/status#blockuser
     * @param  string $id
     * @return stdClass
     * @throws Exception
     */
    public function blockAccount(string $event_id, array $data, array $query = [])
    {
        return $this->client->post('/events/' . $event_id .'/blockAccount' , $data, $query);
    }

    /**
     * Unblocks the user from the current live event.
     * Please keep in mind, users can have multiple roles at the same time, and this will only affect the current role.
     *
     * @see    https://developers.bingewave.com/docs/status#unblockuser
     * @param  string $id
     * @return stdClass
     * @throws Exception
     */
    public function unblockAccount(string $event_id, array $data, array $query = [])
    {
        return $this->client->delete('/events/' . $event_id .'/unblockAccount' , $data, $query);
    }

    /**
     * Adds a RTMP destination to restream a too when a live event is live streaming or broadcasting.
     *
     * @see    https://developers.bingewave.com/docs/eventrestreams#restreamadd
     * @param  string $id
     * @return stdClass
     * @throws Exception
     */
    public function addRestream(string $event_id, array $data, array $query = [])
    {
        return $this->client->post('/events/' . $event_id .'/addRestream' , $data, $query);
    }

    /**
     * Removes a stream that has been added.
     *
     * @see    https://developers.bingewave.com/docs/eventrestreams#restreamremove
     * @param  string $id
     * @return stdClass
     * @throws Exception
     */
    public function removeRestream(string $event_id, string $stream_id, array $data, array $query = [])
    {
        return $this->client->post('/events/' . $event_id .'/removeRestream/' . $stream_id , $data, $query);
    }

    /**
     * Get a list of restreams that will restream a livestream or broadcast to other endpoints.
     *
     * @see    https://developers.bingewave.com/docs/eventrestreams#restreamlist
     * @param  string $id
     * @return stdClass
     * @throws Exception
     */
    public function getRestreams(string $event_id, array $query = [])
    {
        return $this->client->get('/events/' . $event_id .'/getRestreams', $query);
    }



    
}
