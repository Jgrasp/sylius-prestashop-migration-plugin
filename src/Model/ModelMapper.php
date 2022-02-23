<?php
declare(strict_types=1);

namespace Jgrasp\PrestashopMigrationPlugin\Model;

use Exception;
use Jgrasp\PrestashopMigrationPlugin\Attribute\Field;
use Jgrasp\PrestashopMigrationPlugin\Attribute\PropertyAttributeAccessor;
use ReflectionClass;

final class ModelMapper implements ModelMapperInterface
{
    private string $model;

    private PropertyAttributeAccessor $propertyAttributeAccessor;

    private LocaleFetcher $fetcher;

    public function __construct(string $model, PropertyAttributeAccessor $propertyAttributeAccessor, LocaleFetcher $fetcher)
    {
        $this->model = $model;
        $this->propertyAttributeAccessor = $propertyAttributeAccessor;
        $this->fetcher = $fetcher;
    }

    public function map(array $data): ModelInterface
    {
        $model = new $this->model();

        $reflection = new ReflectionClass($model);
        $properties = $reflection->getProperties();

        foreach ($properties as $property) {

            $attribute = $this->propertyAttributeAccessor->get($property, Field::class);

            if ($attribute) {
                /**
                 * @var Field $field
                 */
                $field = $attribute->newInstance();

                if (!array_key_exists($field->source, $data)) {
                    throw new Exception(sprintf('Source value "%s" does not exist for property $%s in %s. Please verify the field exist in your source data.', $field->source, $property->getName(), get_class($model)));
                }

                $value = $data[$field->source];

                if ($field->translatable) {
                    foreach ($value as $langId => $translation) {
                        $locale = $this->fetcher->getLocaleCode($langId);

                        if (null === $locale) {
                            throw new \Exception(sprintf("Locale not found with langId %s.", $langId));
                        }

                        $value[$locale] = $translation;
                        unset($value[$langId]);
                    }
                }

                $property->setValue($model, $value);
            }
        }

        return $model;
    }
}
