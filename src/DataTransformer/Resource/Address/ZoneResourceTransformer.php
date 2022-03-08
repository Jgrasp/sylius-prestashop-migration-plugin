<?php
declare(strict_types=1);

namespace Jgrasp\PrestashopMigrationPlugin\DataTransformer\Resource\Address;

use App\Entity\Addressing\Zone;
use Jgrasp\PrestashopMigrationPlugin\DataTransformer\Resource\ResourceTransformerInterface;
use Jgrasp\PrestashopMigrationPlugin\Model\ModelInterface;
use Sylius\Component\Addressing\Model\Scope;
use Sylius\Component\Addressing\Model\ZoneInterface;
use Sylius\Component\Core\Formatter\StringInflector;
use Sylius\Component\Resource\Model\ResourceInterface;

class ZoneResourceTransformer implements ResourceTransformerInterface
{
    private ResourceTransformerInterface $transformer;

    public function __construct(ResourceTransformerInterface $transformer)
    {
        $this->transformer = $transformer;
    }

    public function transform(ModelInterface $model): ?ResourceInterface
    {
        /** @var Zone $resource */
        $resource = $this->transformer->transform($model);

        $resource->setCode(StringInflector::nameToLowercaseCode($resource->getName().'_'.$resource->getPrestashopId()));
        $resource->setType(ZoneInterface::TYPE_COUNTRY);
        $resource->setScope(Scope::ALL);

        return $resource;
    }

}
