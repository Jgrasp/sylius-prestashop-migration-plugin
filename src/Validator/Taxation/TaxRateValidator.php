<?php
declare(strict_types=1);

namespace Jgrasp\PrestashopMigrationPlugin\Validator\Taxation;

use Jgrasp\PrestashopMigrationPlugin\Validator\ValidatorInterface;
use Sylius\Component\Core\Model\TaxRateInterface;
use Sylius\Component\Resource\Model\ResourceInterface;

class TaxRateValidator implements ValidatorInterface
{
    private ValidatorInterface $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @param TaxRateInterface $resource
     *
     * @return bool
     */
    public function validate(ResourceInterface $resource): bool
    {
        return $this->validator->validate($resource)
            && null !== $resource->getZone();
    }

}
