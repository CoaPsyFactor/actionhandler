<?php

namespace RequestHandler\Modules\Response;

use RequestHandler\Exceptions\ResponseException;
use RequestHandler\Modules\Entity\IModel;
use RequestHandler\Modules\Entity\IRepository;

/**
 * This is used to set up response data, such as status code, data, errors, headers etc..
 *
 * TODO: Add headers handling
 *
 * @package Core\Libs\Response
 */
class Response implements IResponse
{
    const STATUS_SUCCESS = 'success';
    const STATUS_ERROR = 'error';

    /** @var array Array of all available status codes, filled dynamic when "Response" constructor is called */
    private static $commonStatusCodes;

    protected $contentType = 'text/html; charset=utf-8';

    protected $status = IResponseStatus::OK;

    protected $output = '';

    protected $data = [];

    protected $errors = [];

    protected $headers = [];

    private function __construct()
    {
        $responseStatusReflection = new \ReflectionClass(IResponseStatus::class);

        Response::$commonStatusCodes = array_flip($responseStatusReflection->getConstants());
    }


    /**
     *
     * Set single response data value
     *
     * @param string $key
     * @param $value
     * @return IResponse
     */
    public function addData(string $key, $value): IResponse
    {
        $this->data[$key] = $this->getCleanDataValue($value);

        return $this;
    }


    /**
     *
     * Bulk set response data
     *
     * @param array $data
     * @return IResponse
     */
    public function data(array $data): IResponse
    {
        $this->data = [];

        foreach ($data as $key => $value) {

            $this->addData($key, $value);
        }

        return $this;
    }

    /**
     *
     * Retrieve all response data
     *
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     *
     * Set single response error
     *
     * @param string $error
     * @param string $message
     * @return IResponse
     */
    public function addError(string $error, string $message): IResponse
    {
        $this->errors[$error] = $message;

        return $this;
    }

    /**
     *
     * Bulk set response errors
     *
     * @param array $errors
     * @return IResponse
     */
    public function errors(array $errors): IResponse
    {
        $this->errors = $errors;

        return $this;
    }

    /**
     *
     * Retrieve all response errors
     *
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     *
     * Checks does any error in response is set
     *
     * @return bool
     */
    public function hasErrors(): bool
    {
        return false === empty($this->errors);
    }

    /**
     *
     * Checks does certain error in response exists
     *
     * @param string $error
     * @return bool
     */
    public function hasError(string $error): bool
    {
        return isset($this->errors[$error]);
    }

    /**
     *
     * Set response status code
     *
     * @param int $status
     * @return IResponse
     * @throws ResponseException
     */
    public function status(int $status): IResponse
    {
        if (false === isset(Response::$commonStatusCodes[$status])) {
            throw new ResponseException(ResponseException::BAD_STATUS_CODE);
        }

        $this->status = $status;

        return $this;
    }

    /**
     *
     * Retrieve current response status code
     *
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     *
     * Retrieve all response headers in format ["HEADER"=>"VALUE"]
     *
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     *
     * Set headers
     *
     * @param array $headers
     */
    public function setHeaders(array $headers): void
    {
        $this->headers = [];

        foreach ($headers as $header => $value) {
            $this->setHeader($header, $value);
        }
    }

    /**
     * Set single header
     *
     * @param string $header
     * @param $value
     */
    public function setHeader(string $header, $value): void
    {
        $this->headers[$header] = $value;
    }

    /**
     * @inheritdoc
     */
    public function getHeader(string $header): ?string
    {
        return $this->headers[$header] ?? null;
    }

    public function getContentType(): string
    {
        return $this->contentType;
    }

    public function getOutput(): string
    {
        return json_encode([
            $this->errors ? $this->errors : $this->data
        ]);
    }

    /**
     *
     * Retrieve clean (transformed) value
     *
     * We use this method to properly set response data when instance of IModel or array of IModel is given as value
     *
     * @param mixed $value
     * @return mixed
     */
    private function getCleanDataValue($value)
    {
        $returnValue = $value;

        if ($value instanceof IModel || $value instanceof IRepository) {
            $returnValue = $value->toArray();
        } else if (is_array($value)) {
            $returnValue = [];

            foreach ($value as $key => $val) {
                $returnValue[$key] = $this->getCleanDataValue($val);
            }
        }

        return $returnValue;

    }
}