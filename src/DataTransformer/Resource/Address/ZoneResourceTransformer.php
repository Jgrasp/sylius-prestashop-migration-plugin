<?php
declare(strict_types=1);

namespace Jgrasp\PrestashopMigrationPlugin\DataTransformer\Resource\Address;

use Behat\Transliterator\Transliterator;
use Jgrasp\PrestashopMigrationPlugin\DataTransformer\Resource\ResourceTransformerInterface;
use Jgrasp\PrestashopMigrationPlugin\Model\ModelInterface;
use Jgrasp\PrestashopMigrationPlugin\Repository\Country\CountryRepository;
use Jgrasp\PrestashopMigrationPlugin\Repository\EntityRepositoryInterface;
use Sylius\Component\Addressing\Model\CountryInterface;
use Sylius\Component\Addressing\Model\Scope;
use Sylius\Component\Addressing\Model\ZoneInterface;
use Sylius\Component\Addressing\Model\ZoneMemberInterface;
use Sylius\Component\Core\Formatter\StringInflector;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

class ZoneResourceTransformer implements ResourceTransformerInterface
{
    private ResourceTransformerInterface $transformer;

    /** @var RepositoryInterface */
    private RepositoryInterface $countryRepository;

    /** @var CountryRepository */
    private EntityRepositoryInterface $countryRepositoryPrestashop;

    private FactoryInterface $zoneMemberFactory;

    public function __construct(
        ResourceTransformerInterface $transformer,
        RepositoryInterface          $countryRepository,
        EntityRepositoryInterface    $countryRepositoryPrestashop,
        FactoryInterface             $zoneMemberFactory
    )
    {
        $this->transformer = $transformer;
        $this->countryRepository = $countryRepository;
        $this->countryRepositoryPrestashop = $countryRepositoryPrestashop;
        $this->zoneMemberFactory = $zoneMemberFactory;
    }

    public function transform(ModelInterface $model): ResourceInterface
    {
        /** @var ZoneInterface $resource */
        $resource = $this->transformer->transform($model);

        $resource->setCode(StringInflector::nameToLowercaseCode(Transliterator::transliterate($resource->getName().'_'.$resource->getPrestashopId())));
        $resource->setType(ZoneInterface::TYPE_COUNTRY);
        $resource->setScope(Scope::ALL);

        $countries = $this->countryRepositoryPrestashop->findByZoneId($resource->getPrestashopId());

        foreach ($countries as $row) {
            $countryId = (int)$row['id_country'];
            /** @var CountryInterface|null $country */
            $country = $this->countryRepository->findOneBy(['prestashopId' => $countryId]);

            if (null !== $country) {
                /** @var ZoneMemberInterface $zoneMember */
                $zoneMember = $this->zoneMemberFactory->createNew();
                $zoneMember->setCode($country->getCode());

                if ($resource->getMembers()->filter(static fn(ZoneMemberInterface $member) => $member->getCode() === $zoneMember->getCode())->isEmpty()) {
                    $resource->addMember($zoneMember);
                }
            }
        }

        return $resource;
    }

}
