<?php

declare(strict_types=1);

namespace Tests\Unit\Application;

use Exception;
use Core\Application\{ActionFactory, IActionFactory};
use stdClass;
use Tests\TestCase;
use Tests\Unit\Application\Stubs\ActionsEnumStub;

class ActionFactoryTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function testConstructorSuccessWithCorrectlyData()
    {
        $actionFactory = $this->instanceSuccess();
        $this->assertInstanceOf(IActionFactory::class, $actionFactory);
    }

    public function testConstructorErrorWithIncorrectlyData()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('an enum instance is expected for the action record.');
        new ActionFactory(stdClass::class);
    }

    public function testCheckExistsAction()
    {
        $actionFactory = $this->instanceSuccess();
        $this->assertEquals(true, $actionFactory->exists('stubed'));
        $this->assertEquals(false, $actionFactory->exists('NOT stubed'));
    }

    public function testCreateActionSuccessWithExistsAction()
    {
        $actionFactory = $this->instanceSuccess();
        $this->assertInstanceOf(stdClass::class, $actionFactory->create('stubed'));
    }

    public function testCreateActionErrorWithNotExistsAction()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('there is no NOT stubed action on the enum');
        $actionFactory = $this->instanceSuccess();
        $actionFactory->create('NOT stubed');
    }

    private function instanceSuccess()
    {
        return new ActionFactory(ActionsEnumStub::class);
    }
}
