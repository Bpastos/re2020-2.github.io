<?php

declare(strict_types=1);

namespace App\Entity\Project;

use App\Repository\Project\BuildingRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BuildingRepository::class)]
class Building
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    // Surface plancher (m²)
    #[ORM\Column(type: 'text', length: 255)]
    private string $floorArea;

    // Surface habitable (m²)
    #[ORM\Column(type: 'text', length: 255)]
    private string $livingArea;

    // Surface plancher existant
    #[ORM\Column(type: 'string', length: 255)]
    private string $existingFloorArea;

    // Plancher bas
    #[ORM\Column(type: 'text', length: 255)]
    private string $lowFloor;

    // Plancher bas Traitement des ponts thermiques
    #[ORM\Column(type: 'string', length: 255)]
    private string $lowFloorThermal;

    // Plancher haut
    #[ORM\Column(type: 'text', length: 255)]
    private string $highFloor;

    // Plancher haut Traitement des ponts thermiques
    #[ORM\Column(type: 'string', length: 255)]
    private string $highFloorThermal;

    // Plancher intermédiaire
    #[ORM\Column(type: 'text')]
    private string $intermediateFloor;

    // Plancher intermédiaire Traitement des ponts thermiques
    #[ORM\Column(type: 'string', length: 255)]
    private string $intermediateFloorThermal;

    // Façades
    #[ORM\Column(type: 'text')]
    private string $facades;

    // Parois particulières
    #[ORM\Column(type: 'text')]
    private string $particularWalls;

    #[ORM\Column(type: 'datetime_immutable')]
    private DateTimeImmutable $createdAt;

    #[ORM\OneToOne(inversedBy: 'building', targetEntity: Project::class, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private Project $project;

    /**
     * @var Collection<Plan>
     */
    #[ORM\OneToMany(mappedBy: 'building', targetEntity: Plan::class)]
    private Collection $plan;

    public function __construct()
    {
        $this->createdAt = new DateTimeImmutable();
        $this->plan = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFloorArea(): ?string
    {
        return $this->floorArea;
    }

    public function setFloorArea(string $floorArea): self
    {
        $this->floorArea = $floorArea;

        return $this;
    }

    public function getLivingArea(): ?string
    {
        return $this->livingArea;
    }

    public function setLivingArea(string $livingArea): self
    {
        $this->livingArea = $livingArea;

        return $this;
    }

    public function getExistingFloorArea(): ?string
    {
        return $this->existingFloorArea;
    }

    public function setExistingFloorArea(string $existingFloorArea): self
    {
        $this->existingFloorArea = $existingFloorArea;

        return $this;
    }

    public function getLowFloor(): ?string
    {
        return $this->lowFloor;
    }

    public function setLowFloor(string $lowFloor): self
    {
        $this->lowFloor = $lowFloor;

        return $this;
    }

    public function getLowFloorThermal(): ?string
    {
        return $this->lowFloorThermal;
    }

    public function setLowFloorThermal(string $lowFloorThermal): self
    {
        $this->lowFloorThermal = $lowFloorThermal;

        return $this;
    }

    public function getHighFloor(): ?string
    {
        return $this->highFloor;
    }

    public function setHighFloor(string $highFloor): self
    {
        $this->highFloor = $highFloor;

        return $this;
    }

    public function getHighFloorThermal(): ?string
    {
        return $this->highFloorThermal;
    }

    public function setHighFloorThermal(string $highFloorThermal): self
    {
        $this->highFloorThermal = $highFloorThermal;

        return $this;
    }

    public function getIntermediateFloor(): ?string
    {
        return $this->intermediateFloor;
    }

    public function setIntermediateFloor(string $intermediateFloor): self
    {
        $this->intermediateFloor = $intermediateFloor;

        return $this;
    }

    public function getIntermediateFloorThermal(): ?string
    {
        return $this->intermediateFloorThermal;
    }

    public function setIntermediateFloorThermal(string $intermediateFloorThermal): self
    {
        $this->intermediateFloorThermal = $intermediateFloorThermal;

        return $this;
    }

    public function getFacades(): ?string
    {
        return $this->facades;
    }

    public function setFacades(string $facades): self
    {
        $this->facades = $facades;

        return $this;
    }

    public function getParticularWalls(): ?string
    {
        return $this->particularWalls;
    }

    public function setParticularWalls(string $particularWalls): self
    {
        $this->particularWalls = $particularWalls;

        return $this;
    }

    public function getProject(): Project
    {
        return $this->project;
    }

    public function setProject(Project $project): self
    {
        $this->project = $project;

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

    /**
     * @return Collection|Plan[]
     */
    public function getPlan(): Collection
    {
        return $this->plan;
    }

    public function addPlan(Plan $plan): self
    {
        if (!$this->plan->contains($plan)) {
            $this->plan[] = $plan;
            $plan->setBuilding($this);
        }

        return $this;
    }

//    public function removePlan(Plan $plan): self
//    {
//        if ($this->plan->removeElement($plan)) {
//            // set the owning side to null (unless already changed)
//            if ($plan->getBuilding() === $this) {
//                $plan->setBuilding(null);
//            }
//        }
//
//        return $this;
//    }
}
