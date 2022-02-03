<?php

namespace Jgrasp\PrestashopMigrationPlugin\Factory;

use Jgrasp\PrestashopMigrationPlugin\Attribute\Field;
use Jgrasp\PrestashopMigrationPlugin\Attribute\PropertyAttributeAccessor;
use Jgrasp\PrestashopMigrationPlugin\Model\ModelInterface;
use ReflectionClass;
use Sylius\Component\Resource\Model\ResourceInterface;

class Factory implements FactoryInterface
{
    private \Sylius\Component\Resource\Factory\FactoryInterface $factory;

    private PropertyAttributeAccessor $propertyAttributeAccessor;

    public function __construct(\Sylius\Component\Resource\Factory\FactoryInterface $factory, PropertyAttributeAccessor $propertyAttributeAccessor)
    {
        $this->factory = $factory;
        $this->propertyAttributeAccessor = $propertyAttributeAccessor;
    }

    public function createNew(ModelInterface $model): ResourceInterface
    {
        /**
         * @var ResourceInterface $resource;
         */
        $resource = $this->factory->createNew();

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
