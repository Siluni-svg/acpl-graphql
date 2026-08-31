<?php

namespace App\Entity;

use App\Repository\DiscordInviteRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DiscordInviteRepository::class)]
class DiscordInvite
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $url = null;

    #[ORM\Column(length: 255)]
    private ?string $code = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $guild_id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $name = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $icon = null;

    /** @var list<string> */
    #[ORM\Column(type: 'json')]
    private array $traits = [];

    #[ORM\Column(nullable: true)]
    private ?int $approximate_member_count = null;

    #[ORM\Column(nullable: true)]
    private ?int $approximate_presence_count = null;

    #[ORM\ManyToOne]
    private ?User $submitted_by = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $synced_at = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): static
    {
        $this->url = $url;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): static
    {
        $this->code = $code;

        return $this;
    }

    public function getGuildId(): ?string
    {
        return $this->guild_id;
    }

    public function setGuildId(?string $guild_id): static
    {
        $this->guild_id = $guild_id;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;

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

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function setIcon(?string $icon): static
    {
        $this->icon = $icon;

        return $this;
    }

    /** @return list<string> */
    public function getTraits(): array
    {
        return $this->traits;
    }

    /** @param list<string> $traits */
    public function setTraits(array $traits): static
    {
        $this->traits = $traits;

        return $this;
    }

    public function getApproximateMemberCount(): ?int
    {
        return $this->approximate_member_count;
    }

    public function setApproximateMemberCount(?int $approximate_member_count): static
    {
        $this->approximate_member_count = $approximate_member_count;

        return $this;
    }

    public function getApproximatePresenceCount(): ?int
    {
        return $this->approximate_presence_count;
    }

    public function setApproximatePresenceCount(?int $approximate_presence_count): static
    {
        $this->approximate_presence_count = $approximate_presence_count;

        return $this;
    }

    public function getSubmittedBy(): ?User
    {
        return $this->submitted_by;
    }

    public function setSubmittedBy(?User $submitted_by): static
    {
        $this->submitted_by = $submitted_by;

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
