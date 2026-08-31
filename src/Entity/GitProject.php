<?php

namespace App\Entity;

use App\Repository\GitProjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GitProjectRepository::class)]
class GitProject
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var Collection<int, GitProjectGame>
     */
    #[ORM\OneToMany(targetEntity: GitProjectGame::class, mappedBy: 'git_project')]
    private Collection $gitProjectGames;

    /**
     * @var Collection<int, GitProjectRelease>
     */
    #[ORM\OneToMany(targetEntity: GitProjectRelease::class, mappedBy: 'git_project')]
    private Collection $gitProjectReleases;

    #[ORM\Column(length: 255)]
    private ?string $url = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $last_checked_at = null;

    public function __construct()
    {
        $this->gitProjectGames = new ArrayCollection();
        $this->gitProjectReleases = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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
            $gitProjectGame->setGitProject($this);
        }

        return $this;
    }

    public function removeGitProjectGame(GitProjectGame $gitProjectGame): static
    {
        if ($this->gitProjectGames->removeElement($gitProjectGame)) {
            // set the owning side to null (unless already changed)
            if ($gitProjectGame->getGitProject() === $this) {
                $gitProjectGame->setGitProject(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, GitProjectRelease>
     */
    public function getGitProjectReleases(): Collection
    {
        return $this->gitProjectReleases;
    }

    public function addGitProjectRelease(GitProjectRelease $gitProjectRelease): static
    {
        if (!$this->gitProjectReleases->contains($gitProjectRelease)) {
            $this->gitProjectReleases->add($gitProjectRelease);
            $gitProjectRelease->setGitProject($this);
        }

        return $this;
    }

    public function removeGitProjectRelease(GitProjectRelease $gitProjectRelease): static
    {
        if ($this->gitProjectReleases->removeElement($gitProjectRelease)) {
            // set the owning side to null (unless already changed)
            if ($gitProjectRelease->getGitProject() === $this) {
                $gitProjectRelease->setGitProject(null);
            }
        }

        return $this;
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

    public function getOwner(): string
    {
        $path = parse_url($this->url, PHP_URL_PATH);
        $parts = array_values(array_filter(explode('/', $path)));

        return $parts[count($parts) - 2];
    }

    public function getRepo(): string
    {
        $path = parse_url($this->url, PHP_URL_PATH);
        $parts = array_values(array_filter(explode('/', $path)));

        return $parts[count($parts) - 1];
    }

    public function getLastCheckedAt(): ?\DateTime
    {
        return $this->last_checked_at;
    }

    public function setLastCheckedAt(?\DateTime $last_checked_at): static
    {
        $this->last_checked_at = $last_checked_at;

        return $this;
    }
}
