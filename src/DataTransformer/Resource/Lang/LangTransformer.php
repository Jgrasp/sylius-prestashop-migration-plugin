<?php

namespace Jgrasp\PrestashopMigrationPlugin\DataTransformer\Resource\Lang;

use Jgrasp\PrestashopMigrationPlugin\DataTransformer\Resource\ResourceTransformerInterface;
use Jgrasp\PrestashopMigrationPlugin\Model\ModelInterface;
use Sylius\Component\Core\Formatter\StringInflector;
use Sylius\Component\Locale\Model\LocaleInterface;
use Sylius\Component\Resource\Model\ResourceInterface;

class LangTransformer implements ResourceTransformerInterface
{
    private ResourceTransformerInterface $transformer;

    public function __construct(ResourceTransformerInterface $transformer)
    {
        $this->transformer = $transformer;
    }

    public function transform(ModelInterface $model): ResourceInterface
    {
        /**
         * @var LocaleInterface $locale
         */
        $locale = $this->transformer->transform($model);
        $locale->setCode(StringInflector::nameToCode($locale->getCode()));

        return $locale;
    }

}
