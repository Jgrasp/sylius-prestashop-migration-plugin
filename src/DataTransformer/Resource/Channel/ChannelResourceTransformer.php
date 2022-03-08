<?php
declare(strict_types=1);

namespace Jgrasp\PrestashopMigrationPlugin\DataTransformer\Resource\Channel;

use Exception;
use Jgrasp\PrestashopMigrationPlugin\DataTransformer\Resource\ResourceTransformerInterface;
use Jgrasp\PrestashopMigrationPlugin\Model\ModelInterface;
use Jgrasp\PrestashopMigrationPlugin\Model\Shop\ShopModel;
use Sylius\Component\Core\Formatter\StringInflector;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ChannelResourceTransformer implements ResourceTransformerInterface
{
    private ResourceTransformerInterface $transformer;

    private RepositoryInterface $localeRepository;

    private RepositoryInterface $currencyRepository;

    private ParameterBagInterface $parameterBag;

    public function __construct(
        ResourceTransformerInterface $transformer,
        RepositoryInterface          $localeRepository,
        RepositoryInterface          $currencyRepository,
        ParameterBagInterface        $parameterBag
    )
    {
        $this->transformer = $transformer;
        $this->localeRepository = $localeRepository;
        $this->currencyRepository = $currencyRepository;
        $this->parameterBag = $parameterBag;
    }

    /**
     * @param ShopModel $model
     *
     * @return ResourceInterface
     * @throws Exception
     */
    public function transform(ModelInterface $model): ResourceInterface
    {
        /**
         * @var ChannelInterface $channel
         */
        $channel = $this->transformer->transform($model);

        $channel->setCode(StringInflector::nameToUppercaseCode($channel->getName()));
        $localeCode = $this->parameterBag->get('locale');

        $locale = $this->localeRepository->findOneBy(['code' => $localeCode]);

        if (null === $locale) {
            $locales = $this->localeRepository->findAll();
            $locale = reset($locales);

            if ($locale === false) {
                $locale = null;
            }
        }

        $channel->addLocale($locale);
        $channel->setDefaultLocale($locale);

        $currencyPrestashop = reset($model->currencies);

        if ($currencyPrestashop) {
            $currency = $this->currencyRepository->findOneBy(['prestashopId' => $currencyPrestashop]);

            $channel->setBaseCurrency($currency);
            $channel->addCurrency($currency);
        }

        return $channel;
    }

}
