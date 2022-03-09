<?php
declare(strict_types=1);

namespace Jgrasp\PrestashopMigrationPlugin\DataTransformer\Resource\Shipping;

use Behat\Transliterator\Transliterator;
use Jgrasp\PrestashopMigrationPlugin\DataTransformer\Resource\ResourceTransformerInterface;
use Jgrasp\PrestashopMigrationPlugin\Model\Carrier\CarrierModel;
use Jgrasp\PrestashopMigrationPlugin\Model\LocaleFetcher;
use Jgrasp\PrestashopMigrationPlugin\Model\ModelInterface;
use Jgrasp\PrestashopMigrationPlugin\Repository\Carrier\CarrierRepository;
use Jgrasp\PrestashopMigrationPlugin\Repository\EntityRepositoryInterface;
use Sylius\Component\Addressing\Model\ZoneInterface;
use Sylius\Component\Core\Formatter\StringInflector;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\ShippingMethodInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Sylius\Component\Shipping\Calculator\DefaultCalculators;

class ShippingMethodResourceTransformer implements ResourceTransformerInterface
{
    private ResourceTransformerInterface $transformer;

    private RepositoryInterface $shippingMethodRepository;

    private RepositoryInterface $channelRepository;

    private RepositoryInterface $zoneRepository;

    /**
     * @var CarrierRepository
     */
    private EntityRepositoryInterface $carrierRepository;

    private LocaleFetcher $fetcher;

    public function __construct(
        ResourceTransformerInterface $transformer,
        RepositoryInterface          $shippingMethodRepository,
        RepositoryInterface          $channelRepository,
        RepositoryInterface          $zoneRepository,
        EntityRepositoryInterface    $carrierRepository,
        LocaleFetcher                $fetcher
    )
    {
        $this->transformer = $transformer;
        $this->shippingMethodRepository = $shippingMethodRepository;
        $this->channelRepository = $channelRepository;
        $this->zoneRepository = $zoneRepository;
        $this->carrierRepository = $carrierRepository;
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

        foreach ($this->fetcher->getLocales() as $locale) {
            $resource->setCurrentLocale($locale->getCode());
            $resource->setFallbackLocale($locale->getCode());

            $resource->setName($model->name);
        }

        $resource->setCalculator(DefaultCalculators::FLAT_RATE);
        $resource->setCode(StringInflector::nameToLowercaseCode(Transliterator::transliterate($resource->getName().'_'.$resource->getPrestashopId())));

        //@TODO Create one shipping method by zone
        $this->addChannels($resource);
        $this->addZone($resource);

        return $resource;
    }

    private function addZone(ShippingMethodInterface $resource): void
    {
        $carrierZones = $this->carrierRepository->getZones($resource->getPrestashopId());

        foreach ($carrierZones as $carrierZone) {
            $zoneId = (int)$carrierZone['id_zone'];
            $zone = $this->zoneRepository->findOneBy(['prestashopId' => $zoneId]);

            if ($zone instanceof ZoneInterface) {
                $resource->setZone($zone);
                break;
            }
        }
    }

    private function addChannels(ShippingMethodInterface $resource): void
    {
        $shops = $this->carrierRepository->getShops($resource->getPrestashopId());

        foreach ($shops as $shop) {
            $shopId = (int)$shop['id_shop'];

            $channel = $this->channelRepository->findOneBy(['prestashopId' => $shopId]);

            if ($channel instanceof ChannelInterface) {
                $resource->addChannel($channel);
            }
        }
    }
}
