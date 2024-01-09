<?php

declare(strict_types=1);

namespace VueBake\Command\Bake;

use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Utility\Inflector;
use Bake\Command\SimpleBakeCommand;
use Cake\Console\ConsoleOptionParser;

class VueComponentCommand extends SimpleBakeCommand
{
    public $pathFragment = 'VueComponents/';
    protected string $modelName;
    // public string $connection = 'default';

    public function __construct()
    {
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

        $parser->addOption('lang', [
            'short' => 'l',
            'choices' => ['js', 'ts'],
            'default' => 'js',
            'help' => 'The language of the component. Available options are js and ts.'
        ]);
        $parser->addOption('path', [
            'short' => 'p',
            'help' => 'The save path to the component. Defaults to /src/VueComponents/'
        ]);

        return $parser;
    }

    public function execute(Arguments $args, ConsoleIo $io): int
    {
        if ($args->hasOption('path')) {
            $this->pathFragment = rtrim($args->getOption('path'), '/') . '/';
        }

        $lang = $args->getOption('lang');
        $this->connection = $args->getOption('connection');

        $this->modelName = $args->getArgument('name');

        $methods = ['index', 'add', 'edit', 'view'];
        $vars = $this->getVars() + ['lang' => $lang];

        foreach ($methods as $method) {
            $renderer = $this->createTemplateRenderer()
                ->set('action', $method)
                ->set('plugin', $this->plugin)
                ->set($vars);

            $filename = APP_DIR . DS . $this->pathFragment .  $this->modelName . Inflector::camelize($method) . '.vue';
            $content = $renderer->generate('VueBake.vueComponentTemplate.' . $method);
            $io->createFile($filename, $content);
        }

        if ($lang == 'ts') {
            $this->generateTypescriptInterface($io, $vars);
        }

        return static::CODE_SUCCESS;
    }

    // Based on TemplateCommand's _loadController
    protected function getVars(): array
    {

        if ($this->getTableLocator()->exists($this->modelName)) {
            $modelObject = $this->getTableLocator()->get($this->modelName);
        } else {
            $modelObject = $this->getTableLocator()->get($this->modelName, [
                'connectionName' => $this->connection,
            ]);
        }

        try {
            $primaryKey = (array)$modelObject->getPrimaryKey();
            //$displayField = $modelObject->getDisplayField();
            $singularVar = $this->_singularName($this->modelName);
            $singularHumanName = $this->_singularHumanName($this->modelName);
            $schema = $modelObject->getSchema();
            $fields = $schema->columns();
            //$hidden = $modelObject->newEmptyEntity()->getHidden() ?: ['token', 'password', 'passwd'];
            //$modelClass = $this->modelName;
        } catch (\Exception $exception) {
            //$io->error($exception->getMessage());
            $this->abort();
        }

        /*[, $entityClass] = namespaceSplit($this->_entityName($this->modelName));
        $entityClass = sprintf('%s\Model\Entity\%s', $namespace, $entityClass);
        if (!class_exists($entityClass)) {
            $entityClass = EntityInterface::class;
        }
        $associations = $this->_filteredAssociations($modelObject);
        $keyFields = [];
        if (!empty($associations['BelongsTo'])) {
            foreach ($associations['BelongsTo'] as $assoc) {
                $keyFields[$assoc['foreignKey']] = $assoc['variable'];
            }
        }*/

        $pluralVar = Inflector::variable($this->modelName);
        $pluralHumanName = $this->_pluralHumanName($this->modelName);

        return compact(
            'modelObject',
            'pluralHumanName',
            'fields',
            'pluralVar',
            'singularVar',
            'singularHumanName',
            'schema',
            'primaryKey',
            /* 'modelClass',
            'entityClass',
            'displayField',
            'hidden',
            'associations',
            'keyFields',
            'namespace'*/
        );
    }

    protected function generateTypescriptInterface($io, $vars)
    {
        $renderer = $this->createTemplateRenderer()
            ->set($vars);

        $filename = APP_DIR . DS . $this->pathFragment .  $this->_singularHumanName($this->modelName) . 'Interface.ts';
        $content = $renderer->generate('VueBake.vueComponentTemplate.interface');
        $io->createFile($filename, $content);
    }
}
