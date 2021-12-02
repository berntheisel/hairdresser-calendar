<?php

namespace App\Entity;

use App\Repository\BookingsServicesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BookingsServicesRepository::class)
 */
class BookingsServices
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Booking
     * @ORM\JoinColumn(nullable=false)
     */
    private $booking;

    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    private $sort;

    /**
     * @ORM\ManyToOne(targetEntity="Service")
     * @ORM\JoinColumn(nullable=false)
     */
    private $service;

    /**
     * @ORM\ManyToOne(targetEntity="Employee")
     * @ORM\JoinColumn(nullable=false)
     */
    private $employee;

    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    private $duration_in_minutes;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getSort()
    {
        return $this->sort;
    }

    /**
     * @param mixed $sort
     */
    public function setSort($sort): void
    {
        $this->sort = $sort;
    }

    /**
     * @return mixed
     */
    public function getDurationInMinutes()
    {
        return $this->duration_in_minutes;
    }

    /**
     * @param mixed $duration_in_minutes
     */
    public function setDurationInMinutes($duration_in_minutes): void
    {
        $this->duration_in_minutes = $duration_in_minutes;
    }


}
