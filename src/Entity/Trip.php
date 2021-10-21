<?php

namespace App\Entity;

use App\Repository\TripRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TripRepository::class)
 */
class Trip
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $tripName;

    /**
     * @ORM\Column(type="datetime")
     */
    private $tripStartDate;

    /**
     * @ORM\Column(type="integer")
     */
    private $duration;

    /**
     * @ORM\Column(type="date")
     */
    private $deadlineRegistrationDate;

    /**
     * @ORM\Column(type="integer")
     */
    private $capacity;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity=Place::class, inversedBy="trips")
     */
    private $place;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $cencelationReason;

    /**
     * @ORM\Column(type="date", nullable=false)
     */
    private $endDate;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="trip", cascade={"persist"})
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $organizer;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $state = 1;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="trips")
     */
    private $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTripName(): ?string
    {
        return $this->tripName;
    }

    public function setTripName(string $tripName): self
    {
        $this->tripName = $tripName;

        return $this;
    }

    public function getTripStartDate(): ?\DateTimeInterface
    {
        return $this->tripStartDate;
    }

    public function setTripStartDate(\DateTimeInterface $tripStartDate): self
    {
        $this->tripStartDate = $tripStartDate;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getDeadlineRegistrationDate(): ?\DateTimeInterface
    {
        return $this->deadlineRegistrationDate;
    }

    public function setDeadlineRegistrationDate(\DateTimeInterface $deadlineRegistrationDate): self
    {
        $this->deadlineRegistrationDate = $deadlineRegistrationDate;

        return $this;
    }

    public function getCapacity(): ?int
    {
        return $this->capacity;
    }

    public function setCapacity(int $capacity): self
    {
        $this->capacity = $capacity;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPlace(): ?Place
    {
        return $this->place;
    }

    public function setPlace(?Place $place): self
    {
        $this->place = $place;

        return $this;
    }


    public function getCencelationReason(): ?string
    {
        return $this->cencelationReason;
    }

    public function setCencelationReason(string $cencelationReason): self
    {
        $this->cencelationReason = $cencelationReason;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getOrganizer(): ?User
    {
        return $this->organizer;
    }

    public function setOrganizer(?User $organizer): self
    {
        $this->organizer = $organizer;

        return $this;
    }

    public function getParticipation(?User $mainUser) {
        $values = 0;
        foreach ($this->users as $user) {
            if ($mainUser != null && $user->getId() == $mainUser->getId()) {
                $values = 1;
            }
        }
        return $values;
    }

    public function getSizeOfUsers() {
        return sizeof($this->users);
    }

    public function getEtatToString(){
        $dateNow= date("Y-m-d H:i:s");
        if($this->state==1){
            return "Annulée";
        }
        if($this->state==2){
            if($this->deadlineRegistrationDate<$dateNow && $this->getSizeOfUsers()==$this->capacity){
                return "Ouvert";
            }
            else{
                return "fermée";
            }
        }
        else{
            return "En création";
        }

    }

    public function getState(): ?int
    {
        return $this->state;
    }

    public function setState(?int $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function __toString(){
        return $this->tripName;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
        }

        return $this;
    }

    public function addUsers(array $users): void
    {
        foreach($users as $user){
            $this->addUser($user);
        }
    }

    public function removeUser(User $user): self
    {
        $this->users->removeElement($user);

        return $this;
    }

    public function addUserWithValidation(User $user){
        $error=null;
        $dateNow= date("Y-m-d H:i:s");

        if($this->state==1) {
            $error = "Impossible, La sortie a été annulée";
        }elseif ($this->state==3){
            $error = "Impossible, La sortie a pas encore été publiée";
        }
        if($this->endDate() < $dateNow){
            $error = "Impossible, Les Inscriptions sont terminées";
        }
        if($this->capacity >= $this->getSizeOfUsers()){
            $error = "Impossible, La sortie est pleine";
        }
        if($error==null){
            $this->addUser($user);
        }
        return $error;
    }
}
