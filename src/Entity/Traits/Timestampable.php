<?php

namespace App\Entity\Traits;

use ORM\PrePersist;
use Doctrine\ORM\Mapping as ORM;

trait Timestampable
{
    #[ORM\Column(type: 'datetime_immutable', nullable: true, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: 'datetime_immutable', nullable: true, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private ?\DateTimeImmutable $updatedAt = null;
    

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function updateTimestamps(): void
    {
        if ($this->getCreatedAt() === null) {
            $this->setCreatedAt(new \DateTimeImmutable());
        }

        $this->setUpdatedAt(new \DateTimeImmutable());
    }
}