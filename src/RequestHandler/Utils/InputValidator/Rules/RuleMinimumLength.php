<?php

namespace RequestHandler\Utils\InputValidator\Rules;

use RequestHandler\Utils\InputValidator\IInputValidator;
use RequestHandler\Utils\InputValidator\IInputValidatorRule;

/**
 *
 * This rule is used to ensure minimum length of input value
 *
 * @author Aleksandar Zivanovic
 */
class RuleMinimumLength implements IInputValidatorRule
{

    private const PARAMETER_MIN = 0;

    /** @var int */
    private $_min = 0;

    /**
     *
     * @param IInputValidator $validator
     * @param mixed $value
     * @return bool
     */
    public function validate(IInputValidator $validator, $value): bool
    {

        return null === $value || ($value && strlen($value) >= $this->_min);
    }

    /**
     * @param array $parameters
     * @return IInputValidatorRule
     */
    public function setParameters(array $parameters): IInputValidatorRule
    {
        if (isset($parameters[self::PARAMETER_MIN])) {

            $this->_min = $parameters[self::PARAMETER_MIN];
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return "Field must contain at least {$this->_min} characters";
    }

    /**
     *
     * Retrieve name of rule
     *
     * @return string
     */
    public function getRuleName(): string
    {

        return 'min';
    }
}
