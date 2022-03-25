<?php
declare(strict_types=1);

namespace Jgrasp\PrestashopMigrationPlugin\Validator\Taxon;

use Jgrasp\PrestashopMigrationPlugin\Validator\ValidatorInterface;
use Sylius\Component\Resource\Model\ResourceInterface;

class TaxonValidator implements ValidatorInterface
{
    private ValidatorInterface $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    public function validate(ResourceInterface $resource): bool
    {
        //Remove "Root" category which is pretty useless
        return $resource->getPrestashopId() !== 1 && $this->validator->validate($resource);
    }
}
