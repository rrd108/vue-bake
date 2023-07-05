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
    protected $modelName;

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

    public function execute(Arguments $args, ConsoleIo $io): null|int
    {
        if ($args->hasOption('path')) {
            $this->pathFragment = rtrim($args->getOption('path'), '/') . '/';
        }

        $this->modelName = $args->getArgument('name');

        $methods = ['index', 'add', 'edit', 'view'];
        $vars = $this->getVars() + ['lang' => $args->getOption('lang')];

        foreach ($methods as $method) {
            $renderer = $this->createTemplateRenderer()
                ->set('action', $method)
                ->set('plugin', $this->plugin)
                ->set($vars);

            $filename = APP_DIR . DS . $this->pathFragment .  $this->modelName . Inflector::camelize($method) . '.vue';
            $content = $renderer->generate('VueBake.vueComponentTemplate.' . $method);
            $io->createFile($filename, $content);
        }

        return static::CODE_SUCCESS;
    }

    public function templateData(Arguments $arguments): array
    {
        $lang = $arguments->getOption('lang');

        return [
            'lang' => $lang,
        ];
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

        /*$primaryKey = $displayField = $singularVar = $singularHumanName = null;
        $schema = $fields = $hidden = $modelClass = null;
        try {
            $primaryKey = (array)$modelObject->getPrimaryKey();
            $displayField = $modelObject->getDisplayField();
            $singularVar = $this->_singularName($this->controllerName);
            $singularHumanName = $this->_singularHumanName($this->controllerName);
            $schema = $modelObject->getSchema();
            $fields = $schema->columns();
            $hidden = $modelObject->newEmptyEntity()->getHidden() ?: ['token', 'password', 'passwd'];
            $modelClass = $this->modelName;
        } catch (\Exception $exception) {
            $io->error($exception->getMessage());
            $this->abort();
        }

        [, $entityClass] = namespaceSplit($this->_entityName($this->modelName));
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
        }

        $pluralVar = Inflector::variable($this->controllerName);
        $pluralHumanName = $this->_pluralHumanName($this->controllerName);*/

        return compact(
            'modelObject',
            /* 'modelClass',
            'entityClass',
            'schema',
            'primaryKey',
            'displayField',
            'singularVar',
            'pluralVar',
            'singularHumanName',
            'pluralHumanName',
            'fields',
            'hidden',
            'associations',
            'keyFields',
            'namespace'*/
        );
    }
}
