<?php

namespace Rezzza\CommandBus\Tests\Units\Domain\Handler\MethodResolver;

use mageekguy\atoum;

class ClassNameAsMethodWithoutSuffix extends atoum\test
{
    public function test_it_should_resolve_the_method_name_from_command_name()
    {
        $this
            ->given(
                $sut = $this->newTestedInstance,
                $command = new \Rezzza\CommandBus\Tests\Fixtures\Command\SendWelcomeEmailCommand,
                $handler = new \mock\StdClass
            )
            ->when(
                $methodName = $sut->resolveMethodName($command, $handler)
            )
            ->then
                ->phpString($methodName)->isEqualTo('sendWelcomeEmail')
        ;
    }
}
