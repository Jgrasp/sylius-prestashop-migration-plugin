<?php
declare(strict_types=1);

namespace Jgrasp\PrestashopMigrationPlugin\DataTransformer\Resource\User;

use Jgrasp\PrestashopMigrationPlugin\DataTransformer\Resource\ResourceTransformerInterface;
use Jgrasp\PrestashopMigrationPlugin\Model\Customer\CustomerModel;
use Jgrasp\PrestashopMigrationPlugin\Model\ModelInterface;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Core\Model\ShopUserInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Model\ResourceInterface;

class ShopUserResourceTransformer implements ResourceTransformerInterface
{
    private ResourceTransformerInterface $transformer;

    private FactoryInterface $userFactory;

    public function __construct(ResourceTransformerInterface $transformer, FactoryInterface $userFactory)
    {
        $this->transformer = $transformer;
        $this->userFactory = $userFactory;
    }

    /**
     * @param CustomerModel $model
     *
     * @return ResourceInterface
     */
    public function transform(ModelInterface $model): ?ResourceInterface
    {
        /**
         * @var CustomerInterface $customer
         */
        $customer = $this->transformer->transform($model);
        $shopUser = $customer->getUser();

        if (null === $shopUser) {
            $shopUser = $this->userFactory->createNew();
        }

        $shopUser->setUsername($customer->getEmail());
        $shopUser->setEnabled($model->enabled);

        switch ($model->gender) {
            case 1:
                $gender = \Sylius\Component\Customer\Model\CustomerInterface::MALE_GENDER;
                break;
            case 2:
                $gender = \Sylius\Component\Customer\Model\CustomerInterface::FEMALE_GENDER;
                break;
            default:
                $gender = \Sylius\Component\Customer\Model\CustomerInterface::UNKNOWN_GENDER;
        }


        $customer->setGender($gender);
        $customer->setUser($shopUser);

        if (null !== $model->birthday && $model->birthday !== '0000-00-00') {
            $customer->setBirthday(\DateTime::createFromFormat('Y-m-d', $model->birthday));
        }


        return $customer;
    }

}
