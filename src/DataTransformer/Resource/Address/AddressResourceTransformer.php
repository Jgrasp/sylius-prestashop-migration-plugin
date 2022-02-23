<?php
declare(strict_types=1);

namespace Jgrasp\PrestashopMigrationPlugin\DataTransformer\Resource\Address;

use App\Entity\Addressing\Country;
use Jgrasp\PrestashopMigrationPlugin\DataTransformer\Resource\ResourceTransformerInterface;
use Jgrasp\PrestashopMigrationPlugin\Model\Address\AddressModel;
use Jgrasp\PrestashopMigrationPlugin\Model\ModelInterface;
use Sylius\Component\Core\Model\AddressInterface;
use Sylius\Component\Core\Repository\CustomerRepositoryInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

class AddressResourceTransformer implements ResourceTransformerInterface
{
    private ResourceTransformerInterface $transformer;

    private CustomerRepositoryInterface $customerRepository;

    private RepositoryInterface $countryRepository;

    public function __construct(
        ResourceTransformerInterface $transformer,
        CustomerRepositoryInterface  $customerRepository,
        RepositoryInterface          $countryRepository
    )
    {
        $this->transformer = $transformer;
        $this->customerRepository = $customerRepository;
        $this->countryRepository = $countryRepository;
    }

    /**
     * @param AddressModel $model
     *
     * @return ResourceInterface
     */
    public function transform(ModelInterface $model): ?ResourceInterface
    {
        $customer = $this->customerRepository->findOneBy(['prestashopId' => $model->customerId]);

        if (null === $customer) {
            return null;
        }

        /**
         * @var AddressInterface $address
         */
        $address = $this->transformer->transform($model);
        /**
         * @var Country|null $country
         */
        $country = $this->countryRepository->findOneBy(['prestashopId' => $model->countryId]);

        if (null === $country) {
            return null;
        }

        if (null !== $model->address2) {
            $address->setStreet($address->getStreet().', '.$model->address2);
        }

        $address->setCountryCode($country->getCode());
        $address->setCustomer($customer);

        $phone = $model->phone;

        if (empty($phone)) {
            $phone = $model->phoneMobile;
        }

        $address->setPhoneNumber($phone);

        return $address;
    }

}
