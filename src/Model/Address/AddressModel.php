<?php
declare(strict_types=1);

namespace Jgrasp\PrestashopMigrationPlugin\Model\Address;

use Jgrasp\PrestashopMigrationPlugin\Attribute\Field;
use Jgrasp\PrestashopMigrationPlugin\Model\ModelInterface;

class AddressModel implements ModelInterface
{
    #[Field(source: 'id_address', target: 'prestashopId', id: true)]
    public int $id;

    #[Field(source: 'firstname', target: 'firstname')]
    public string $firstname;

    #[Field(source: 'lastname', target: 'lastname')]
    public string $lastname;

    #[Field(source: 'address1', target: 'street')]
    public string $street;

    #[Field(source: 'address2')]
    public ?string $address2;

    #[Field(source: 'city', target: 'city')]
    public string $city;

    #[Field(source: 'postcode', target: 'postcode')]
    public string $postcode;

    #[Field(source: 'company', target: 'company')]
    public ?string $company;

    #[Field(source: 'phone')]
    public ?string $phone;

    #[Field(source: 'phone_mobile')]
    public ?string $phoneMobile;

    #[Field(source: 'id_customer')]
    public int $customerId;

    #[Field(source: 'id_country')]
    public int $countryId;
}
