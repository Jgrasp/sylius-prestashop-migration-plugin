<?php
declare(strict_types=1);

namespace Jgrasp\PrestashopMigrationPlugin\Validator\Shipping;

use Jgrasp\PrestashopMigrationPlugin\Repository\EntityRepositoryInterface;
use Jgrasp\PrestashopMigrationPlugin\Validator\ValidatorInterface;
use Sylius\Component\Resource\Model\ResourceInterface;

class ShippingMethodValidator implements ValidatorInterface
{
    private ValidatorInterface $validator;

    private EntityRepositoryInterface $carrierRepository;

    public function __construct(ValidatorInterface $validator, EntityRepositoryInterface $carrierRepository)
    {
        $this->validator = $validator;
        $this->carrierRepository = $carrierRepository;
    }

    public function validate(ResourceInterface $resource): bool
    {
        
        return $this->validator->validate($resource);
    }

}
