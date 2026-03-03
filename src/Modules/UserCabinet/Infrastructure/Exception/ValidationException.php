<?php

namespace App\Modules\UserCabinet\Infrastructure\Exception;

use Throwable;

class ValidationException extends \InvalidArgumentException
{
    protected array $errors;
    public function __construct(array $errors = array(), $message = "", int $code = 0, Throwable $previous = null)
    {
        $this->errors = $errors;
        parent::__construct($message);
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}