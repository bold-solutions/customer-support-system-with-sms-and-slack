<?php
namespace App\Service;

use GuzzleHttp\Client;

class SlackApiClient
{
    /** @var Client $client */
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function getConversationReplies(string $threadParentId)
    {
        $response = $this->client->get('conversations.replies', [
            'query' => [
                'channel' => $this->client->getConfig()['query']['channel'],
                'ts' => $threadParentId,
            ]
        ]);

        $body = json_decode($response->getBody()->getContents(), true);

        return $body['messages'];
    }
}