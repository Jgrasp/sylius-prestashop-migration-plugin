<?php

namespace Jgrasp\PrestashopMigrationPlugin\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

trait PrestashopTrait
{
    /**
     * @ORM\Column(name="prestashop_id", type="integer", nullable=true)
     */
    protected ?int $prestashopId = null;

    /**
     * @ORM\Column(name="prestashop_created_at", type="datetime", nullable=true)
     */
    protected ?DateTime $prestashopCreatedAt;

    /**
     * @ORM\Column(name="prestashop_updated_at", type="datetime", nullable=true)
     */
    protected ?DateTime $prestashopUpdatedAt;

    public function getPrestashopId(): ?int
    {
        return $this->prestashopId;
    }

    public function setPrestashopId(?int $prestashopId): void
    {
        $this->prestashopId = $prestashopId;
    }

    public function getPrestashopCreatedAt(): ?\DateTimeInterface
    {
        return $this->importCreatedAt;
    }

    public function setPrestashopCreatedAt(?\DateTimeInterface $prestashopCreatedAt): void
    {
        $this->importCreatedAt = $prestashopCreatedAt;
    }

    public function getPrestashopUpdatedAt(): ?\DateTimeInterface
    {
        return $this->importUpdatedAt;
    }

    public function setPrestashopUpdatedAt(?\DateTimeInterface $prestashopUpdatedAt): void
    {
        $this->importUpdatedAt = $prestashopUpdatedAt;
    }
}
