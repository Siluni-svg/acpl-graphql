<?php

namespace App\Entity;

use App\Enum\StreamPlatformEnum;
use App\Repository\StreamAccountRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StreamAccountRepository::class)]
#[ORM\UniqueConstraint(name: 'uq_stream_account_user_platform', fields: ['user', 'platform'])]
class StreamAccount
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(enumType: StreamPlatformEnum::class)]
    private ?StreamPlatformEnum $platform = null;

    #[ORM\Column(length: 255)]
    private ?string $handle = null;

    #[ORM\Column]
    private bool $is_live = false;

    #[ORM\Column]
    private bool $is_archipelago_tagged = false;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $title = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $thumbnail = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $stream_url = null;

    #[ORM\Column(nullable: true)]
    private ?int $viewer_count = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $synced_at = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getPlatform(): ?StreamPlatformEnum
    {
        return $this->platform;
    }

    public function setPlatform(StreamPlatformEnum $platform): static
    {
        $this->platform = $platform;

        return $this;
    }

    public function getHandle(): ?string
    {
        return $this->handle;
    }

    public function setHandle(string $handle): static
    {
        $this->handle = $handle;

        return $this;
    }

    public function isLive(): bool
    {
        return $this->is_live;
    }

    public function setIsLive(bool $is_live): static
    {
        $this->is_live = $is_live;

        return $this;
    }

    public function isArchipelagoTagged(): bool
    {
        return $this->is_archipelago_tagged;
    }

    public function setIsArchipelagoTagged(bool $is_archipelago_tagged): static
    {
        $this->is_archipelago_tagged = $is_archipelago_tagged;

        return $this;
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

    public function getThumbnail(): ?string
    {
        return $this->thumbnail;
    }

    public function setThumbnail(?string $thumbnail): static
    {
        $this->thumbnail = $thumbnail;

        return $this;
    }

    public function getStreamUrl(): ?string
    {
        return $this->stream_url;
    }

    public function setStreamUrl(?string $stream_url): static
    {
        $this->stream_url = $stream_url;

        return $this;
    }

    public function getViewerCount(): ?int
    {
        return $this->viewer_count;
    }

    public function setViewerCount(?int $viewer_count): static
    {
        $this->viewer_count = $viewer_count;

        return $this;
    }

    public function getSyncedAt(): ?\DateTimeImmutable
    {
        return $this->synced_at;
    }

    public function setSyncedAt(?\DateTimeImmutable $synced_at): static
    {
        $this->synced_at = $synced_at;

        return $this;
    }
}
