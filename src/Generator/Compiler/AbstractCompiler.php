<?php

/*
 * Copyright (c) Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Eureka\Component\Orm\Generator\Compiler;

use Eureka\Component\Database\Connection;
use Eureka\Component\Orm\Exception\GeneratorException;
use Eureka\Component\Orm\Generator\Compiler\Field\Field;
use Eureka\Component\Orm\Generator\Type;

/**
 * Abstract Compiler class
 *
 * @author Romain Cottard
 */
abstract class AbstractCompiler
{
    /** @var bool $verbose Verbose active or not. */
    protected bool $verbose = true;

    /** @var string $rootDir */
    protected string $rootDir = __DIR__ . '/../../eureka-component';

    /** @var Connection $connection */
    protected Connection $connection;

    /** @var string $validatorsConfig */
    protected string $validatorsConfig = '';

    /** @var bool[]  */
    protected array $templates = [];

    /**
     * @return Context
     */
    abstract protected function getContext(): Context;

    /**
     * AbstractCompiler constructor.
     *
     * @param array $templates
     */
    public function __construct(array $templates)
    {
        $this->templates = $templates;
    }

    /**
     * Set verbose mode
     *
     * @param  bool $verbose
     * @return $this
     */
    public function setVerbose(bool $verbose): self
    {
        $this->verbose = (bool) $verbose;

        return $this;
    }

    /**
     * Set root directory
     *
     * @param  string $rootDir
     * @return $this
     */
    /*public function setRootDirectory(string $rootDir): self
    {
        $this->rootDir = (string) $rootDir;

        return $this;
    }*/

    /**
     * Set database connection.
     *
     * @param  Connection $connection
     * @return $this
     */
    public function setConnection(Connection $connection): self
    {
        $this->connection = $connection;

        return $this;
    }

    /**
     * @param string $template
     * @param Context $context
     * @return string
     * @throws GeneratorException
     */
    protected function renderTemplate(string $template, Context $context): string
    {
        //~ Read template
        $content = $this->readTemplate($template);

        //~ Replace template vars
        $content = str_replace($context->getKeys(), $context->getValues(), $content);
        return str_replace(["\n\n\n", "    }\n\n}"], ["\n\n", "    }\n}"], $content); // clean double empty lines
    }

    /**
     * @param string $template
     * @return string
     * @throws GeneratorException
     */
    protected function readTemplate(string $template): string
    {
        if (!is_readable($template)) {
            throw new GeneratorException('Template file does not exists or not readable (file: ' . $template . ')'); // @codeCoverageIgnore
        }

        $content = file_get_contents($template);

        if ($content === false) {
            throw new GeneratorException('Template file does not exists or not readable (file: ' . $template . ')'); // @codeCoverageIgnore
        }

        return $content;
    }

    /**
     * @param Context $context
     * @param bool $isAbstract
     * @return Context
     */
    protected function updateContext(Context $context, bool $isAbstract = false): Context
    {
        return $context;
    }

    /**
     * Get method name for getter.
     *
     * @param Field $field
     * @return string
     */
    protected function getNameForGetter(Field $field): string
    {
        $toReplace  = array(
            '/^(is_)/i',
            '/^(has_)/i',
            '/^(in_)/i', // db_prefix is empty
            '/(_is_)/i',
            '/(_has_)/i',
            '/(_in_)/i', // db_prefix is not empty
            '/(_)/',
        );
        $methodName = str_replace(' ', '', ucwords(preg_replace($toReplace, ' ', strtolower($field->getName(true)))));

        $type = $field->getType();
        $name = $field->getName();

        switch (true) {
            case ($type instanceof Type\TypeBool) && (stripos($name, '_has_') !== false || stripos($name, 'has_') === 0):
                $methodPrefix = 'has';
                break;
            case ($type instanceof Type\TypeBool) && (stripos($name, '_is_') !== false || stripos($name, 'is_') === 0):
                $methodPrefix = 'is';
                break;
            case ($type instanceof Type\TypeBool) && (stripos($name, '_in_') !== false || stripos($name, 'in_') === 0):
                $methodPrefix = 'in';
                break;
            default:
                $methodPrefix = 'get';
                break;
        }

        return $methodPrefix . $methodName;
    }

    /**
     * Get method name for setter.
     *
     * @param Field $field
     * @return string
     */
    protected function getNameForSetter(Field $field): string
    {
        return 'set' . str_replace(' ', '', ucwords(str_replace('_', ' ', strtolower($field->getName(true)))));
    }

    /**
     * Get property name for field in data class.
     *
     * @param Field $field
     * @return string
     */
    protected function getPropertyName(Field $field): string
    {
        return lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', strtolower($field->getName(true))))));
    }
}
