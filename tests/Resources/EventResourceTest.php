<?php
namespace Tests\Resources;

use App\Http\Resources\EventResource;
use App\Models\Event;
use Tests\TestCase;

class EventResourceTest extends TestCase {

    public function testResource() {

        $event = Event::factory()->create();

        $resource = EventResource::make($event);

        $this->assertEquals($event->id, $resource->id);
        $this->assertEquals($event->title, $resource->title);
        $this->assertEquals($event->description, $resource->description);
        $this->assertEquals($event->start_date, $resource->start_date);
        $this->assertEquals((string) $event->created_at, $resource->created_at);



        
    }

}