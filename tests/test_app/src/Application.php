<?php

declare(strict_types=1);

namespace TestApp;

use Cake\Console\CommandCollection;
use Cake\Http\BaseApplication;
use Cake\Http\MiddlewareQueue;
use Cake\Routing\RouteBuilder;
use CakeDumpSql\Command\DumpSqlCommand;

class Application extends BaseApplication
{
    public function middleware(MiddlewareQueue $middlewareQueue): MiddlewareQueue
    {
        return $middlewareQueue;
    }

    public function routes(RouteBuilder $routes): void
    {
    }

    public function console(CommandCollection $commands): CommandCollection
    {
        $commands = parent::console($commands);

        return $commands;
    }

    public function bootstrap(): void
    {
        parent::bootstrap();
        $this->addPlugin('Bake');
    }
}
