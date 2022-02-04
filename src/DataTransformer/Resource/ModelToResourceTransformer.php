<?php

namespace Jgrasp\PrestashopMigrationPlugin\DataTransformer\Resource;


use InvalidArgumentException;
use Jgrasp\PrestashopMigrationPlugin\Attribute\Field;
use Jgrasp\PrestashopMigrationPlugin\Attribute\PropertyAttributeAccessor;
use Jgrasp\PrestashopMigrationPlugin\DataTransformer\DataTransformerInterface;
use Jgrasp\PrestashopMigrationPlugin\Model\ModelInterface;
use Jgrasp\PrestashopMigrationPlugin\Provider\ResourceProviderInterface;
use ReflectionClass;
use Sylius\Component\Resource\Model\ResourceInterface;

final class ModelToResourceTransformer implements DataTransformerInterface
{
    private ResourceProviderInterface $resourceProvider;

    private PropertyAttributeAccessor $propertyAttributeAccessor;
    private DataTransformerInterface $dataTransformer;

    public function __construct(DataTransformerInterface $dataTransformer, ResourceProviderInterface $resourceProvider, PropertyAttributeAccessor $propertyAttributeAccessor)
    {
        $this->dataTransformer = $dataTransformer;
        $this->resourceProvider = $resourceProvider;
        $this->propertyAttributeAccessor = $propertyAttributeAccessor;
    }

    public function transform($data): ResourceInterface
    {
        $model = $this->dataTransformer->transform($data);

        if (!$model instanceof ModelInterface) {
            throw new InvalidArgumentException(sprintf('$model should be an instance of %s.', ModelInterface::class));
        }

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
