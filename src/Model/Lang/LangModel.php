<?php

namespace Jgrasp\PrestashopMigrationPlugin\Model\Lang;

use Jgrasp\PrestashopMigrationPlugin\Attribute\Field;
use Jgrasp\PrestashopMigrationPlugin\Model\ModelInterface;

class LangModel implements ModelInterface
{
    #[Field(source: 'id_lang', target: 'prestashopId', id: true)]
    public int $id;

    #[Field(source: 'locale', target: 'code')]
    public string $code;

}
