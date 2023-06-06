<?php

namespace App\Entity;

use App\Repository\MusicRepository;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MusicRepository::class)]
class Music
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $artist = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private DateTimeImmutable $date;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $text = null;

    public function __construct()
    {
        $this->date = new DateTimeImmutable('now');
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getArtist(): ?string
    {
        return $this->artist;
    }

    public function setArtist(?string $artist): self
    {
        $this->artist = $artist;

        return $this;
    }

    public function getDate(): DateTimeImmutable
    {
        return $this->date;
    }

    public function getDateString(): string
    {
        return $this->date->format('d.m.Y H:i');
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(?string $text): self
    {
        $this->text = $text;

        return $this;
    }
    public function getFile(): ?string
    {
        return $this->text;
    }

    public function fileExist(): bool
    {
        return is_file('uploads/'. $this->getFile());
    }

    public function removeFile(): void
    {
        if ($this->fileExist()) {
            unlink('uploads/' . $this->getFile());
        }
    }

    public function setFile(?string $text): self
    {
        $this->text = $text;

        return $this;
    }
}
