<?php
declare(strict_types=1);

namespace Jgrasp\PrestashopMigrationPlugin\DataTransformer\Resource\Country;

use Jgrasp\PrestashopMigrationPlugin\DataTransformer\Resource\ResourceTransformerInterface;
use Jgrasp\PrestashopMigrationPlugin\Model\Country\CountryModel;
use Jgrasp\PrestashopMigrationPlugin\Model\ModelInterface;
use Sylius\Component\Resource\Model\ResourceInterface;

class CountryResourceTransformer implements ResourceTransformerInterface
{
    private ResourceTransformerInterface $transformer;

    public function __construct(ResourceTransformerInterface $transformer)
    {
        $this->transformer = $transformer;
    }

    /**
     * @param CountryModel $model
     *
     * @return ResourceInterface|null
     */
    public function transform(ModelInterface $model): ?ResourceInterface
    {
        if (!$model->enabled) {
            return null;
        }

        return $this->transformer->transform($model);
    }

}
