<?php

namespace App\Modules\UserCabinet\Infrastructure\Service\Logger\Adapters;

use App\Modules\UserCabinet\Infrastructure\Service\Logger\Dto\ErrorLogDto;
use App\Modules\UserCabinet\Infrastructure\Service\Logger\ErrorLoggerInterface;
use Psr\Log\LoggerInterface;

class ErrorLogAdapter implements ErrorLoggerInterface
{
    protected LoggerInterface $logger;
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function logError(ErrorLogDto $log): void
    {
        $context = $log->context;
        if (!empty($log->labels)) {
            $context['labels'] = $log->labels;
        }
        if ($log->throwable !== null) {
            $context['exception_class'] = $log->throwable::class;
            $context['exception_code'] = $log->throwable->getCode();
        }
        $context['logged_at'] = $log->when->format(DATE_ATOM);

        $this->logger->log($log->level, $log->message, $context);
    }
}
