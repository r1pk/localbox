<?php

namespace App\Entity;

use App\Repository\FileRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: FileRepository::class)]
class File
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 64)]
    private ?string $token = null;

    #[ORM\Column(length: 64)]
    private ?string $groupToken = null;

    #[ORM\Column(length: 255)]
    private ?string $clientFilename = null;

    #[ORM\Column(length: 255)]
    private ?string $serverFilename = null;

    #[ORM\Column]
    private ?int $size = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(string $token): static
    {
        $this->token = $token;

        return $this;
    }

    public function getGroupToken(): ?string
    {
        return $this->groupToken;
    }

    public function setGroupToken(string $groupToken): static
    {
        $this->groupToken = $groupToken;

        return $this;
    }

    public function getClientFilename(): ?string
    {
        return $this->clientFilename;
    }

    public function setClientFilename(string $clientFilename): static
    {
        $this->clientFilename = $clientFilename;

        return $this;
    }

    public function getServerFilename(): ?string
    {
        return $this->serverFilename;
    }

    public function setServerFilename(string $serverFilename): static
    {
        $this->serverFilename = $serverFilename;

        return $this;
    }

    public function getSize(): ?int
    {
        return $this->size;
    }

    public function setSize(int $size): static
    {
        $this->size = $size;

        return $this;
    }

    public static function prepareForUploadedFile(UploadedFile $file, string $groupToken): static
    {
        $entity = new self();

        $entity->setToken(Uuid::v4()->toRfc4122());
        $entity->setGroupToken($groupToken);

        $entity->setServerFilename(Uuid::v4()->toRfc4122());
        $entity->setClientFilename($file->getClientOriginalName());
        $entity->setSize($file->getSize());

        return $entity;
    }
}
