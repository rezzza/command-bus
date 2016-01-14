<?php

namespace Rezzza\CommandBus\Tests\Units\Infra\Provider\Direct;

use mageekguy\atoum;

class DirectBus extends atoum\test
{
    public function test_object_handler_should_be_executed_through_the_method_name_resolved()
    {
        $this
            ->given(
                $command = new \Rezzza\CommandBus\Tests\Fixtures\Command\SendWelcomeEmailCommand,
                $handler = new \mock\Rezzza\CommandBus\Tests\Fixtures\UserService,
                $this->calling($handler)->sendWelcomeEmail = true
            )
            ->and(
                $locator = new \mock\Rezzza\CommandBus\Domain\Handler\CommandHandlerLocatorInterface,
                $this->calling($locator)->getCommandHandler = $handler,
                $methodResolver = new \mock\Rezzza\CommandBus\Domain\Handler\HandlerMethodResolverInterface,
                $this->calling($methodResolver)->resolveMethodName = 'sendWelcomeEmail',
                $sut = $this->newTestedInstance($locator, $methodResolver)
            )
            ->when(
                $sut->handle($command)
            )
            ->then
                ->mock($handler)
                    ->call('sendWelcomeEmail')
                    ->withArguments($command)
                    ->once()
        ;
    }

    public function test_missing_resolved_method_name_on_handler_should_lead_to_exception()
    {
        $this
            ->given(
                $command = new \Rezzza\CommandBus\Tests\Fixtures\Command\SendWelcomeEmailCommand,
                $handler = new \mock\Rezzza\CommandBus\Tests\Fixtures\UserService
            )
            ->and(
                $locator = new \mock\Rezzza\CommandBus\Domain\Handler\CommandHandlerLocatorInterface,
                $this->calling($locator)->getCommandHandler = $handler,
                $methodResolver = new \mock\Rezzza\CommandBus\Domain\Handler\HandlerMethodResolverInterface,
                $this->calling($methodResolver)->resolveMethodName = 'tututu',
                $sut = $this->newTestedInstance($locator, $methodResolver)
            )
            ->exception(function () use ($sut, $command) {
                $sut->handle($command);
            })
                ->hasMessage('Service mock\Rezzza\CommandBus\Tests\Fixtures\UserService has no method tututu to handle command.')
            ->then
                ->mock($handler)
                    ->wasNotCalled()
        ;
    }

    public function test_no_handler_located_should_lead_to_exception()
    {
        $this
            ->given(
                $locator = new \mock\Rezzza\CommandBus\Domain\Handler\CommandHandlerLocatorInterface,
                $this->calling($locator)->getCommandHandler = null,
                $methodResolver = new \mock\Rezzza\CommandBus\Domain\Handler\HandlerMethodResolverInterface,
                $sut = $this->newTestedInstance($locator, $methodResolver)
            )
            ->exception(function () use ($sut) {
                $sut->handle(new \Rezzza\CommandBus\Tests\Fixtures\Command\SendWelcomeEmailCommand);
            })
                ->hasMessage('Handler locator return a not object|callable handler, type is NULL')
        ;
    }
}
