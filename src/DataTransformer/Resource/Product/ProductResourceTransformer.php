<?php

namespace Jgrasp\PrestashopMigrationPlugin\DataTransformer\Resource\Product;

use Jgrasp\PrestashopMigrationPlugin\DataTransformer\Resource\ResourceTransformerInterface;
use Jgrasp\PrestashopMigrationPlugin\Model\ModelInterface;
use Sylius\Component\Core\Formatter\StringInflector;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Resource\Model\ResourceInterface;

class ProductResourceTransformer implements ResourceTransformerInterface
{
    private ResourceTransformerInterface $transformer;

    public function __construct(ResourceTransformerInterface $transformer)
    {
        $this->transformer = $transformer;
    }

    public function transform(ModelInterface $model): ResourceInterface
    {
        /**
         * @var ProductInterface $product
         */
        $product = $this->transformer->transform($model);

        if (null === $model->code) {
            $product->setCode(StringInflector::nameToCode($product->getName()));
        }

        dd($product->getCode());
    }

}
