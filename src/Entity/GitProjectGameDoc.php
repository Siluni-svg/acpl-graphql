<?php

namespace App\Entity;

use App\Repository\GitProjectGameDocRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GitProjectGameDocRepository::class)]
class GitProjectGameDoc
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'gitProjectGameDocs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?GitProjectGame $git_project_game = null;

    #[ORM\Column(length: 10)]
    private ?string $language = null;

    /** @var Collection<int, GitProjectGameDocRevision> */
    #[ORM\OneToMany(targetEntity: GitProjectGameDocRevision::class, mappedBy: 'git_project_game_doc', cascade: ['persist'])]
    private Collection $gitProjectGameDocRevisions;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: 'published_revision_id', nullable: true, onDelete: 'SET NULL')]
    private ?GitProjectGameDocRevision $published_revision = null;

    public function __construct()
    {
        $this->gitProjectGameDocRevisions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGitProjectGame(): ?GitProjectGame
    {
        return $this->git_project_game;
    }

    public function setGitProjectGame(?GitProjectGame $git_project_game): static
    {
        $this->git_project_game = $git_project_game;

        return $this;
    }

    public function getLanguage(): ?string
    {
        return $this->language;
    }

    public function setLanguage(string $language): static
    {
        $this->language = $language;

        return $this;
    }

    /** @return Collection<int, GitProjectGameDocRevision> */
    public function getGitProjectGameDocRevisions(): Collection
    {
        return $this->gitProjectGameDocRevisions;
    }

    public function addGitProjectGameDocRevision(GitProjectGameDocRevision $gitProjectGameDocRevision): static
    {
        if (!$this->gitProjectGameDocRevisions->contains($gitProjectGameDocRevision)) {
            $this->gitProjectGameDocRevisions->add($gitProjectGameDocRevision);
            $gitProjectGameDocRevision->setGitProjectGameDoc($this);
        }

        return $this;
    }

    public function getPublishedRevision(): ?GitProjectGameDocRevision
    {
        return $this->published_revision;
    }

    public function setPublishedRevision(?GitProjectGameDocRevision $published_revision): static
    {
        $this->published_revision = $published_revision;

        return $this;
    }
}
