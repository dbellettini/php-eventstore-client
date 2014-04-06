<?php

namespace DB\EventStoreClient\Tests\Model;

use DB\EventStoreClient\Model\EventReference;
use DB\EventStoreClient\Model\StreamReference;

class EventReferenceTest extends \PHPUnit_Framework_TestCase
{
    public function testGettersReturnProperValue()
    {
        $streamName = 'streamname';
        $streamVersion = 10;

        $reference = EventReference::fromStreamReferenceAndVersion(StreamReference::fromName($streamName), $streamVersion);

        $this->assertSame($streamName, $reference->getStreamReference()->getStreamName());
        $this->assertSame($streamVersion, $reference->getStreamVersion());
    }

    public function testFromStreamNameAndVersionFactoryMethodWorksProperly()
    {
        $streamName = 'streamname';
        $streamVersion = 10;

        $reference = EventReference::fromStreamNameAndVersion($streamName, $streamVersion);

        $this->assertSame($streamName, $reference->getStreamReference()->getStreamName());
        $this->assertSame($streamVersion, $reference->getStreamVersion());
    }
}
