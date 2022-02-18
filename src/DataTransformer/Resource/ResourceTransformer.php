<?php

namespace Jgrasp\PrestashopMigrationPlugin\DataTransformer\Resource;

use Jgrasp\PrestashopMigrationPlugin\Attribute\Field;
use Jgrasp\PrestashopMigrationPlugin\Attribute\PropertyAttributeAccessor;
use Jgrasp\PrestashopMigrationPlugin\Model\ModelInterface;
use Jgrasp\PrestashopMigrationPlugin\Provider\ResourceProviderInterface;
use ReflectionClass;
use Sylius\Component\Resource\Model\ResourceInterface;

final class ResourceTransformer implements ResourceTransformerInterface
{
    private ResourceProviderInterface $resourceProvider;

    private PropertyAttributeAccessor $propertyAttributeAccessor;

    public function __construct(ResourceProviderInterface $resourceProvider, PropertyAttributeAccessor $propertyAttributeAccessor)
    {
        $this->resourceProvider = $resourceProvider;
        $this->propertyAttributeAccessor = $propertyAttributeAccessor;
    }

    public function transform(ModelInterface $model): ResourceInterface
    {
        $resource = $this->resourceProvider->getResource($model);

        $reflectionModel = new ReflectionClass($model);
        $properties = $reflectionModel->getProperties();

        foreach ($properties as $property) {

            $attribute = $this->propertyAttributeAccessor->get($property, Field::class);

            if ($attribute) {
                $field = $attribute->newInstance();
                $resourceReflection = new ReflectionClass($resource);

                $setter = 'set'.ucwords($field->target);

                if ($resourceReflection->hasMethod($setter)) {
                    $resourceReflection->getMethod($setter)->invoke($resource, $property->getValue($model));
                }
            }
        }

        return $resource;
    }

}
