<?php

namespace Jgrasp\PrestashopMigrationPlugin\DataTransformer\Resource;

use Jgrasp\PrestashopMigrationPlugin\Attribute\Field;
use Jgrasp\PrestashopMigrationPlugin\Attribute\PropertyAttributeAccessor;
use Jgrasp\PrestashopMigrationPlugin\Model\ModelInterface;
use Jgrasp\PrestashopMigrationPlugin\Provider\ResourceProviderInterface;
use ReflectionClass;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\TranslatableInterface;
use Symfony\Component\PropertyAccess\Exception\InvalidArgumentException;
use Symfony\Component\PropertyAccess\PropertyAccess;

final class ResourceTransformer implements ResourceTransformerInterface
{
    private ResourceProviderInterface $resourceProvider;

    private PropertyAttributeAccessor $propertyAttributeAccessor;

    public function __construct(
        ResourceProviderInterface $resourceProvider,
        PropertyAttributeAccessor $propertyAttributeAccessor
    )
    {
        $this->resourceProvider = $resourceProvider;
        $this->propertyAttributeAccessor = $propertyAttributeAccessor;
    }

    public function transform(ModelInterface $model): ResourceInterface
    {
        $resource = $this->resourceProvider->getResource($model);

        $reflectionModel = new ReflectionClass($model);
        $properties = $reflectionModel->getProperties();

        $propertyAccessor = PropertyAccess::createPropertyAccessorBuilder()
            ->enableMagicCall()
            ->getPropertyAccessor();

        foreach ($properties as $property) {

            $attribute = $this->propertyAttributeAccessor->get($property, Field::class);

            if ($attribute) {
                /**
                 * @var Field $field
                 */
                $field = $attribute->newInstance();

                if (null === $field->target) {
                    continue;
                }

                try {
                    $propertyAccessor->setValue($resource, $field->target, $property->getValue($model));
                } catch (InvalidArgumentException $exception) {
                    continue;
                }
            }
        }

        return $resource;
    }

}
