<?php

namespace App\Entity;

use App\Repository\FileRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

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

    #[ORM\Column(length: 255)]
    private ?string $clientFilename = null;

    #[ORM\Column(length: 255)]
    private ?string $serverFilename = null;

    #[ORM\Column(length: 255)]
    private ?string $storagePath = null;

    #[ORM\Column(length: 64)]
    private ?string $batchUploadToken = null;

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

    public function getStoragePath(): ?string
    {
        return $this->storagePath;
    }

    public function setStoragePath(string $storagePath): static
    {
        $this->storagePath = $storagePath;

        return $this;
    }

    public function getBatchUploadToken(): ?string
    {
        return $this->batchUploadToken;
    }

    public function setBatchUploadToken(string $batchUploadToken): static
    {
        $this->batchUploadToken = $batchUploadToken;

        return $this;
    }
}
