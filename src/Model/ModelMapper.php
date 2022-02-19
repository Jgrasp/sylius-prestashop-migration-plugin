<?php

namespace Jgrasp\PrestashopMigrationPlugin\Model;

use Exception;
use Jgrasp\PrestashopMigrationPlugin\Attribute\Field;
use Jgrasp\PrestashopMigrationPlugin\Attribute\PropertyAttributeAccessor;
use ReflectionClass;

final class ModelMapper implements ModelMapperInterface
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

                if (!array_key_exists($field->source,$data)) {
                    throw new Exception(sprintf('Property does not exist for Model %s. Please verify if "%s" is a valid field in your source data.', get_class($model), $field->source));
                }

                $property->setValue($model, $data[$field->source]);
            }
        }

        return $model;
    }
}
