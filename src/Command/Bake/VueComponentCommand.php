<?php

declare(strict_types=1);

namespace VueBake\Command\Bake;

use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Bake\Command\TemplateCommand;
use Bake\Command\SimpleBakeCommand;
use Cake\Console\ConsoleOptionParser;

class VueComponentCommand extends SimpleBakeCommand
{
    public $pathFragment = 'VueComponents/';
    private $templateCommand;

    public function __construct()
    {
        $this->templateCommand = new TemplateCommand();
    }

    public function name(): string
    {
        return 'vueComponent';
    }

    public function template(): string
    {
        return 'VueBake.vueComponentTemplate';
    }

    public function fileName(string $name): string
    {
        return $name . '.vue';
    }

    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        $parser = parent::buildOptionParser($parser);

        $parser->addArgument('lang', [
            'choices' => ['js', 'ts'],
            'help' => 'The language of the component. Available options are js and ts.'
        ]);
        $parser->addArgument('path', [
            'help' => 'The save path to the component. Defaults to /src/VueComponents/'
        ]);

        return $parser;
    }

    public function execute(Arguments $args, ConsoleIo $io): null|int
    {
        if ($args->hasArgument('path')) {
            $this->pathFragment = rtrim($args->getArgument('path'), '/') . '/';
        }

        return parent::execute($args, $io);
    }

    public function templateData(Arguments $arguments): array
    {
        $lang = $arguments->getArgument('lang');

        return ['lang' => $lang];
    }
}
