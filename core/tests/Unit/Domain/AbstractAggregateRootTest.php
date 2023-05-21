<?php

declare(strict_types=1);

namespace Tests\Unit\Domain;

use Core\Domain\AbstractAggregateRoot;
use Tests\BaseTestCase;
use Tests\Unit\Domain\Stubs\{AggregateRootStub, DomainEventStub};

final class AbstractAggregateRootTest extends BaseTestCase
{
    public AbstractAggregateRoot $sut;
    public string|null $id;
    public string|null $createdAt;
    public string|null $updatedAt;
    public string|null $deletedAt;

    public function setUp(): void
    {
        parent::setUp();
        $this->id = 'dummy-id';
        $this->createdAt = '2021-01-01 00:00:00';
        $this->updatedAt = '2021-01-01 00:00:01';
        $this->deletedAt = '2021-01-01 00:00:02';
    }

    public function testAggregateRootShouldInstaciateWithParams(): void
    {
        $this->sut = new AggregateRootStub(
            $this->id,
            $this->createdAt,
            $this->updatedAt,
            $this->deletedAt
        );
        $this->assertEquals($this->id, $this->sut->id);
        $this->assertEquals($this->createdAt, $this->sut->createdAt);
        $this->assertEquals($this->updatedAt, $this->sut->getUpdatedAt());
        $this->assertEquals($this->deletedAt, $this->sut->getDeletedAt());
        $this->assertTrue($this->sut->itsDeleted());
        $events = [];
        foreach ($this->sut->getEvents() as $event) {
            $events[] = $event;
        }
        $this->assertCount(0, $events);
    }

    public function testAggregateRootShouldInstaciateWithoutParams(): void
    {
        $this->sut = new AggregateRootStub();
        $this->assertNull($this->sut->id);
        $this->assertNull($this->sut->createdAt);
        $this->assertNull($this->sut->getUpdatedAt());
        $this->assertNull($this->sut->getDeletedAt());
        $this->assertFalse($this->sut->itsDeleted());
        $events = [];
        foreach ($this->sut->getEvents() as $event) {
            $events[] = $event;
        }
        $this->assertCount(0, $events);
    }

    public function testAggregateRootShouldRecordEvent(): void
    {
        $this->sut = new AggregateRootStub();
        $this->sut->record(new DomainEventStub(
            'dummy-event-type',
            'dummy-event-id',
            '2021-01-01 00:00:00',
            'dummy-aggregate-id'
        ));
        $events = [];
        foreach ($this->sut->getEvents() as $event) {
            $events[] = $event;
        }
        $this->assertCount(1, $events);
        $this->assertInstanceOf(DomainEventStub::class, $events[0]);
    }

    public function testAggregateRootShouldClearEvents(): void
    {
        $this->sut = new AggregateRootStub();
        $this->sut->record(new DomainEventStub(
            'dummy-event-type',
            'dummy-event-id',
            '2021-01-01 00:00:00',
            'dummy-aggregate-id'
        ));
        $this->sut->clearEvents();
        $events = [];
        foreach ($this->sut->getEvents() as $event) {
            $events[] = $event;
        }
        $this->assertCount(0, $events);
    }
}
