<?php


namespace App\Invirtu;


abstract class InvirtuResource
{
    /**
     * @var ThinkificClient
     */
    protected $client;

    /**
     * IntercomResource constructor.
     *
     * @param ThinkificClient $client
     */
    public function __construct(InvirtuClient $client)
    {
        $this->client = $client;
    }
}