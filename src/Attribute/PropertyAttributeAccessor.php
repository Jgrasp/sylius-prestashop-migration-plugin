<?php
declare(strict_types=1);

namespace Jgrasp\PrestashopMigrationPlugin\Attribute;

use ReflectionAttribute;
use ReflectionProperty;

final class PropertyAttributeAccessor
{
    public function get(ReflectionProperty $reflectionProperty, string $attributeClass): ?ReflectionAttribute
    {
        $attributes = $reflectionProperty->getAttributes($attributeClass, ReflectionAttribute::IS_INSTANCEOF);

        /**
         * @var ReflectionAttribute|false $attribute
         */
        $attribute = reset($attributes);

        if (!$attribute) {
            return null;
        }

        return $attribute;
    }
}
