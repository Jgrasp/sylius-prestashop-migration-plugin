<?php
declare(strict_types=1);

namespace Jgrasp\PrestashopMigrationPlugin\Validator;

use Jgrasp\PrestashopMigrationPlugin\Entity\PrestashopTrait;
use Sylius\Component\Resource\Model\ResourceInterface;

class ResourceValidator implements ValidatorInterface
{
    private \Symfony\Component\Validator\Validator\ValidatorInterface $validator;

    private ViolationBagInterface $violationBag;

    public function __construct(\Symfony\Component\Validator\Validator\ValidatorInterface $validator, ViolationBagInterface $violationBag)
    {
        $this->validator = $validator;
        $this->violationBag = $violationBag;
    }

    /**
     * @param ResourceInterface|PrestashopTrait $resource
     *
     * @return bool
     */
    public function validate(ResourceInterface $resource): bool
    {
        $violations = $this->validator->validate($resource, null, ['sylius']);

        $this->violationBag->addConstraintViolationList($resource->getPrestashopId(), $violations);

        return $violations->count() === 0;
    }

}
