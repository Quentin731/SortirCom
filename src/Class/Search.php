<?php

    namespace App\Classe;


    use App\Entity\City;

    class Search {
        public $string = '';

        /**
         * @var City[]
         */
        public $city = [];

        /**
         * @return String
         */
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
         * @return City[]
         */
        public function getCity(): array
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


    }
