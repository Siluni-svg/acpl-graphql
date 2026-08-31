<?php

namespace App\Entity;

use App\Enum\StatusEnum;
use App\Repository\GitProjectGameRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;

#[HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: GitProjectGameRepository::class)]
class GitProjectGame
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'gitProjectGames')]
    #[ORM\JoinColumn(nullable: false)]
    private ?GitProject $git_project = null;

    #[ORM\ManyToOne(inversedBy: 'gitProjectGames')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Game $game = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $slug = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $tag_regex = null;

    #[ORM\Column(length: 20, enumType: StatusEnum::class)]
    private StatusEnum $status = StatusEnum::IN_REVIEW;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
    private ?User $created_by = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $validated_at = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
    private ?User $validated_by = null;

    /**
     * @var Collection<int, GitProjectGameDoc>
     */
    #[ORM\OneToMany(targetEntity: GitProjectGameDoc::class, mappedBy: 'git_project_game')]
    private Collection $gitProjectGameDocs;

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'watchedGitProjectGames')]
    private Collection $watchedByUsers;

    public function __construct()
    {
        $this->gitProjectGameDocs = new ArrayCollection();
        $this->watchedByUsers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, GitProjectRelease>
     */
    public function getGitProjectReleases(): Collection
    {
        $releases = $this->getGitProject()
            ->getGitProjectReleases()
            ->filter(function ($release) {
                return preg_match($this->getTagRegex() ?? '/.*/', $release->getRelease());
            })
            ->matching(Criteria::create()->orderBy(['publishedAt' => \Doctrine\Common\Collections\Order::Descending]));

        return $releases;
    }

    public function getGitProject(): ?GitProject
    {
        return $this->git_project;
    }

    public function setGitProject(?GitProject $git_project): static
    {
        $this->git_project = $git_project;

        return $this;
    }

    public function getGame(): ?Game
    {
        return $this->game;
    }

    public function setGame(?Game $game): static
    {
        $this->game = $game;

        return $this;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getTagRegex(): ?string
    {
        return $this->tag_regex;
    }

    public function setTagRegex(?string $tag_regex): static
    {
        $this->tag_regex = $tag_regex;

        return $this;
    }

    public function getStatus(): StatusEnum
    {
        return $this->status;
    }

    public function setStatus(StatusEnum $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getCreatedBy(): ?User
    {
        return $this->created_by;
    }

    public function setCreatedBy(?User $created_by): static
    {
        $this->created_by = $created_by;

        return $this;
    }

    public function getValidatedAt(): ?\DateTimeImmutable
    {
        return $this->validated_at;
    }

    public function setValidatedAt(?\DateTimeImmutable $validated_at): static
    {
        $this->validated_at = $validated_at;

        return $this;
    }

    public function getValidatedBy(): ?User
    {
        return $this->validated_by;
    }

    public function setValidatedBy(?User $validated_by): static
    {
        $this->validated_by = $validated_by;

        return $this;
    }

    /**
     * @return Collection<int, GitProjectGameDoc>
     */
    public function getGitProjectGameDocs(): Collection
    {
        return $this->gitProjectGameDocs;
    }

    public function addGitProjectGameDoc(GitProjectGameDoc $gitProjectGameDoc): static
    {
        if (!$this->gitProjectGameDocs->contains($gitProjectGameDoc)) {
            $this->gitProjectGameDocs->add($gitProjectGameDoc);
            $gitProjectGameDoc->setGitProjectGame($this);
        }

        return $this;
    }

    public function removeGitProjectGameDoc(GitProjectGameDoc $gitProjectGameDoc): static
    {
        if ($this->gitProjectGameDocs->removeElement($gitProjectGameDoc)) {
            // set the owning side to null (unless already changed)
            if ($gitProjectGameDoc->getGitProjectGame() === $this) {
                $gitProjectGameDoc->setGitProjectGame(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getWatchedByUsers(): Collection
    {
        return $this->watchedByUsers;
    }
}
