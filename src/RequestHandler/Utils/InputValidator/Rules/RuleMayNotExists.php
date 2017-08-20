<?php

namespace RequestHandler\Utils\InputValidator\Rules;

use RequestHandler\Utils\InputValidator\IInputValidatorRule;
use RequestHandler\Utils\InputValidator\InputValidatorRule;

/**
 *
 * This rule is used to ensure that input value IS NOT DEFINED
 *
 * @author Aleksandar Zivanovic
 */
class RuleMayNotExists extends InputValidatorRule
{

    /**
     * @param mixed $value
     * @return bool
     */
    public function validate($value): bool
    {
        return false === isset($value);
    }

    /**
     * @param array $parameters
     * @return IInputValidatorRule
     */
    public function setParameters(array $parameters): IInputValidatorRule
    {

        return $this;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return 'field is not supported';
    }

    /**
     *
     * Retrieve name of rule
     *
     * @return string
     */
    public function getRuleName(): string
    {

        return 'may-not-exists';
    }
}
