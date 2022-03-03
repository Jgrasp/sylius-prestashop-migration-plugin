<?php
declare(strict_types=1);

namespace Jgrasp\PrestashopMigrationPlugin\DataTransformer\Resource\Product;

use Jgrasp\PrestashopMigrationPlugin\DataTransformer\Resource\ResourceTransformerInterface;
use Jgrasp\PrestashopMigrationPlugin\Model\LocaleFetcher;
use Jgrasp\PrestashopMigrationPlugin\Model\ModelInterface;
use Jgrasp\PrestashopMigrationPlugin\Model\Product\ProductAttributeModel;
use Jgrasp\PrestashopMigrationPlugin\Repository\EntityRepositoryInterface;
use Jgrasp\PrestashopMigrationPlugin\Repository\Product\ProductAttributeRepository;
use Jgrasp\PrestashopMigrationPlugin\Repository\Product\ProductRepository;
use Sylius\Component\Channel\Model\ChannelsAwareInterface;
use Sylius\Component\Core\Formatter\StringInflector;
use Sylius\Component\Core\Model\ChannelPricingInterface;
use Sylius\Component\Core\Model\ProductVariantInterface;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

class ProductVariantResourceTransformer implements ResourceTransformerInterface
{
    private ResourceTransformerInterface $transformer;

    private RepositoryInterface $productRepository;

    private RepositoryInterface $productOptionValueRepository;

    /** @var ProductRepository $productEntityRepository */
    private EntityRepositoryInterface $productEntityRepository;

    /** @var ProductAttributeRepository $productAttributeRepository */
    private EntityRepositoryInterface $productAttributeRepository;

    private FactoryInterface $channelPricingFactory;

    private LocaleFetcher $localeFetcher;

    public function __construct(
        ResourceTransformerInterface $transformer,
        RepositoryInterface          $productRepository,
        RepositoryInterface          $productOptionValueRepository,
        EntityRepositoryInterface    $productEntityRepository,
        EntityRepositoryInterface    $productAttributeRepository,
        FactoryInterface             $channelPricingFactory,
        LocaleFetcher       $localeFetcher
    )
    {
        $this->transformer = $transformer;
        $this->productRepository = $productRepository;
        $this->productOptionValueRepository = $productOptionValueRepository;
        $this->productEntityRepository = $productEntityRepository;
        $this->productAttributeRepository = $productAttributeRepository;
        $this->channelPricingFactory = $channelPricingFactory;
        $this->localeFetcher = $localeFetcher;
    }

    /**
     * @param ProductAttributeModel $model
     *
     * @return ResourceInterface|null
     */
    public function transform(ModelInterface $model): ?ResourceInterface
    {
        /** @var ProductVariantInterface $resource */
        $resource = $this->transformer->transform($model);

        /** @var ProductInterface|ChannelsAwareInterface|null $product */
        $product = $this->productRepository->findOneBy(['prestashopId' => $model->productId]);

        if (null === $product) {
            return null;
        }

        $code = $product->getCode().'_'.$resource->getPrestashopId();

        $resource->setCode(StringInflector::nameToCode($code));

        //Add options
        $attributes = $this->productAttributeRepository->getAttributes($model->id);

        foreach ($attributes as $attribute) {
            $attributeId = (int)$attribute['id_attribute'];

            $productOptionValue = $this->productOptionValueRepository->findOneBy(['prestashopId' => $attributeId]);

            //Escape product variant transformation if an option value is not found
            //@Todo add log here
            if (null === $productOptionValue) {
                return null;
            }

            $resource->addOptionValue($productOptionValue);
        }

        //Add channels informations
        foreach ($product->getChannels() as $channel) {
            $channelPricing = $resource->getChannelPricingForChannel($channel);

            if (null === $channelPricing) {
                /** @var ChannelPricingInterface $channelPricing */
                $channelPricing = $this->channelPricingFactory->createNew();
                $channelPricing->setChannelCode($channel->getCode());
            }

            $defaultPrice = $this->productEntityRepository->getPriceByShopId($product->getPrestashopId(), $channel->getPrestashopId());
            $price = ($defaultPrice + $model->price) * 100;


            $channelPricing->setPrice($price);
            $resource->addChannelPricing($channelPricing);
        }

        foreach ($this->localeFetcher->getLocales() as $locale) {
            $resource->setCurrentLocale($locale->getCode());
            $resource->setFallbackLocale($locale->getCode());

            $resource->setName(null);
        }


        $resource->setProduct($product);

        return $resource;
    }

}
