<?php

namespace App\Entity;

use App\Entity\Traits\Timestampable;
use App\Repository\PinRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: PinRepository::class)]
#[ORM\Table(name: "pins")]
#[ORM\HasLifecycleCallbacks]
class Pin
{

    use Timestampable;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"il faut un titre")]
    #[Assert\Length(min: 3)]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 10, minMessage: 'La description doit contenir au moins 10 caractères')]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imageName = null;



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }



    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function updateTimestamp()
    {
        if($this->getCreatedAt()===null)
        {
            $this->setCreatedAt(new DateTimeImmutable());
        }
        $this->setUpdatedAt(new DateTimeImmutable());

    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function setImageName(?string $imageName): static
    {
        $this->imageName = $imageName;

        return $this;
    }

}
