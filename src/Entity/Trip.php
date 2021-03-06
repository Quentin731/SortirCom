<?php

namespace App\Entity;

use App\Repository\TripRepository;
use DateInterval;
use DateTime;
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
     * @ORM\Column(type="integer", nullable=true)
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

    /**
     * @ORM\ManyToMany(targetEntity=Group::class, inversedBy="trips")
     */
    private $groups;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->groups = new ArrayCollection();
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
        $dateNow= new DateTime('now');
        if($this->state==1){
            return "Annul??e";
        }
        if($this->state==2){
            if($this->deadlineRegistrationDate>$dateNow){
                return "Ouvert";
            }
            else{
                return "ferm??e";
            }
        }
        else{
            return "En cr??ation";
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
        $dateNow = new DateTime('now');;

        if($this->state==1) {
            $error = "Impossible, La sortie a ??t?? annul??e";
        }elseif ($this->state==3){
            $error = "Impossible, La sortie a pas encore ??t?? publi??e";
        }
        if($this->deadlineRegistrationDate <= $dateNow){
            $error = "Impossible, Les Inscriptions sont termin??es";
        }
        if($this->capacity <= $this->getSizeOfUsers()){
            $error = "Impossible, La sortie est pleine";
        }
        if($error==null){
            $this->addUser($user);
        }
        return $error;
    }
    public function removeUserWithValidation(User $user){
        $error=null;
        $dateNow = new DateTime('now');;

        if($this->deadlineRegistrationDate <= $dateNow) {
            $error = "Impossible, La sortie a d??but??e";
        }

        if($error==null){
            $this->removeUser($user);
        }
        return $error;
    }

    public function getIsAviable(){
        $dateNow = new DateTime('now');
        if ($this->getEndDate()->add(new DateInterval('P30D')) < $dateNow){
            $dateNow = new DateTime('now');
            return false;
        }
        return true;
    }

    /**
     * @return Collection|Group[]
     */
    public function getGroups(): Collection
    {
        return $this->groups;
    }

    public function addGroup(Group $group): self
    {
        if (!$this->groups->contains($group)) {
            $this->groups[] = $group;
        }

        return $this;
    }

    public function removeGroup(Group $group): self
    {
        $this->groups->removeElement($group);

        return $this;
    }
}
