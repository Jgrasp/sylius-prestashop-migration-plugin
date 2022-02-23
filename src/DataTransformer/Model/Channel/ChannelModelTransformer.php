<?php
declare(strict_types=1);

namespace Jgrasp\PrestashopMigrationPlugin\DataTransformer\Model\Channel;

use Jgrasp\PrestashopMigrationPlugin\DataTransformer\Model\ModelTransformerInterface;
use Jgrasp\PrestashopMigrationPlugin\Model\ModelInterface;
use Jgrasp\PrestashopMigrationPlugin\Model\Shop\ShopModel;
use Jgrasp\PrestashopMigrationPlugin\Repository\EntityRepositoryInterface;

class ChannelModelTransformer implements ModelTransformerInterface
{
    private ModelTransformerInterface $transformer;

    private EntityRepositoryInterface $currencyRepository;

    public function __construct(ModelTransformerInterface $transformer, EntityRepositoryInterface $currencyRepository)
    {
        $this->transformer = $transformer;
        $this->currencyRepository = $currencyRepository;
    }

    public function transform(array $data): ModelInterface
    {
        /**
         * @var ShopModel
         */
        $model = $this->transformer->transform($data);

        $shopCurrencies = $this->currencyRepository->getCurrencyIdByShopId($model->id);
        $model->currencies = array_map(static fn($currencyId) => (int)$currencyId, array_column($shopCurrencies, 'id_currency'));

        return $model;
    }

}
