<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Overblog\GraphQLBundle\Annotation as GQL;
use Symfony\Component\Security\Core\User\UserInterface;

#[GQL\Type]
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_UUID', fields: ['uuid'])]
class User implements UserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[GQL\Field()]
    #[ORM\Column(length: 180)]
    private ?string $uuid = null;

    /** @var list<string> The user roles */
    #[ORM\Column]
    private array $roles = [];

    #[ORM\Column(length: 255, unique: true)]
    private ?string $discord_id = null;

    #[ORM\Column(length: 255)]
    private ?string $discord_username = null;

    #[ORM\Column(length: 255)]
    private ?string $discord_email = null;

    #[ORM\Column(length: 255)]
    private ?string $discord_avatar = null;

    #[ORM\Column(length: 5)]
    private ?string $locale = null;

    #[ORM\Column]
    private ?bool $enabled = null;

    #[ORM\ManyToOne(targetEntity: self::class)]
    private ?self $banned_by = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $banned_at = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $banned_reason = null;

    #[ORM\Column]
    private ?\DateTime $last_connected_at = null;

    /**
     * @var Collection<int, GitProjectGame>
     */
    #[ORM\ManyToMany(targetEntity: GitProjectGame::class, inversedBy: 'watchedByUsers')]
    #[ORM\JoinTable(name: 'user_watched_git_project_game')]
    private Collection $watchedGitProjectGames;

    public function __construct()
    {
        $this->watchedGitProjectGames = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    public function setUuid(string $uuid): static
    {
        $this->uuid = $uuid;

        return $this;
    }

    /** @see UserInterface */
    public function getUserIdentifier(): string
    {
        return (string) $this->uuid;
    }

    /** @see UserInterface */
    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    public function getDiscordId(): ?string
    {
        return $this->discord_id;
    }

    public function setDiscordId(string $discord_id): static
    {
        $this->discord_id = $discord_id;

        return $this;
    }

    public function getDiscordUsername(): ?string
    {
        return $this->discord_username;
    }

    public function setDiscordUsername(string $discord_username): static
    {
        $this->discord_username = $discord_username;

        return $this;
    }

    public function getDiscordEmail(): ?string
    {
        return $this->discord_email;
    }

    public function setDiscordEmail(string $discord_email): static
    {
        $this->discord_email = $discord_email;

        return $this;
    }

    public function getDiscordAvatar(): ?string
    {
        return $this->discord_avatar;
    }

    public function setDiscordAvatar(string $discord_avatar): static
    {
        $this->discord_avatar = $discord_avatar;

        return $this;
    }

    public function getLocale(): ?string
    {
        return $this->locale;
    }

    public function setLocale(string $locale): static
    {
        $this->locale = $locale;

        return $this;
    }

    public function isEnabled(): ?bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): static
    {
        $this->enabled = $enabled;

        return $this;
    }

    public function getBannedBy(): ?self
    {
        return $this->banned_by;
    }

    public function setBannedBy(?self $banned_by): static
    {
        $this->banned_by = $banned_by;

        return $this;
    }

    public function getBannedAt(): ?\DateTimeImmutable
    {
        return $this->banned_at;
    }

    public function setBannedAt(?\DateTimeImmutable $banned_at): static
    {
        $this->banned_at = $banned_at;

        return $this;
    }

    public function getBannedReason(): ?string
    {
        return $this->banned_reason;
    }

    public function setBannedReason(?string $banned_reason): static
    {
        $this->banned_reason = $banned_reason;

        return $this;
    }

    public function getLastConnectedAt(): ?\DateTime
    {
        return $this->last_connected_at;
    }

    public function setLastConnectedAt(\DateTime $last_connected_at): static
    {
        $this->last_connected_at = $last_connected_at;

        return $this;
    }

    /**
     * @return Collection<int, GitProjectGame>
     */
    public function getWatchedGitProjectGames(): Collection
    {
        return $this->watchedGitProjectGames;
    }

    public function addWatchedGitProjectGame(GitProjectGame $gitProjectGame): static
    {
        if (!$this->watchedGitProjectGames->contains($gitProjectGame)) {
            $this->watchedGitProjectGames->add($gitProjectGame);
        }

        return $this;
    }

    public function removeWatchedGitProjectGame(GitProjectGame $gitProjectGame): static
    {
        $this->watchedGitProjectGames->removeElement($gitProjectGame);

        return $this;
    }
}
