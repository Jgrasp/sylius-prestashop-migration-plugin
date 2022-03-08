<?php
declare(strict_types=1);

namespace Jgrasp\PrestashopMigrationPlugin\Validator;

use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

interface ViolationBagInterface
{
    /**
     * @return ConstraintViolationInterface[]
     */
    public function all(): array;

    public function addViolation(Violation $violation);

    public function addConstraintViolationList(int $entityId, ConstraintViolationListInterface $constraintViolationList);
}
