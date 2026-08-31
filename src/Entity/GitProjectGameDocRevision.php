<?php

namespace App\Entity;

use App\Enum\StatusEnum;
use App\Repository\GitProjectGameDocRevisionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GitProjectGameDocRevisionRepository::class)]
class GitProjectGameDocRevision
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

    #[ORM\Column(enumType: StatusEnum::class)]
    private StatusEnum $status = StatusEnum::IN_REVIEW;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $validated_at = null;

    #[ORM\ManyToOne(inversedBy: 'gitProjectGameDocRevisions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?GitProjectGameDoc $git_project_game_doc = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $created_by = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: true)]
    private ?User $validated_by = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

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

    public function getValidatedAt(): ?\DateTimeImmutable
    {
        return $this->validated_at;
    }

    public function setValidatedAt(?\DateTimeImmutable $validated_at): static
    {
        $this->validated_at = $validated_at;

        return $this;
    }

    public function getGitProjectGameDoc(): ?GitProjectGameDoc
    {
        return $this->git_project_game_doc;
    }

    public function setGitProjectGameDoc(GitProjectGameDoc $git_project_game_doc): static
    {
        $this->git_project_game_doc = $git_project_game_doc;

        return $this;
    }

    public function getCreatedBy(): ?User
    {
        return $this->created_by;
    }

    public function setCreatedBy(User $created_by): static
    {
        $this->created_by = $created_by;

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
}
