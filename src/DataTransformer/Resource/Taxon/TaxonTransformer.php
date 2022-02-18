<?php

namespace Jgrasp\PrestashopMigrationPlugin\DataTransformer\Resource\Taxon;

use Jgrasp\PrestashopMigrationPlugin\DataTransformer\Resource\ResourceTransformerInterface;
use Jgrasp\PrestashopMigrationPlugin\Model\ModelInterface;
use Sylius\Component\Core\Formatter\StringInflector;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Taxonomy\Generator\TaxonSlugGeneratorInterface;
use Sylius\Component\Taxonomy\Repository\TaxonRepositoryInterface;

final class TaxonTransformer implements ResourceTransformerInterface
{
    private ResourceTransformerInterface $transformer;

    private TaxonSlugGeneratorInterface $taxonSlugGenerator;
    private TaxonRepositoryInterface $taxonRepository;

    public function __construct(ResourceTransformerInterface $transformer, TaxonSlugGeneratorInterface $taxonSlugGenerator, TaxonRepositoryInterface $taxonRepository)
    {
        $this->transformer = $transformer;
        $this->taxonSlugGenerator = $taxonSlugGenerator;
        $this->taxonRepository = $taxonRepository;
    }

    public function transform(ModelInterface $model): ResourceInterface
    {
        $taxon = $this->transformer->transform($model);

        if (null === $taxon->getId()) {
            $taxon->setCode(StringInflector::nameToLowercaseCode(sprintf('%s %s', $taxon->getName(), $model->getId())));
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
