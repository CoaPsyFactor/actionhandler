<?php

namespace RequestHandler\Utils\Factory;

use RequestHandler\Exceptions\ObjectFactoryException;

interface IFactory
{

    /**
     *
     * Returns singleton instance of object using second argument as construct parameters
     *
     * @param string $interface
     * @param array ...$arguments
     * @return mixed
     */
    public static function create(string $interface, ... $arguments);

    /**
     *
     * Maps interface with corresponding class
     *
     * @param string $interface
     * @param string $className
     * @return void
     * @throws ObjectFactoryException
     */
    public static function map(string $interface, string $className): void;

    /**
     * @param array $map
     * @return void
     */
    public static function setMap(array $map): void;
}