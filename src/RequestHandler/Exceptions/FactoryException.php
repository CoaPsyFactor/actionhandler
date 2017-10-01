<?php

namespace RequestHandler\Exceptions;

use RequestHandler\Modules\Exception\BaseException;

class FactoryException extends BaseException
{

    const ERROR_INVALID_INTERFACE = 80001;
    const ERROR_INVALID_CLASS = 80002;
    const ERROR_INTERFACE_MISMATCH = 80003;
    const ERROR_INVALID_TYPE = 80004;
    const ERROR_UNRESOLVED_PARAMETER = 80005;

    protected $_errors = [
        FactoryException::ERROR_INVALID_INTERFACE => 'Invalid interface provided.',
        FactoryException::ERROR_INVALID_CLASS => 'Invalid class name provided',
        FactoryException::ERROR_INTERFACE_MISMATCH => 'Class does not implements required interface.',
        FactoryException::ERROR_INVALID_TYPE => 'Requested object is mapped with invalid type',
        FactoryException::ERROR_UNRESOLVED_PARAMETER => 'ObjectFactory is unable to resolve parameter'
    ];
}