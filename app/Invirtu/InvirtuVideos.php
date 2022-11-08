<?php


namespace App\Invirtu;

use Http\Client\Exception;
use stdClass;

class InvirtuVideos extends InvirtuResource
{

    /**
     * Gets a content
     *
     * @see    https://developers.thinkific.com/api/api-documentation/
     * @param  string $id
     * @return stdClass
     * @throws Exception
     */
    public function get($id)
    {
        return $this->client->get('contents/'.$id);
    }
}
