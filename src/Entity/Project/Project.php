<?php

declare(strict_types=1);

namespace App\Entity\Project;

use App\Entity\Billing;
use App\Entity\Thermician\Remark;
use App\Entity\Thermician\Ticket;
use App\Entity\User;
use App\Repository\Project\ProjectRepository;
use DateTime;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProjectRepository::class)]
class Project
{
    public const STATUS_BILLING = 'En attente de séléction forfais';
    public const STATUS_TO_COMPETE = "En Attente de remplissage d'informations";
    public const STATUS_ERROR_PAID = 'Erreur de paiement';
    public const STATUS_FINISH = 'PROJECT FINIS';
    public const STATUS_PAID = 'Paiement effectué';
    public const STATUS_ERROR_INFORMATION = 'Erreur il manque des informations';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    private string $firstName;

    #[ORM\Column(type: 'string', length: 255)]
    private string $lastName;

    #[ORM\Column(type: 'string', length: 255)]
    private string $company;

    #[ORM\Column(type: 'text')]
    private string $address;

    #[ORM\Column(type: 'string', length: 10)]
    private string $postalCode;

    #[ORM\Column(type: 'string', length: 255)]
    private string $city;

    #[ORM\Column(type: 'string', length: 255)]
    private string $phoneNumber;

    #[ORM\Column(type: 'string', length: 255)]
    private string $email;

    #[ORM\Column(type: 'datetime_immutable')]
    private DateTimeImmutable $createdAt;

    #[ORM\Column(type: 'string', length: 255)]
    private string $masterJob;

    #[ORM\Column(type: 'string', length: 255)]
    private string $cadastralReference;

    #[ORM\Column(type: 'string', length: 255)]
    private string $projectLocation;

    #[ORM\Column(type: 'string', length: 255)]
    private string $projectType;

    #[ORM\Column(type: 'string', length: 255)]
    private string $projectName;

    #[ORM\Column(type: 'string', length: 255)]
    private string $status = Project::STATUS_TO_COMPETE;

    #[ORM\Column(type: 'date')]
    private DateTime $constructionPlanDate;

    #[ORM\OneToOne(inversedBy: 'project', targetEntity: Owner::class, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private Owner $ownerProject;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'projects')]
    #[ORM\JoinColumn(nullable: false)]
    private User $user;

    #[ORM\OneToOne(mappedBy: 'project', targetEntity: Building::class, cascade: ['persist', 'remove'])]
    private ?Building $building;

    #[ORM\OneToOne(mappedBy: 'project', targetEntity: Carpentry::class, cascade: ['persist', 'remove'])]
    private ?Carpentry $carpentry;

    #[ORM\OneToOne(mappedBy: 'project', targetEntity: MainHeading::class, cascade: ['persist', 'remove'])]
    private ?MainHeading $mainHeading;

    #[ORM\OneToOne(mappedBy: 'project', targetEntity: SecondaryHeading::class, cascade: ['persist', 'remove'])]
    private ?SecondaryHeading $secondaryHeading;

    #[ORM\OneToOne(mappedBy: 'project', targetEntity: SanitaryHotwater::class, cascade: ['persist', 'remove'])]
    private ?SanitaryHotwater $sanitaryHotwater;

    #[ORM\OneToOne(mappedBy: 'project', targetEntity: Ventilation::class, cascade: ['persist', 'remove'])]
    private ?Ventilation $ventilation;

    /**
     * @ORM\Column(type="string")
     */
    #[ORM\OneToOne(mappedBy: 'project', targetEntity: Comment::class, cascade: ['persist', 'remove'])]
    private ?Comment $comment;

    #[ORM\OneToOne(mappedBy: 'project', targetEntity: Billing::class, cascade: ['persist', 'remove'])]
    private ?Billing $billing;

    #[ORM\OneToOne(mappedBy: 'project', targetEntity: Ticket::class, cascade: ['persist', 'remove'])]
    private ?Ticket $ticket;

    /**
     * @var Collection<Remark>
     */
    #[ORM\OneToMany(mappedBy: 'project', targetEntity: Remark::class)]
    private Collection $remarks;

    public function __construct()
    {
        $this->createdAt = new DateTimeImmutable();
        $this->remarks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getCompany(): ?string
    {
        return $this->company;
    }

    public function setCompany(string $company): self
    {
        $this->company = $company;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function setPostalCode(string $postalCode): self
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

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

    public function getMasterJob(): ?string
    {
        return $this->masterJob;
    }

    public function setMasterJob(string $masterJob): self
    {
        $this->masterJob = $masterJob;

        return $this;
    }

    public function getCadastralReference(): ?string
    {
        return $this->cadastralReference;
    }

    public function setCadastralReference(string $cadastralReference): self
    {
        $this->cadastralReference = $cadastralReference;

        return $this;
    }

    public function getProjectLocation(): ?string
    {
        return $this->projectLocation;
    }

    public function setProjectLocation(string $projectLocation): self
    {
        $this->projectLocation = $projectLocation;

        return $this;
    }

    public function getProjectType(): ?string
    {
        return $this->projectType;
    }

    public function setProjectType(string $projectType): self
    {
        $this->projectType = $projectType;

        return $this;
    }

    public function getConstructionPlanDate(): ?DateTime
    {
        return $this->constructionPlanDate;
    }

    public function setConstructionPlanDate(DateTime $constructionPlanDate): self
    {
        $this->constructionPlanDate = $constructionPlanDate;

        return $this;
    }

    public function getOwnerProject(): ?Owner
    {
        return $this->ownerProject;
    }

    public function setOwnerProject(Owner $ownerProject): self
    {
        $this->ownerProject = $ownerProject;

        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getBuilding(): ?Building
    {
        return $this->building;
    }

    public function setBuilding(Building $building): self
    {
        // set the owning side of the relation if necessary
        if ($building->getProject() !== $this) {
            $building->setProject($this);
        }

        $this->building = $building;

        return $this;
    }

    public function getCarpentry(): ?Carpentry
    {
        return $this->carpentry;
    }

    public function setCarpentry(Carpentry $carpentry): self
    {
        // set the owning side of the relation if necessary
        if ($carpentry->getProject() !== $this) {
            $carpentry->setProject($this);
        }

        $this->carpentry = $carpentry;

        return $this;
    }

    public function getMainHeading(): ?MainHeading
    {
        return $this->mainHeading;
    }

    public function setMainHeading(MainHeading $mainHeading): self
    {
        // set the owning side of the relation if necessary
        if ($mainHeading->getProject() !== $this) {
            $mainHeading->setProject($this);
        }

        $this->mainHeading = $mainHeading;

        return $this;
    }

    public function getSecondaryHeading(): ?SecondaryHeading
    {
        return $this->secondaryHeading;
    }

    public function setSecondaryHeading(SecondaryHeading $secondaryHeading): self
    {
        // set the owning side of the relation if necessary
        if ($secondaryHeading->getProject() !== $this) {
            $secondaryHeading->setProject($this);
        }

        $this->secondaryHeading = $secondaryHeading;

        return $this;
    }

    public function getSanitaryHotwater(): ?SanitaryHotwater
    {
        return $this->sanitaryHotwater;
    }

    public function setSanitaryHotwater(SanitaryHotwater $sanitaryHotwater): self
    {
        // set the owning side of the relation if necessary
        if ($sanitaryHotwater->getProject() !== $this) {
            $sanitaryHotwater->setProject($this);
        }

        $this->sanitaryHotwater = $sanitaryHotwater;

        return $this;
    }

    public function getVentilation(): ?Ventilation
    {
        return $this->ventilation;
    }

    public function setVentilation(Ventilation $ventilation): self
    {
        // set the owning side of the relation if necessary
        if ($ventilation->getProject() !== $this) {
            $ventilation->setProject($this);
        }

        $this->ventilation = $ventilation;

        return $this;
    }

    public function getComment(): ?Comment
    {
        return $this->comment;
    }

    public function setComment(Comment $comment): self
    {
        // set the owning side of the relation if necessary
        if ($comment->getProject() !== $this) {
            $comment->setProject($this);
        }

        $this->comment = $comment;

        return $this;
    }

    public function getBilling(): ?Billing
    {
        return $this->billing;
    }

    public function setBilling(Billing $billing): self
    {
        // set the owning side of the relation if necessary
        if ($billing->getProject() !== $this) {
            $billing->setProject($this);
        }

        $this->billing = $billing;

        return $this;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function getProjectName(): string
    {
        return $this->projectName;
    }

    public function setProjectName(string $projectName): void
    {
        $this->projectName = $projectName;
    }

    public function getTicket(): ?Ticket
    {
        return $this->ticket;
    }

    public function setTicket(Ticket $ticket): self
    {
        // set the owning side of the relation if necessary
        if ($ticket->getProject() !== $this) {
            $ticket->setProject($this);
        }

        $this->ticket = $ticket;

        return $this;
    }

    /**
     * @return Collection|Remark[]
     */
    public function getRemarks(): Collection
    {
        return $this->remarks;
    }

    public function addRemark(Remark $remark): self
    {
        if (!$this->remarks->contains($remark)) {
            $this->remarks[] = $remark;
            $remark->setProject($this);
        }

        return $this;
    }

//    public function removeRemark(Remark $remark): self
//    {
//        if ($this->remarks->removeElement($remark)) {
//            // set the owning side to null (unless already changed)
//            if ($remark->getProject() === $this) {
//                $remark->setProject(null);
//            }
//        }
//
//        return $this;
//    }
}
