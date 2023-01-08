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
     * Update a restream.
     *
     * @see    https://developers.bingewave.com/docs/eventrestreams#restreamupdate
     * @param  string $id
     * @return stdClass
     * @throws Exception
     */
    public function updateRestream(string $event_id, string $stream_id, array $data, array $query = [])
    {
        return $this->client->put('/events/' . $event_id .'/updateRestream/' . $stream_id, $data, $query);
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
        return $this->client->delete('/events/' . $event_id .'/removeRestream/' . $stream_id , $data, $query);
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

    /**
     * Sets a preference options to the live event.
     *
     * @see    https://developers.bingewave.com/docs/accounts#setpreference
     * 
     * @param  string $value The value to search by e.g. An email address

     * @return stdClass
     * @throws Exception
     */
    public function setPreference(string $event_id, array $data, array $query = [])
    {
        return $this->client->post('/events/'. $event_id .'/setPreference', $data, $query);
    }

    /**
     * Removes a preference option from the live event.
     * 
     * @see    https://developers.bingewave.com/docs/accounts#removepreference
     * 
     * @param  string $value The value to search by e.g. An email address

     * @return stdClass
     * @throws Exception
     */
    public function removePreference(string $event_id, string $key, array $data, array $query = [])
    {
        return $this->client->delete('/events/'. $event_id .'/removePreference/' . $key, $data, $query);
    }


    /**
     * List the widgets that are currently associated with a live event.
     * 
     * @see    https://developers.bingewave.com/docs/eventwidgets#listwidget
     * 
     * @param  string $value The value to search by e.g. An email address

     * @return stdClass
     * @throws Exception
     */
    public function listWidgets(string $event_id, array $query = [])
    {
        return $this->client->get('/events/'. $event_id .'/getWidgets', $query);
    }

    /**
     * Add a widget to the live event and set which user roles will have access to the widget.
     * 
     * @see    https://developers.bingewave.com/docs/eventwidgets#addwidget
     * 
     * @param  string $value The value to search by e.g. An email address

     * @return stdClass
     * @throws Exception
     */
    public function addWidgets(string $event_id, array $data = [], array $query = [])
    {
        return $this->client->post('/events/'. $event_id .'/addWidget', $data, $query);
    }

    /**
     * Update the settings for the current widget associated with the live event.
     * 
     * @see    https://developers.bingewave.com/docs/eventwidgets#updatewidget
     * 
     * @param  string $value The value to search by e.g. An email address

     * @return stdClass
     * @throws Exception
     */
    public function updateWidgets(string $event_id, string $widget_id, array $data = [], array $query = [])
    {
        return $this->client->put('/events/'. $event_id .'/updateWidget/' . $widget_id, $data, $query);
    }

    /**
     * Removes the widget from the live event, and this will cause the widget to be removed from the screen.
     * 
     * @see    https://developers.bingewave.com/docs/eventwidgets#removewidget
     * 
     * @param  string $value The value to search by e.g. An email address

     * @return stdClass
     * @throws Exception
     */
    public function removeWidgets(string $event_id, array $data = [], array $query = [])
    {
        return $this->client->delete('/events/'. $event_id .'/updateWidget', $data, $query);
    }

    /**
     * Each position that a widget is placed in has options that can be used to configure how the elements behave inside that space. Set the options below.
     * 
     * @see    https://developers.bingewave.com/docs/eventwidgets#setoptions
     * 
     * @param  string $value The value to search by e.g. An email address

     * @return stdClass
     * @throws Exception
     */
    public function setWidgetOptions(string $event_id, string $widget_id, array $data = [], array $query = [])
    {
        return $this->client->post('/events/'. $event_id .'/setWidgetPositioningOptions/' . $widget_id, $data, $query);
    }

    /**
     * Return a list of configured options for the current live event.
     * 
     * @see    https://developers.bingewave.com/docs/eventwidgets#getoptions
     * 
     * @param  string $value The value to search by e.g. An email address

     * @return stdClass
     * @throws Exception
     */
    public function getWidgetPositioningOptions(string $event_id, array $query = [])
    {
        return $this->client->get('/events/'. $event_id .'/getWidgetPositioningOptions',  $query);
    }

    /**
     * Set an environment variable for the widget that has been associated with the live event.
     * 
     * @see    https://developers.bingewave.com/docs/eventwidgets#setenv
     * 
     * @param  string $value The value to search by e.g. An email address

     * @return stdClass
     * @throws Exception
     */
    public function setWidgetEnvironmentVariable(string $event_id, string $widget_id, array $data = [], array $query = [])
    {
        return $this->client->post('/events/'. $event_id .'/widget/' . $widget_id . '/setEnvironmentVariable/', $data, $query);
    }

    /**
     * Remove the environment variable for the widget that is associated with a live event.
     * 
     * @see    https://developers.bingewave.com/docs/eventwidgets#removeenv
     * 
     * @param  string $value The value to search by e.g. An email address

     * @return stdClass
     * @throws Exception
     */
    public function removeWidgetEnvironmentVariable(string $event_id, string $widget_id, string $key, array $data = [], array $query = [])
    {
        return $this->client->delete('/events/'. $event_id .'/widget/' . $widget_id . '/removeEnvironmentVariable/' . $key, $data, $query);
    }

    /**
     * Send generic content, ie HTML, that can be displayed on-screen to a user.
     * 
     * @see    https://developers.bingewave.com/docs/onscreen#content
     * 
     * @param  string $value The value to search by e.g. An email address

     * @return stdClass
     * @throws Exception
     */
    public function sendOnscreenContent(string $event_id, array $data, array $query = [])
    {
        return $this->client->post('/events/'. $event_id .'/sendOnscreenContent',  $data, $query);
    }

    /**
     * Display a text message over the video (live or pre-recorded) during an event.
     * 
     * @see    https://developers.bingewave.com/docs/onscreen#message
     * 
     * @param  string $value The value to search by e.g. An email address

     * @return stdClass
     * @throws Exception
     */
    public function sendOnscreenMessage(string $event_id, array $data, array $query = [])
    {
        return $this->client->post('/events/'. $event_id .'/sendOnscreenMessage',  $data, $query);
    }

    /**
     * Send a poll on-screen that users can take as a video is playing. Requires a poll to be created first.
     * 
     * @see    https://developers.bingewave.com/docs/onscreen#poll
     * 
     * @param  string $value The value to search by e.g. An email address

     * @return stdClass
     * @throws Exception
     */
    public function sendOnscreenPoll(string $event_id, array $data, array $query = [])
    {
        return $this->client->post('/events/'. $event_id .'/sendOnscreenPoll',  $data, $query);
    }

     /**
     * Removes an overlay that is currently being displayed on-screen.
     * 
     * @see    https://developers.bingewave.com/docs/onscreen#closeoverlay
     * 
     * @param  string $value The value to search by e.g. An email address

     * @return stdClass
     * @throws Exception
     */
    public function closeOverlay(string $event_id, array $data, array $query = [])
    {
        return $this->client->post('/events/'. $event_id .'/closeOverlay',  $data, $query);
    }


    /**
     * Add a webhook to the live event.
     *
     * @see    https://developers.bingewave.com/docs/eventrestreams#restreamadd
     * @param  string $id
     * @return stdClass
     * @throws Exception
     */
    public function addWebhook(string $event_id, array $data, array $query = [])
    {
        return $this->client->post('/events/' . $event_id .'/webhooks' , $data, $query);
    }

    /**
     * Update a restream.
     *
     * @see    https://developers.bingewave.com/docs/eventwebhooks#update
     * @param  string $id
     * @return stdClass
     * @throws Exception
     */
    public function updateWebhook(string $event_id, string $webhook_id, array $data, array $query = [])
    {
        return $this->client->put('/events/' . $event_id .'/webhooks/' . $webhook_id, $data, $query);
    }

    /**
     * Remove a webhook associated with a live event.
     *
     * @see    https://developers.bingewave.com/docs/eventwebhooks#delete
     * @param  string $id
     * @return stdClass
     * @throws Exception
     */
    public function removeWebhook(string $event_id, string $webhook_id, array $data, array $query = [])
    {
        return $this->client->delete('/events/' . $event_id .'/webhooks/' . $webhook_id , $data, $query);
    }

    /**
     * Get all the webhooks associated with a live event.
     *
     * @see    https://developers.bingewave.com/docs/eventwebhooks#list
     * @param  string $id
     * @return stdClass
     * @throws Exception
     */
    public function getWebhooks(string $event_id, array $query = [])
    {
        return $this->client->get('/events/' . $event_id .'/webhooks', $query);
    }

    /**
     * Get the information on a single webhook.
     *
     * @see    https://developers.bingewave.com/docs/eventwebhooks#view
     * @param  string $id
     * @return stdClass
     * @throws Exception
     */
    public function getSingleWebhook(string $event_id, string $webhook_id, array $query = [])
    {
        return $this->client->get('/events/' . $event_id .'/webhooks/' . $webhook_id, $query);
    }





    
}
