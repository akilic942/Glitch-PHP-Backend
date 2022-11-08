<?php


namespace App\Invirtu;

use Http\Client\Exception;
use stdClass;

class InvirtuTemplates extends InvirtuResource
{

    /**
     * Retrieve a list of templates associated with an organizer account
     *
     * @see    https://developers.bingewave.com/docs/templates#list
     * 
     * @param  array $options that will be passed as query parameters.
     * 
     * @return stdClass
     * @throws Exception
     */
    public function list(array $query = [])
    {
        return $this->client->get('/templates', $query);
    }

    /**
     * Create a new template.
     *
     * @see    https://developers.bingewave.com/docs/templates#create
     * @param  string $id
     * @param  array $options
     * @return stdClass
     * @throws Exception
     */
    public function create(array $data, array $query = [])
    {
        return $this->client->post('/templates', $data, $query);
    }

    /**
     * Updates a current template.
     *
     * @see    https://developers.bingewave.com/docs/templates#update
     * @param  string $template_id The id of the template to update
     * @param  array $options
     * @return stdClass
     * @throws Exception
     */
    public function update(string $template_id, array $data, array $query = [])
    {

        return $this->client->post('/templates/' . $template_id, $data, $query);
    }

    /**
     * Retrieves a single review by it's review ID
     *
     * @see    https://developers.bingewave.com/docs/templates#view
     * @param  string $id
     * @return stdClass
     * @throws Exception
     */
    public function get(string $template_id, array $query = [])
    {
        return $this->client->get('/templates/'.$template_id, $query);
    }

    /**
     * Creates a review for a given course ID
     *
     * @see    https://developers.bingewave.com/docs/templates#view
     * 
     * @param  string $id
     * @param  array $options
     * @return stdClass
     * @throws Exception
     */
    public function delete(string $template_id, array $data, array $query = [])
    {

        return $this->client->post('/templates/' . $template_id, $data, $query);
    }

    /**
     * Retrieve the widgets associated with the current template.
     *
     * @see    https://developers.bingewave.com/docs/templatewidgets#listwidget
     * 
     * @param  string $template_id The id of the template to retrieve.
     * @param  array $options
     * @return stdClass
     * @throws Exception
     */
    public function getWidgets(string $template_id, array $query = [])
    {

        return $this->client->get('/templates/' . $template_id . '/getWidgets', $query);
    }

    /**
     * Add widget to the current template.
     *
     * @see    https://developers.bingewave.com/docs/templatewidgets#addwidget
     * 
     * @param  string $template_id The id of the template to retrieve.
     * @param  array $options
     * @return stdClass
     * @throws Exception
     */
    public function addWidgets(string $template_id, array $data, array $query = [])
    {

        return $this->client->post('/templates/' . $template_id . '/getWidgets', $data, $query);
    }

    /**
     * Update widget associated with the demplate.
     *
     * @see    https://developers.bingewave.com/docs/templatewidgets#updatewidget
     * 
     * @param  string $template_id The id of the template to retrieve.
     * @param  array $options
     * @return stdClass
     * @throws Exception
     */
    public function updateWidget(string $template_id, string $widget_id, array $data, array $query = [])
    {

        return $this->client->put('/templates/' . $template_id . '/updateWidget/' . $widget_id, $data, $query);
    }

    /**
     * Remove the widget from the template.
     *
     * @see    https://developers.bingewave.com/docs/templatewidgets#removewidget
     * 
     * @param  string $template_id The id of the template to retrieve.
     * @param  array $options
     * @return stdClass
     * @throws Exception
     */
    public function removeWidgets(string $template_id, array $data, array $query = [])
    {

        return $this->client->delete('/templates/' . $template_id . '/removeWidget', $data, $query);
    }

    /**
     * Each position that a widget is placed in has options that can be used to configure how the elements behave inside that space. Set the options below.
     *
     * @see    https://developers.bingewave.com/docs/templatewidgets#setoptions
     * 
     * @param  string $template_id The id of the template to retrieve.
     * @param  array $options
     * @return stdClass
     * @throws Exception
     */
    public function setOption(string $template_id, int $option, array $data, array $query = [])
    {

        return $this->client->post('/templates/' . $template_id . '/setWidgetPositioningOptions/' . $option, $data, $query);
    }

    /**
     * Return a list of configured options for the current template.

     *
     * @see    https://developers.bingewave.com/docs/templatewidgets#getoptions
     * 
     * @param  string $template_id The id of the template to retrieve.
     * @param  array $options
     * @return stdClass
     * @throws Exception
     */
    public function getOptions(string $template_id, array $query = [])
    {

        return $this->client->get_browser('/templates/' . $template_id . '/getWidgetPositioningOptions', $query);
    }
    
}
