<?php
declare(strict_types=1);

namespace Jgrasp\PrestashopMigrationPlugin\DataTransformer\Resource\Taxation;

use Behat\Transliterator\Transliterator;
use Jgrasp\PrestashopMigrationPlugin\DataTransformer\Resource\ResourceTransformerInterface;
use Jgrasp\PrestashopMigrationPlugin\Model\ModelInterface;
use Sylius\Component\Core\Formatter\StringInflector;
use Sylius\Component\Locale\Context\LocaleContextInterface;
use Sylius\Component\Resource\Model\ResourceInterface;

class TaxCategoryResourceTransformer implements ResourceTransformerInterface
{
    private ResourceTransformerInterface $transformer;

    private LocaleContextInterface $localeContext;

    public function __construct(ResourceTransformerInterface $transformer, LocaleContextInterface $localeContext)
    {
        $this->transformer = $transformer;
        $this->localeContext = $localeContext;
    }

    public function transform(ModelInterface $model): ResourceInterface
    {
        $resource = $this->transformer->transform($model);

        $resource->setName($model->getName($this->localeContext->getLocaleCode()));
        $resource->setCode(StringInflector::nameToLowercaseCode(Transliterator::transliterate($resource->getName())));

        return $resource;
    }

}
