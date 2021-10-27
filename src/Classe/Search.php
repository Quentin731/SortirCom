<?php

    namespace App\Classe;


    use App\Entity\City;
    use Doctrine\Common\Collections\ArrayCollection;

    class Search {
        public $string;


        public $city;


        public function __construct()
        {
            $this->string = '';
            $this->city = new ArrayCollection();
        }

        public function getString(): string
        {
            return $this->string;
        }

        /**
         * @param String $string
         */
        public function setString(string $string): void
        {
            $this->string = $string;
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
            return $this->string;
        }
    }
