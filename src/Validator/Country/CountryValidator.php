<?php
declare(strict_types=1);

namespace Jgrasp\PrestashopMigrationPlugin\Validator\Country;

use Jgrasp\PrestashopMigrationPlugin\Validator\ValidatorInterface;
use Sylius\Component\Addressing\Model\CountryInterface;
use Sylius\Component\Resource\Model\ResourceInterface;

class CountryValidator implements ValidatorInterface
{
    private ValidatorInterface $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @param CountryInterface $resource
     *
     * @return bool
     */
    public function validate(ResourceInterface $resource): bool
    {
        return $this->validator->validate($resource) && $resource->isEnabled();
    }

}
