<?php

namespace App\Entity;

use App\Repository\GitProjectReleaseRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GitProjectReleaseRepository::class)]
class GitProjectRelease
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'gitProjectReleases')]
    #[ORM\JoinColumn(nullable: false)]
    private ?GitProject $git_project = null;

    #[ORM\Column(length: 255)]
    private ?string $release = null;

    #[ORM\Column(length: 255)]
    private ?string $tag = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $published_at = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getRelease(): ?string
    {
        return $this->release;
    }

    public function setRelease(string $release): static
    {
        $this->release = $release;

        return $this;
    }

    public function getTag(): ?string
    {
        return $this->tag;
    }

    public function setTag(string $tag): static
    {
        $this->tag = $tag;

        return $this;
    }

    public function getPublishedAt(): ?\DateTimeImmutable
    {
        return $this->published_at;
    }

    public function setPublishedAt(\DateTimeImmutable $published_at): static
    {
        $this->published_at = $published_at;

        return $this;
    }

    /**
     * Whether this release's major version looks stable (1.x and above),
     * as opposed to a pre-1.0 release (0.x), which is considered beta.
     */
    public function isStable(): bool
    {
        $major = $this->extractMajorVersion($this->tag) ?? $this->extractMajorVersion($this->release);

        return null === $major || $major >= 1;
    }

    private function extractMajorVersion(?string $value): ?int
    {
        if (null === $value || 1 !== preg_match('/(?<![\d.])v?(\d+)\.\d+/i', $value, $matches)) {
            return null;
        }

        return (int) $matches[1];
    }
}
