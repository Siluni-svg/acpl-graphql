<?php

namespace App\Entity;

use App\Repository\GuideRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GuideRepository::class)]
class Guide
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $slug = null;

    #[ORM\Column(length: 10)]
    private ?string $language = null;

    /** @var Collection<int, GuideRevision> */
    #[ORM\OneToMany(targetEntity: GuideRevision::class, mappedBy: 'guide', cascade: ['persist'])]
    private Collection $guideRevisions;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: 'published_revision_id', nullable: true, onDelete: 'SET NULL')]
    private ?GuideRevision $published_revision = null;

    public function __construct()
    {
        $this->guideRevisions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

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

    /** @return Collection<int, GuideRevision> */
    public function getGuideRevisions(): Collection
    {
        return $this->guideRevisions;
    }

    public function addGuideRevision(GuideRevision $guideRevision): static
    {
        if (!$this->guideRevisions->contains($guideRevision)) {
            $this->guideRevisions->add($guideRevision);
            $guideRevision->setGuide($this);
        }

        return $this;
    }

    public function getPublishedRevision(): ?GuideRevision
    {
        return $this->published_revision;
    }

    public function setPublishedRevision(?GuideRevision $published_revision): static
    {
        $this->published_revision = $published_revision;

        return $this;
    }
}
