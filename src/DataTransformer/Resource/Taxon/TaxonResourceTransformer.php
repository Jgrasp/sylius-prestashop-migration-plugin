<?php
declare(strict_types=1);

namespace Jgrasp\PrestashopMigrationPlugin\DataTransformer\Resource\Taxon;

use App\Entity\Taxonomy\Taxon;
use Jgrasp\PrestashopMigrationPlugin\DataTransformer\Resource\ResourceTransformerInterface;
use Jgrasp\PrestashopMigrationPlugin\Model\Category\CategoryModel;
use Jgrasp\PrestashopMigrationPlugin\Model\LocaleFetcher;
use Jgrasp\PrestashopMigrationPlugin\Model\ModelInterface;
use Sylius\Component\Core\Formatter\StringInflector;
use Sylius\Component\Core\Model\TaxonInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Taxonomy\Generator\TaxonSlugGeneratorInterface;
use Sylius\Component\Taxonomy\Repository\TaxonRepositoryInterface;


final class TaxonResourceTransformer implements ResourceTransformerInterface
{
    private ResourceTransformerInterface $transformer;

    private TaxonSlugGeneratorInterface $taxonSlugGenerator;

    private TaxonRepositoryInterface $taxonRepository;

    private LocaleFetcher $localeFetcher;

    public function __construct(
        ResourceTransformerInterface $transformer,
        TaxonSlugGeneratorInterface  $taxonSlugGenerator,
        TaxonRepositoryInterface     $taxonRepository,
        LocaleFetcher                $localeFetcher
    )
    {
        $this->transformer = $transformer;
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

            $name = $model->name[$locale->getCode()];

            $taxon->setName($name);
            $taxon->setDescription($model->description[$locale->getCode()]);

            //Set the name with code because prestashop can have multiple categories with same name. Can break the slug taxon in Sylius which is unique.
            if (null === $taxon->getId() && null === $taxon->getCode()) {
                $taxon->setCode(StringInflector::nameToLowercaseCode(sprintf('%s %s', $taxon->getName(), $model->id)));
            }

            $taxon->setName($taxon->getCode());
            $slug = $this->taxonSlugGenerator->generate($taxon);
            $taxon->setName($name);

            $taxon->setSlug($slug);
        }

        $this->addParent($taxon, $model);

        return $taxon;
    }

    /**
     * @param TaxonInterface $taxon
     * @param CategoryModel $model
     *
     * @return void
     */
    private function addParent(TaxonInterface $taxon, ModelInterface $model): void
    {
        $parent = $this->taxonRepository->findOneBy(['prestashopId' => $model->parent]);

        if (null === $parent) {
            $parent = $this->taxonRepository->findOneBy(['code' => 'MENU_CATEGORY']);
        }

        $taxon->setParent($parent);
    }
}
