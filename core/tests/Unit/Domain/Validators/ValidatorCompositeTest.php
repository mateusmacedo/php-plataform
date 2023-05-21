<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Validators;

use Core\Domain\Validators\ValidatorComposite;
use Tests\BaseTestCase;

final class ValidatorCompositeTest extends BaseTestCase
{
    private ValidatorComposite $sut;

    protected function createValidator(bool $isvalid, array $messages, string $key): ValidatorStub
    {
        $closure = function ($value) use ($isvalid) {
            return $isvalid;
        };
        return new ValidatorStub($closure, $messages, $key);
    }

    protected function createValidatorComposite(?string $key = null, ?array $validators = null): ValidatorComposite
    {
        $stub = new ValidatorComposite($key);
        foreach ($validators as $validator) {
            $stub->addValidator($validator);
        }
        return $stub;
    }

    public function testShouldCreateValidatorCompositeWithKey(): void
    {
        $this->sut = $this->createValidatorComposite('ValidatorCompositeSut');
        $this->assertEquals('ValidatorCompositeSut', $this->sut->getKey());
    }

    public function testShouldCreateValidatorCompositeWithoutKey(): void
    {
        $this->sut = $this->createValidatorComposite();
        $this->assertEquals($this->sut->getKey(), $this->sut->getKey());
    }

    public function testShouldAddValidator(): void
    {
        $validatorOne = $this->createValidator(true, ['ValidatorOne is invalid'], 'ValidatorOne');
        $validatorTwo = $this->createValidator(false, ['ValidatorTwo is invalid'], 'ValidatorTwo');
        $validatorThree = $this->createValidator(false, ['ValidatorThree is invalid'], 'ValidatorThree');
        $validatorFour = $this->createValidator(true, ['ValidatorFour is invalid'], 'ValidatorFour');

        $validatorOneTwo = $this->createValidatorComposite('ValidatorOneTwo', [$validatorOne, $validatorTwo]);

        $validatorThreeFour = $this->createValidatorComposite('ValidatorThreeFour', [$validatorThree, $validatorFour]);

        $this->sut = $this->createValidatorComposite('ValidatorCompositeSut', [$validatorOneTwo, $validatorThreeFour]);

        $this->assertCount(2, $this->sut);
    }

    public function testShouldThrowExceptionWhenAddValidatorAlreadyExists(): void
    {
        $validatorOne = $this->createValidator(true, ['ValidatorOne is invalid'], 'ValidatorOne');
        $validatorTwo = $this->createValidator(true, ['ValidatorTwo is invalid'], 'ValidatorTwo');
        $validatorThree = $this->createValidator(false, ['ValidatorThree is invalid'], 'ValidatorThree');

        $this->sut = $this->createValidatorComposite(
            'ValidatorCompositeSut',
            [$validatorOne, $validatorTwo, $validatorThree]
        );

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Validator ValidatorOne already exists');
        $this->sut->addValidator($validatorOne);
    }

    public function testShouldRemoveValidator(): void
    {
        $validatorOne = $this->createValidator(true, ['ValidatorOne is invalid'], 'ValidatorOne');
        $validatorTwo = $this->createValidator(true, ['ValidatorTwo is invalid'], 'ValidatorTwo');
        $validatorThree = $this->createValidator(false, ['ValidatorThree is invalid'], 'ValidatorThree');

        $this->sut = $this->createValidatorComposite(
            'ValidatorCompositeSut',
            [$validatorOne, $validatorTwo, $validatorThree]
        );

        $this->assertCount(3, $this->sut);
        $this->sut->removeValidator($validatorOne);
        $this->assertCount(2, $this->sut);
    }

    public function testShouldThrowExceptionWhenRemoveValidatorDoesNotExists(): void
    {
        $validatorOne = $this->createValidator(true, ['ValidatorOne is invalid'], 'ValidatorOne');
        $validatorTwo = $this->createValidator(true, ['ValidatorTwo is invalid'], 'ValidatorTwo');
        $validatorThree = $this->createValidator(false, ['ValidatorThree is invalid'], 'ValidatorThree');
        $validatorFour = $this->createValidator(true, ['ValidatorFour is invalid'], 'ValidatorFour');

        $this->sut = $this->createValidatorComposite(
            'ValidatorCompositeSut',
            [$validatorOne, $validatorTwo, $validatorThree]
        );

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Validator ValidatorFour not found');
        $this->sut->removeValidator($validatorFour);
    }

    public function testShouldValidateWithoutErrors(): void
    {
        $validatorOne = $this->createValidator(true, ['ValidatorOne is invalid'], 'ValidatorOne');
        $validatorTwo = $this->createValidator(true, ['ValidatorTwo is invalid'], 'ValidatorTwo');
        $validatorThree = $this->createValidator(true, ['ValidatorThree is invalid'], 'ValidatorThree');
        $validatorFour = $this->createValidator(true, ['ValidatorFour is invalid'], 'ValidatorFour');

        $validatorOneTwo = $this->createValidatorComposite('ValidatorOneTwo', [$validatorOne, $validatorTwo]);

        $validatorThreeFour = $this->createValidatorComposite('ValidatorThreeFour', [$validatorThree, $validatorFour]);

        $this->sut = $this->createValidatorComposite('ValidatorCompositeSut', [$validatorOneTwo, $validatorThreeFour]);

        $this->assertTrue($this->sut->isValid('value'));
    }

    public function testShouldValidateWithErrors(): void
    {
        $validatorOne = $this->createValidator(false, ['ValidatorOne is invalid'], 'ValidatorOne');
        $validatorTwo = $this->createValidator(true, ['ValidatorTwo is invalid'], 'ValidatorTwo');
        $validatorThree = $this->createValidator(false, ['ValidatorThree is invalid'], 'ValidatorThree');
        $validatorFour = $this->createValidator(true, ['ValidatorFour is invalid'], 'ValidatorFour');

        $validatorOneTwo = $this->createValidatorComposite('ValidatorOneTwo', [$validatorOne, $validatorTwo]);

        $validatorThreeFour = $this->createValidatorComposite('ValidatorThreeFour', [$validatorThree, $validatorFour]);

        $this->sut = $this->createValidatorComposite('ValidatorCompositeSut', [$validatorOneTwo, $validatorThreeFour]);

        $this->assertFalse($this->sut->isValid('valueOne'));
        $this->assertEquals(
            [
                'ValidatorOneTwo' => [
                    'ValidatorOne' => ['ValidatorOne is invalid'],
                ],
                'ValidatorThreeFour' => [
                    'ValidatorThree' => ['ValidatorThree is invalid'],
                ]
            ],
            $this->sut->getMessages()
        );
    }

    public function testShouldValidateCollectionValue(): void
    {
        $validatorOne = $this->createValidator(true, ['ValidatorOne is invalid'], 'ValidatorOne');
        $validatorTwo = $this->createValidator(true, ['ValidatorTwo is invalid'], 'ValidatorTwo');
        $validatorThree = $this->createValidator(true, ['ValidatorThree is invalid'], 'ValidatorThree');
        $validatorFour = $this->createValidator(true, ['ValidatorFour is invalid'], 'ValidatorFour');

        $validatorOneTwo = $this->createValidatorComposite('ValidatorOneTwo', [$validatorOne, $validatorTwo]);

        $validatorThreeFour = $this->createValidatorComposite('ValidatorThreeFour', [$validatorThree, $validatorFour]);

        $this->sut = $this->createValidatorComposite('ValidatorCompositeSut', [$validatorOneTwo, $validatorThreeFour]);

        $this->assertTrue($this->sut->isValid(['valueOne', 'valueTwo']));
    }

    public function testShouldValidateCollectionValueWithErrors(): void
    {
        $validatorOne = $this->createValidator(false, ['ValidatorOne is invalid'], 'ValidatorOne');
        $validatorTwo = $this->createValidator(true, ['ValidatorTwo is invalid'], 'ValidatorTwo');
        $validatorThree = $this->createValidator(false, ['ValidatorThree is invalid'], 'ValidatorThree');
        $validatorFour = $this->createValidator(true, ['ValidatorFour is invalid'], 'ValidatorFour');

        $validatorOneTwo = $this->createValidatorComposite('ValidatorOneTwo', [$validatorOne, $validatorTwo]);

        $validatorThreeFour = $this->createValidatorComposite('ValidatorThreeFour', [$validatorThree, $validatorFour]);

        $this->sut = $this->createValidatorComposite('ValidatorCompositeSut', [$validatorOneTwo, $validatorThreeFour]);

        $this->assertFalse($this->sut->isValid(['valueOne', 'valueTwo']));
        $this->assertEquals(
            [
                [
                    'ValidatorOneTwo' => [
                        'ValidatorOne' => ['ValidatorOne is invalid'],
                    ],
                    'ValidatorThreeFour' => [
                        'ValidatorThree' => ['ValidatorThree is invalid'],
                    ]
                ],
                [
                    'ValidatorOneTwo' => [
                        'ValidatorOne' => ['ValidatorOne is invalid'],
                    ],
                    'ValidatorThreeFour' => [
                        'ValidatorThree' => ['ValidatorThree is invalid'],
                    ]
                ]
            ],
            $this->sut->getMessages()
        );
    }
}
