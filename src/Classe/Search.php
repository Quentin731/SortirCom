<?php

    namespace App\Classe;


    use App\Entity\City;
    use Doctrine\Common\Collections\ArrayCollection;

    class Search {
        public $userSearch;


        public $city;


        public function __construct()
        {
            $this->userSearch = '';
            $this->city = new ArrayCollection();
        }

        public function getUserSearch(): string
        {
            return $this->userSearch;
        }

        /**
         * @param String $userSearch
         */
        public function setUserSearch(string $userSearch): void
        {
            $this->userSearch = $userSearch;
        }

        /**
         * @return Collection|City[]
         */
        public function getCity()
        {
            return $this->city;
        }

        /**
         * @param City[] $city
         */
        public function setCity(array $city): void
        {
            $this->city = $city;
        }

        public function __toString(): string {
            return $this->userSearch;
        }
    }
