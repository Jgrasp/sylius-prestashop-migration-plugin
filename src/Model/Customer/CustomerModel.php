<?php
declare(strict_types=1);

namespace Jgrasp\PrestashopMigrationPlugin\Model\Customer;

use Jgrasp\PrestashopMigrationPlugin\Attribute\Field;
use Jgrasp\PrestashopMigrationPlugin\Model\ModelInterface;

class CustomerModel implements ModelInterface
{
    #[Field(source: 'id_customer', target: 'prestashopId', id: true)]
    public int $id;

    #[Field(source: 'firstname', target: 'firstname')]
    public string $lastname;

    #[Field(source: 'lastname', target: 'lastname')]
    public string $firstname;

    #[Field(source: 'email', target: 'email')]
    public string $email;

    #[Field(source: 'active')]
    public bool $enabled;

    #[Field(source: 'id_gender')]
    public int $gender;

    #[Field(source: 'birthday')]
    public ?string $birthday;

    #[Field(source: 'newsletter', target: 'subscribedToNewsletter')]
    public bool $newsletter;
}
