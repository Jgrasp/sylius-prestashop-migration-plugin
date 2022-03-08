<?php
declare(strict_types=1);

namespace Jgrasp\PrestashopMigrationPlugin\Validator;

use Symfony\Component\Validator\ConstraintViolationInterface;

class Violation
{
    private int $entityId;

    private string $message;

    public function __construct(int $entityId, string $message)
    {
        $this->entityId = $entityId;
        $this->message = $message;
    }

    public function getEntityId(): int
    {
        return $this->entityId;
    }

    public function getMessage(): string
    {
        return $this->message;
    }
}
