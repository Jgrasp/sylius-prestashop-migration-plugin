<?php
declare(strict_types=1);

namespace Jgrasp\PrestashopMigrationPlugin\Provider;

use Exception;
use Jgrasp\PrestashopMigrationPlugin\Attribute\Field;
use Jgrasp\PrestashopMigrationPlugin\Attribute\PropertyAttributeAccessor;
use Jgrasp\PrestashopMigrationPlugin\Entity\PrestashopTrait;
use Jgrasp\PrestashopMigrationPlugin\Model\ModelInterface;
use ReflectionClass;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

final class ResourceProvider implements ResourceProviderInterface
{
    private RepositoryInterface $repository;

    private FactoryInterface $factory;

    private PropertyAttributeAccessor $propertyAttributeAccessor;

    public function __construct(RepositoryInterface $repository, FactoryInterface $factory, PropertyAttributeAccessor $propertyAttributeAccessor)
    {
        $this->repository = $repository;
        $this->factory = $factory;
        $this->propertyAttributeAccessor = $propertyAttributeAccessor;
    }

    public function getResource(ModelInterface $model): ResourceInterface
    {
        $reflection = new ReflectionClass($this->repository->getClassName());
        $traits = $reflection->getTraitNames();

        if (in_array(PrestashopTrait::class, $traits) === false) {
            throw new Exception(sprintf("Entity %s should implement an instance of Trait %s", $this->repository->getClassName(), PrestashopTrait::class));
        }

        $prestashopId = null;
        $modelReflection = new ReflectionClass($model);
        $modelProperties = $modelReflection->getProperties();

        foreach ($modelProperties as $property) {
            $attribute = $this->propertyAttributeAccessor->get($property, Field::class);

            if (null === $attribute) {
                continue;
            }

            $field = $attribute->newInstance();

            if (false === $field->id) {
                continue;
            }

            $prestashopId = $property->getValue($model);
            break;
        }

        $resource = $this->repository->findOneBy(['prestashopId' => $prestashopId]);

        if (!$resource) {
            $resource = $this->factory->createNew();
        }

        return $resource;
    }
}
