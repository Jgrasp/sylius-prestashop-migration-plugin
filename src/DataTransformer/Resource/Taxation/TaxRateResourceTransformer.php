<?php
declare(strict_types=1);

namespace Jgrasp\PrestashopMigrationPlugin\DataTransformer\Resource\Taxation;

use Doctrine\ORM\EntityManagerInterface;
use Jgrasp\PrestashopMigrationPlugin\DataTransformer\Resource\ResourceTransformerInterface;
use Jgrasp\PrestashopMigrationPlugin\Model\ModelInterface;
use Jgrasp\PrestashopMigrationPlugin\Model\Tax\TaxModel;
use Sylius\Component\Addressing\Model\ZoneMemberInterface;
use Sylius\Component\Core\Formatter\StringInflector;
use Sylius\Component\Locale\Context\LocaleContextInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Sylius\Component\Taxation\Model\TaxCategoryInterface;
use Sylius\Component\Taxation\Model\TaxRateInterface;
use Sylius\Component\Taxation\Repository\TaxCategoryRepositoryInterface;

class TaxRateResourceTransformer implements ResourceTransformerInterface
{
    private ResourceTransformerInterface $transformer;

    /** @var TaxCategoryRepositoryInterface */
    private RepositoryInterface $taxCategoryRepository;

    private RepositoryInterface $zoneMemberRepository;

    private FactoryInterface $taxCategoryFactory;

    private EntityManagerInterface $entityManager;

    private LocaleContextInterface $localeContext;

    public function __construct(
        ResourceTransformerInterface $transformer,
        RepositoryInterface          $taxCategoryRepository,
        RepositoryInterface          $zoneMemberRepository,
        FactoryInterface             $taxCategoryFactory,
        EntityManagerInterface       $entityManager,
        LocaleContextInterface       $localeContext
    )
    {
        $this->transformer = $transformer;
        $this->taxCategoryRepository = $taxCategoryRepository;
        $this->zoneMemberRepository = $zoneMemberRepository;
        $this->taxCategoryFactory = $taxCategoryFactory;
        $this->entityManager = $entityManager;
        $this->localeContext = $localeContext;
    }

    /**
     * @param TaxModel $model
     *
     * @return ResourceInterface|null
     */
    public function transform(ModelInterface $model): ?ResourceInterface
    {
        /** @var TaxRateInterface $resource */
        $resource = $this->transformer->transform($model);

        $resource->setName($model->getName($this->localeContext->getLocaleCode()));
        $resource->setAmount($resource->getAmount() / 100);
        $resource->setCode(StringInflector::nameToLowercaseCode($resource->getName()));
        $resource->setCalculator('default');

        if (null === $resource->getName()) {
            return null;
        }

        $this->addZone($resource, $model);

        if (null === $resource->getZone()) {
            return null;
        }

        $this->addTaxCategory($resource);

        if (null === $resource->getCategory()) {
            return null;
        }

        return $resource;
    }

    private function addTaxCategory(TaxRateInterface $resource): void
    {

        /** @var TaxCategoryInterface $taxCategory */
        $taxCategory = $this->taxCategoryRepository->findOneBy(['code' => $resource->getCode()]);

        if (null === $taxCategory) {
            /** @var TaxCategoryInterface $taxCategory */
            $taxCategory = $this->taxCategoryFactory->createNew();
        }

        $taxCategory->setCode($resource->getCode());
        $taxCategory->setName($resource->getName());

        $this->entityManager->persist($taxCategory);
        $this->entityManager->flush();

        $resource->setCategory($taxCategory);
    }

    private function addZone(TaxRateInterface $resource, ModelInterface $model): void
    {
        $countryCode = $this->getCountryCode($model);

        /** @var ZoneMemberInterface|null $zoneMember */
        $zoneMember = $this->zoneMemberRepository->findOneBy(['code' => $countryCode]);

        if (null === $zoneMember) {
            return;
        }

        $resource->setZone($zoneMember->getBelongsTo());
    }

    /**
     * @param TaxModel $model
     *
     * @return string|null
     */
    private function getTaxName(ModelInterface $model): ?string
    {
        $localeCode = $this->localeContext->getLocaleCode();

        //@Todo add logs
        if (!$model->hasName($localeCode)) {
            return null;
        }

        return $model->getName($localeCode);
    }

    private function getCountryCode(ModelInterface $model): ?string
    {
        $name = $this->getTaxName($model);

        if (null === $name) {
            return null;
        }

        $values = explode(' ', $name);

        return $values[1] ?? null;
    }
}
