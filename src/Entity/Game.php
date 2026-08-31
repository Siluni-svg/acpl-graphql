<?php

namespace App\Entity;

use App\Repository\GameRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GameRepository::class)]
class Game
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * @var Collection<int, GitProjectGame>
     */
    #[ORM\OneToMany(targetEntity: GitProjectGame::class, mappedBy: 'game')]
    private Collection $gitProjectGames;

    #[ORM\Column(nullable: true)]
    private ?int $pegi = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $igdb_id = null;

    #[ORM\Column(length: 255)]
    private ?string $cover_image = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $synopsis = null;

    #[ORM\Column]
    private ?bool $restricted = null;

    /**
     * @var Collection<int, Genre>
     */
    #[ORM\ManyToMany(targetEntity: Genre::class)]
    #[ORM\JoinTable(name: 'game_genre')]
    private Collection $genres;

    /**
     * @var Collection<int, Theme>
     */
    #[ORM\ManyToMany(targetEntity: Theme::class)]
    #[ORM\JoinTable(name: 'game_theme')]
    private Collection $themes;

    public function __construct()
    {
        $this->gitProjectGames = new ArrayCollection();
        $this->genres = new ArrayCollection();
        $this->themes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, GitProjectGame>
     */
    public function getGitProjectGames(): Collection
    {
        return $this->gitProjectGames;
    }

    public function addGitProjectGame(GitProjectGame $gitProjectGame): static
    {
        if (!$this->gitProjectGames->contains($gitProjectGame)) {
            $this->gitProjectGames->add($gitProjectGame);
            $gitProjectGame->setGame($this);
        }

        return $this;
    }

    public function removeGitProjectGame(GitProjectGame $gitProjectGame): static
    {
        if ($this->gitProjectGames->removeElement($gitProjectGame)) {
            // set the owning side to null (unless already changed)
            if ($gitProjectGame->getGame() === $this) {
                $gitProjectGame->setGame(null);
            }
        }

        return $this;
    }

    public function getPegi(): ?int
    {
        return $this->pegi;
    }

    public function setPegi(?int $pegi): static
    {
        $this->pegi = $pegi;

        return $this;
    }

    public function getIgdbId(): ?int
    {
        return $this->igdb_id;
    }

    public function setIgdbId(?int $igdb_id): static
    {
        $this->igdb_id = $igdb_id;

        return $this;
    }

    public function getCoverImage(): ?string
    {
        return $this->cover_image;
    }

    public function setCoverImage(string $cover_image): static
    {
        $this->cover_image = $cover_image;

        return $this;
    }

    public function getSynopsis(): ?string
    {
        return $this->synopsis;
    }

    public function setSynopsis(?string $synopsis): static
    {
        $this->synopsis = $synopsis;

        return $this;
    }

    public function isRestricted(): ?bool
    {
        return $this->restricted;
    }

    public function setRestricted(bool $restricted): static
    {
        $this->restricted = $restricted;

        return $this;
    }

    /**
     * @return Collection<int, Genre>
     */
    public function getGenres(): Collection
    {
        return $this->genres;
    }

    public function addGenre(Genre $genre): static
    {
        if (!$this->genres->contains($genre)) {
            $this->genres->add($genre);
        }

        return $this;
    }

    public function removeGenre(Genre $genre): static
    {
        $this->genres->removeElement($genre);

        return $this;
    }

    /**
     * @return Collection<int, Theme>
     */
    public function getThemes(): Collection
    {
        return $this->themes;
    }

    public function addTheme(Theme $theme): static
    {
        if (!$this->themes->contains($theme)) {
            $this->themes->add($theme);
        }

        return $this;
    }

    public function removeTheme(Theme $theme): static
    {
        $this->themes->removeElement($theme);

        return $this;
    }
}
