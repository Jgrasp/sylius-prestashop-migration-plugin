<?php
declare(strict_types=1);

namespace Jgrasp\PrestashopMigrationPlugin\DataTransformer\Resource\Product;

use Jgrasp\PrestashopMigrationPlugin\DataTransformer\Resource\ResourceTransformerInterface;
use Jgrasp\PrestashopMigrationPlugin\Model\Attribute\AttributeModel;
use Jgrasp\PrestashopMigrationPlugin\Model\LocaleFetcher;
use Jgrasp\PrestashopMigrationPlugin\Model\ModelInterface;
use Sylius\Component\Core\Formatter\StringInflector;
use Sylius\Component\Product\Model\ProductOptionValueInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

class ProductOptionValueResourceTransformer implements ResourceTransformerInterface
{
    private ResourceTransformerInterface $transformer;

    private RepositoryInterface $productOptionRepository;

    private LocaleFetcher $localeFetcher;

    public function __construct(ResourceTransformerInterface $transformer, RepositoryInterface $productOptionRepository, LocaleFetcher $localeFetcher)
    {
        $this->transformer = $transformer;
        $this->productOptionRepository = $productOptionRepository;
        $this->localeFetcher = $localeFetcher;
    }

    /**
     * @param AttributeModel $model
     *
     * @return ResourceInterface|null
     * @throws \Exception
     */
    public function transform(ModelInterface $model): ?ResourceInterface
    {
        /**
         * @var ProductOptionValueInterface $resource
         */
        $resource = $this->transformer->transform($model);

        $productOption = $this->productOptionRepository->findOneBy(['prestashopId' => $model->attributeGroupId]);

        if (null === $productOption) {
            return null;
        }

        $resource->setOption($productOption);

        foreach ($this->localeFetcher->getLocales() as $locale) {
            $resource->setCurrentLocale($locale->getCode());
            $resource->setFallbackLocale($locale->getCode());

            $name = $model->name[$locale->getCode()];

            $resource->setValue($name);

            if (null === $resource->getId() && null === $resource->getCode()) {
                $resource->setCode(StringInflector::nameToLowercaseCode(sprintf('%s %s', $resource->getName(), $model->id)));
            }
        }

        return $resource;
    }
}
