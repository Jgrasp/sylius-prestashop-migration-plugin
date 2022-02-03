<?php

namespace Jgrasp\PrestashopMigrationPlugin\Mapper;

use Exception;
use Jgrasp\PrestashopMigrationPlugin\Attribute\Field;
use Jgrasp\PrestashopMigrationPlugin\Attribute\PropertyAttributeAccessor;
use Jgrasp\PrestashopMigrationPlugin\Model\ModelInterface;
use ReflectionClass;

class EntityMapper implements MapperInterface
{
    private string $model;

    private PropertyAttributeAccessor $propertyAttributeAccessor;

    public function __construct(string $model, PropertyAttributeAccessor $propertyAttributeAccessor)
    {
        $this->model = $model;
        $this->propertyAttributeAccessor = $propertyAttributeAccessor;
    }

    public function map(array $data): ModelInterface
    {
        $model = new $this->model();

        $reflection = new ReflectionClass($model);
        $properties = $reflection->getProperties();

        foreach ($properties as $property) {

            $attribute = $this->propertyAttributeAccessor->get($property, Field::class);

            if ($attribute) {
                $field = $attribute->newInstance();

                if (!isset($data[$field->source])) {
                    throw new Exception(sprintf('Property does not exist. Please verify if "%s" is a valid field in your source data.', $field->name));
                }

                $property->setValue($model, $data[$field->source]);
            }
        }

        return $model;
    }
}
