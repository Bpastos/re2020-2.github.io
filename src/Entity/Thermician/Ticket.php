<?php

declare(strict_types=1);

namespace App\Entity\Thermician;

use App\Entity\Project\Project;
use App\Repository\Thermician\TicketRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TicketRepository::class)]
class Ticket
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'ticket', targetEntity: Project::class, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private Project $project;

    #[ORM\OneToOne(inversedBy: 'activeTicket', targetEntity: Thermician::class, cascade: ['persist', 'remove'])]
    private ?Thermician $activeThermician;

    #[ORM\Column(type: 'datetime_immutable')]
    private DateTimeImmutable $createdAt;

    #[ORM\Column(type: 'boolean')]
    private bool $isActive = true;

    #[ORM\ManyToOne(targetEntity: Thermician::class, inversedBy: 'pendingTicket')]
    private ?Thermician $oldThermician;

    /**
     * @var Collection<Document>
     */
    #[ORM\OneToMany(mappedBy: 'ticket', targetEntity: Document::class)]
    private Collection $documents;

    #[ORM\ManyToOne(targetEntity: Thermician::class, inversedBy: 'finishedTickets')]
    private ?Thermician $finishedThermician;

    public function __construct()
    {
        $this->createdAt = new DateTimeImmutable();
        $this->documents = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProject(): ?Project
    {
        return $this->project;
    }

    public function setProject(Project $project): self
    {
        $this->project = $project;

        return $this;
    }

    public function getActiveThermician(): ?Thermician
    {
        return $this->activeThermician;
    }

    public function setActiveThermician(null|Thermician $activeThermician): self
    {
        $this->activeThermician = $activeThermician;

        return $this;
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getOldThermician(): ?Thermician
    {
        return $this->oldThermician;
    }

    public function setOldThermician(?Thermician $oldThermician): self
    {
        $this->oldThermician = $oldThermician;

        return $this;
    }

    public function getDocuments(): ?Collection
    {
        return $this->documents;
    }

    public function addDocument(Document $document): self
    {
        if (!$this->documents->contains($document)) {
            $this->documents[] = $document;
            $document->setTicket($this);
        }

        return $this;
    }

//    public function removeDocument(Document $document): self
//    {
//        if ($this->documents->removeElement($document)) {
//            // set the owning side to null (unless already changed)
//            if ($document->getTicket() === $this) {
//                $document->setTicket(null);
//            }
//        }
//
//        return $this;
//    }

    public function getFinishedThermician(): ?Thermician
    {
        return $this->finishedThermician;
    }

    public function setFinishedThermician(?Thermician $finishedThermician): self
    {
        $this->finishedThermician = $finishedThermician;

        return $this;
    }
}
