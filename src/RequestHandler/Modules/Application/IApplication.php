<?php

namespace RequestHandler\Modules\Application;

use RequestHandler\Utils\DataFilter\IDataFilter;

interface IApplication
{

    /**
     * Executes handler for requested action
     *
     * @param \Closure $routeRegisterCallback
     */
    public function boot(\Closure $routeRegisterCallback): void;

    /**
     *
     * Retrieve configuration
     *
     * @return array
     */
    public function config(): array;

    /**
     *
     * Retrieve attribute value
     *
     * @param string $name
     * @param mixed $default
     * @param null|IDataFilter $filter
     * @return mixed|null
     */
    public function getAttribute(string $name, $default = null, ?IDataFilter $filter = null);

    /**
     *
     * Sets attribute value
     *
     * @param string $name
     * @param mixed $value
     * @return IApplication
     */
    public function setAttribute(string $name, $value): IApplication;
}