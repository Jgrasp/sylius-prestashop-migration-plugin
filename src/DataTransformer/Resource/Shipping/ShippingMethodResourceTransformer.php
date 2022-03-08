<?php
declare(strict_types=1);

namespace Jgrasp\PrestashopMigrationPlugin\DataTransformer\Resource\Shipping;

use Jgrasp\PrestashopMigrationPlugin\DataTransformer\Resource\ResourceTransformerInterface;
use Jgrasp\PrestashopMigrationPlugin\Model\Carrier\CarrierModel;
use Jgrasp\PrestashopMigrationPlugin\Model\LocaleFetcher;
use Jgrasp\PrestashopMigrationPlugin\Model\ModelInterface;
use Sylius\Component\Core\Model\ShippingMethodInterface;
use Sylius\Component\Resource\Model\ResourceInterface;

class ShippingMethodResourceTransformer implements ResourceTransformerInterface
{
    private ResourceTransformerInterface $transformer;

    private LocaleFetcher $fetcher;

    public function __construct(ResourceTransformerInterface $transformer, LocaleFetcher $fetcher)
    {
        $this->transformer = $transformer;
        $this->fetcher = $fetcher;
    }

    /**
     * @param CarrierModel $model
     *
     * @return ResourceInterface
     * @throws \Exception
     */
    public function transform(ModelInterface $model): ResourceInterface
    {
        /** @var ShippingMethodInterface $resource */
        $resource = $this->transformer->transform($model);

      /*  foreach ($this->fetcher->getLocales() as $locale) {
            $resource->setCurrentLocale($locale->getCode());
            $resource->setFallbackLocale($locale->getCode());

            $resource->setName($model->name);

        }*/

        return $resource;
    }
}
