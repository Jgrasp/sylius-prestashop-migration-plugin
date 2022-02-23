<?php
declare(strict_types=1);

namespace Jgrasp\PrestashopMigrationPlugin\Model\Employee;

use DateTime;
use Jgrasp\PrestashopMigrationPlugin\Attribute\Field;
use Jgrasp\PrestashopMigrationPlugin\Model\ModelInterface;
use Jgrasp\PrestashopMigrationPlugin\Model\ToggleableTrait;

class EmployeeModel implements ModelInterface
{
    use ToggleableTrait;

    #[Field(source: 'id_employee', target: 'prestashopId', id: true)]
    public int $id;

    #[Field(source: 'id_lang')]
    public int $langId;

    #[Field(source: 'email', target: 'email')]
    public string $email;

    #[Field(source: 'email', target: 'username')]
    public string $username;

    #[Field(source: 'firstname', target: 'firstname')]
    public string $firstname;

    #[Field(source: 'lastname', target: 'lastname')]
    public string $lastname;
}
