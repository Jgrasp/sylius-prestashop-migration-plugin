<?php

namespace Jgrasp\PrestashopMigrationPlugin\DataTransformer\Resource\Taxon;

use App\Entity\Taxonomy\Taxon;
use Jgrasp\PrestashopMigrationPlugin\DataTransformer\Resource\ResourceTransformerInterface;
use Jgrasp\PrestashopMigrationPlugin\Model\Category\CategoryModel;
use Jgrasp\PrestashopMigrationPlugin\Model\LocaleFetcher;
use Jgrasp\PrestashopMigrationPlugin\Model\ModelInterface;
use Sylius\Component\Core\Formatter\StringInflector;
use Sylius\Component\Core\Model\TaxonInterface;
use Sylius\Component\Locale\Model\LocaleInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Taxonomy\Generator\TaxonSlugGeneratorInterface;
use Sylius\Component\Taxonomy\Repository\TaxonRepositoryInterface;

final class TaxonResourceTransformer implements ResourceTransformerInterface
{
    private ResourceTransformerInterface $transformer;

    private FactoryInterface $taxonTranslationFactory;

    private TaxonSlugGeneratorInterface $taxonSlugGenerator;

    private TaxonRepositoryInterface $taxonRepository;

    private LocaleFetcher $localeFetcher;

    public function __construct(
        ResourceTransformerInterface $transformer,
        FactoryInterface             $taxonTranslationFactory,
        TaxonSlugGeneratorInterface  $taxonSlugGenerator,
        TaxonRepositoryInterface     $taxonRepository,
        LocaleFetcher                $localeFetcher
    )
    {
        $this->transformer = $transformer;
        $this->taxonTranslationFactory = $taxonTranslationFactory;
        $this->taxonSlugGenerator = $taxonSlugGenerator;
        $this->taxonRepository = $taxonRepository;
        $this->localeFetcher = $localeFetcher;
    }

    public function transform(ModelInterface $model): ResourceInterface
    {
        /**
         * @var Taxon $taxon
         */
        $taxon = $this->transformer->transform($model);

        foreach ($this->localeFetcher->getLocales() as $locale) {
            $taxon->setCurrentLocale($locale->getCode());
            $taxon->setFallbackLocale($locale->getCode());

            $taxon->setName($model->name[$locale->getCode()]);
        }

        if (null === $taxon->getId()) {
            $taxon->setCode(StringInflector::nameToLowercaseCode(sprintf('%s %s', $taxon->getName(), $model->id)));
        }

        //Set the name with code because prestashop can have multiple categories with same name. Can break the slug taxon in Sylius which is unique.
        $name = $taxon->getName();

        $taxon->setName($taxon->getCode());
        $taxon->setSlug($this->taxonSlugGenerator->generate($taxon));

        $taxon->setName($name);
        $taxon->setParent($this->taxonRepository->findOneBy(['code' => 'MENU_CATEGORY']));

        return $taxon;
    }
}
