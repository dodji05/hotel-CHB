<?php

namespace App\Model;

class Reservation
{
    private $dateArrive;
    private $dateDepart;
    private $nbAdulte;
    private $nbEnfant;

    /**
     * @return mixed
     */
    public function getDateArrive()
    {
        return $this->dateArrive;
    }

    /**
     * @param mixed $dateArrive
     */
    public function setDateArrive($dateArrive): void
    {
        $this->dateArrive = $dateArrive;
    }

    /**
     * @return mixed
     */
    public function getDateDepart()
    {
        return $this->dateDepart;
    }

    /**
     * @param mixed $dateDepart
     * @return Reservation
     */
    public function setDateDepart($dateDepart)
    {
        $this->dateDepart = $dateDepart;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNbAdulte()
    {
        return $this->nbAdulte;
    }

    /**
     * @param mixed $nbAdulte
     * @return Reservation
     */
    public function setNbAdulte($nbAdulte)
    {
        $this->nbAdulte = $nbAdulte;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNbEnfant()
    {
        return $this->nbEnfant;
    }

    /**
     * @param mixed $nbEnfant
     * @return Reservation
     */
    public function setNbEnfant($nbEnfant)
    {
        $this->nbEnfant = $nbEnfant;
        return $this;
    }


}
