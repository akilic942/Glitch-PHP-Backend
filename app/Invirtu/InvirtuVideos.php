<?php


namespace App\Invirtu;

use Http\Client\Exception;
use stdClass;

class InvirtuVideos extends InvirtuResource
{

    /**
     * Get a list of video content.
     *
     * @see    https://developers.bingewave.com/docs/videos#list
     * @param  string $id
     * @return stdClass
     * @throws Exception
     */
    public function list(array $query = [])
    {
        return $this->client->get('/videos', $query);
    }

    /**
     * Create a video/pre-recordend content
     *
     * @see    https://developers.bingewave.com/docs/videos#create
     * @param  string $id
     * @return stdClass
     * @throws Exception
     */
    public function create(array $data, array $query = [])
    {
        return $this->client->post('/videos', $data, $query);
    }

    /**
     * Update a video file.
     *
     * @see    https://developers.bingewave.com/docs/videos#update
     * @param  string $id
     * @return stdClass
     * @throws Exception
     */
    public function update(string $video_id, array $data, array $query = [])
    {
        return $this->client->put('/videos/' . $video_id, $data, $query);
    }

    /**
     * Retrieve a single video file.
     *
     * @see    https://developers.bingewave.com/docs/videos#view
     * @param  string $id
     * @return stdClass
     * @throws Exception
     */
    public function get(string $video_id, array $query = [])
    {
        return $this->client->get('/videos/' . $video_id, $query);
    }

    /**
     * Deletes a video file
     *
     * @see    https://developers.bingewave.com/docs/videos#delete
     * @param  string $id
     * @return stdClass
     * @throws Exception
     */
    public function delete(string $video_id, array $data, array $query = [])
    {
        return $this->client->delete('/videos/' . $video_id, $data, $query);
    }
}
