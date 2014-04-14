<?php

namespace EventStore;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Message\Response;
use GuzzleHttp\Stream\Stream;

/**
 * Class Connection
 * @package EventStore
 */
class Connection implements ConnectionInterface
{
    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * @var array
     */
    private static $defaultOptions = [
        'base_url' => 'http://127.0.0.1:2113/'
    ];

    /**
     * Constructor
     *
     * @param ClientInterface $client
     */
    protected function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * {@inheritdoc}
     */
    public function appendToStream($stream, $expectedVersion, array $events)
    {
        $eventsArray = [];

        foreach ($events as $event) {
            $eventsArray[] = $event->toArray();
        }

        $this
           ->client
           ->post('/streams/'.$stream, [
                'body' => Stream::factory(json_encode($eventsArray)),
                'headers' => [
                    'Content-type'       => 'application/json',
                    'ES-ExpectedVersion' => $expectedVersion
                ]
           ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function readStreamEventsForward($stream, $start, $count, $resolveLinkTos)
    {
        $url = \sprintf('/streams/%s/%d/forward/%d', $stream, $start, $count);

        $response = $this->client
            ->get($url, [
                'headers' => [
                    'accept' => 'application/vnd.eventstore.atom+json'
                ],
            ])
        ;

        $slice = $this->transformResponse($response);

        return $slice;
    }

    /**
     * {@inheritdoc}
     */
    public function readStreamEventsBackward($stream, $start, $count, $resolveLinkTos)
    {
        // TODO: Implement readStreamEventsBackward() method.
    }

    /**
     * {@inheritdoc}
     */
    public function deleteStream($stream, $hardDelete = false)
    {
        $headers = [
            'Content-type' => 'application/json',
        ];

        if ($hardDelete) {
            $headers['ES-HardDelete'] = 'true';
        }

        $this
            ->client
            ->delete('/streams/'.$stream, [
                'headers' => $headers
            ])
        ;
    }

    /**
     * @param  array      $options
     * @return Connection
     */
    public static function create(array $options = [])
    {
        $options = array_merge(self::$defaultOptions, $options);

        if (!isset($options['client'])) {
            $options['client'] = new Client(['base_url' => $options['base_url']]);
        }

        return new self($options['client']);
    }

    private function transformResponse(Response $response)
    {
        $slice = new StreamEventsSlice(

        );

        return $slice;
    }
}
