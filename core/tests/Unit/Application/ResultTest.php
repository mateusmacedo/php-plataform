<?php

declare(strict_types=1);

namespace Tests\Unit\Application;

use Core\Application\{ApplicationException, Result};
use Exception;
use Tests\BaseTestCase;

final class ResultTest extends BaseTestCase
{
    protected Result $sut;

    public function testSuccessWithValue(): void
    {
        $this->sut = Result::success('success');
        $this->assertInstanceOf(Result::class, $this->sut);
        $this->assertTrue($this->sut->isSuccess());
        $this->assertEquals('success', $this->sut->value);
    }

    public function testSuccessWithoutValue(): void
    {
        $this->sut = Result::success(null);
        $this->assertInstanceOf(Result::class, $this->sut);
        $this->assertTrue($this->sut->isSuccess());
        $this->assertNull($this->sut->value);
    }

    public function testFailure(): void
    {
        $this->sut = Result::failure(new ApplicationException('failure'));
        $this->assertInstanceOf(Result::class, $this->sut);
        $this->assertFalse($this->sut->isSuccess());
        $this->assertNull($this->sut->value);
        $this->assertInstanceOf(ApplicationException::class, $this->sut->error);
    }

    public function testMapToWithCorrectParams()
    {
        $this->sut = Result::success('success');

        $result = $this->sut
            ->mapTo(fn ($value) => $value . ' mapped')
            ->mapTo(fn ($value) => $value . ' mappedAgain');
        $this->assertInstanceOf(Result::class, $result);
        $this->assertTrue($result->isSuccess());
        $this->assertEquals('success mapped mappedAgain', $result->value);
    }

    public function testMapToWithIncorrectParams()
    {
        $this->sut = Result::success('success');

        $result = $this->sut
            ->mapTo(fn ($value) => $value . ' mapped')
            ->mapTo(fn ($value) => throw new Exception('error'))
            ->mapTo(fn ($value) => $value . ' mappedAgain');
        $this->assertInstanceOf(Result::class, $result);
        $this->assertFalse($result->isSuccess());
        $this->assertNull($result->value);
        $this->assertInstanceOf(Exception::class, $result->error);
        $this->assertEquals('error', $result->error->getMessage());
    }
}
