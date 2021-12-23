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
     * @ORM\ManyToOne(targetEntity="Booking")
     * @ORM\JoinColumn(nullable=false)
     */
    private $booking;

    /**
     * @ORM\Column(type="integer", nullable=true)
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
    private $durationInMinutes;

    /**
     * @return mixed
     */
    public function getBooking()
    {
        return $this->booking;
    }

    /**
     * @param mixed $booking
     */
    public function setBooking($booking): void
    {
        $this->booking = $booking;
    }

    /**
     * @return mixed
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * @param mixed $service
     */
    public function setService($service): void
    {
        $this->service = $service;
    }

    /**
     * @return mixed
     */
    public function getEmployee()
    {
        return $this->employee;
    }

    /**
     * @param mixed $employee
     */
    public function setEmployee($employee): void
    {
        $this->employee = $employee;
    }

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
     *
     */
    public function getDurationInMinutes()
    {
        return $this->durationInMinutes;
    }

    /**
     * @param mixed $durationInMinutes
     */
    public function setDurationInMinutes($durationInMinutes): void
    {
        $this->durationInMinutes = $durationInMinutes;
    }
}
