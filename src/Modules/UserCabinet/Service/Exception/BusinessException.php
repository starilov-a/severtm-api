<?php

namespace App\Modules\UserCabinet\Service\Exception;

abstract class BusinessException extends \DomainException
{
    public function __construct(
        string $publicMessage,
        string $codeKey,
        int    $httpStatus = 400,
        ?\Throwable $previous = null
    ) {
        parent::__construct($publicMessage, 0, $previous);
        $this->codeKey    = $codeKey;
        $this->httpStatus = $httpStatus;
    }

    public function getCodeKey(): string   { return $this->codeKey; }
    public function getHttpStatus(): int   { return $this->httpStatus; }
    private string $codeKey;
    private int    $httpStatus;
}