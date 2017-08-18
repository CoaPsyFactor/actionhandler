<?php

namespace RequestHandler\Utils\InputValidator\Rules;

use RequestHandler\Modules\Database\Database;
use RequestHandler\Modules\Database\IDatabase;
use RequestHandler\Utils\SingletonFactory\SingletonFactory;

/**
 *
 * This rule is used to ensure that input value with defined table and field (column) does not exists in database
 *
 * @author Aleksandar Zivanovic
 */
class RuleUniqueEntity extends InputValidatorRule
{

    private const PARAMETER_TABLE = 0;
    private const PARAMETER_FIELD = 1;

    /** @var string */
    private $_table;

    /** @var string */
    private $_field;

    private $_value;

    /**
     * @param mixed $value
     * @return bool
     */
    public function validate($value): bool
    {

        $this->_value = $value;

        $results = SingletonFactory::getSharedInstance(IDatabase::class)
            ->fetchAll("SELECT `{$this->_field}` FROM `{$this->_table}` WHERE `{$this->_field}` = ?;", [$value]);

        return empty($results);
    }

    /**
     * @param array $parameters
     * @return InputValidatorRule
     */
    public function setParameters(array $parameters): InputValidatorRule
    {
        if (isset($parameters[self::PARAMETER_TABLE])) {

            $this->_table = $parameters[self::PARAMETER_TABLE];
        } else {

            throw new \RuntimeException('Rule table must be defined');
        }

        if (isset($parameters[self::PARAMETER_FIELD])) {

            $this->_field = $parameters[self::PARAMETER_FIELD];
        } else {

            throw new \RuntimeException('Rule field must be defined');
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return "{$this->_field} {$this->_value} already exists";
    }
}