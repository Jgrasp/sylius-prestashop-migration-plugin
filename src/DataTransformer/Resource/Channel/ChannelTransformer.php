<?php

namespace Jgrasp\PrestashopMigrationPlugin\DataTransformer\Resource\Channel;

use App\Entity\Channel\Channel;
use Jgrasp\PrestashopMigrationPlugin\DataTransformer\Resource\ResourceTransformerInterface;
use Jgrasp\PrestashopMigrationPlugin\Model\ModelInterface;
use Sylius\Component\Core\Formatter\StringInflector;
use Sylius\Component\Resource\Model\ResourceInterface;

class ChannelTransformer implements ResourceTransformerInterface
{
    private ResourceTransformerInterface $transformer;

    public function __construct(ResourceTransformerInterface $transformer)
    {
        $this->transformer = $transformer;
    }

    public function transform(ModelInterface $model): ResourceInterface
    {
        /**
         * @var Channel $channel
         */
        $channel = $this->transformer->transform($model);

        $channel->setCode(StringInflector::nameToUppercaseCode($channel->getName()));

        return $channel;
    }

}
