<?php

namespace App\Entity;

use App\Repository\ConfigRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ConfigRepository::class)
 */
class Config
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=8)
     */
    private $dayStartTime;

    /**
     * @ORM\Column(type="string", length=8)
     */
    private $dayEndTime;

    /**
     * @ORM\Column(type="integer")
     */
    private $minuteSteps;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDayStartTime(): ?string
    {
        return $this->dayStartTime;
    }

    public function setDayStartTime(string $dayStartTime): self
    {
        $this->dayStartTime = $dayStartTime;

        return $this;
    }

    public function getDayEndTime(): ?string
    {
        return $this->dayEndTime;
    }

    public function setDayEndTime(string $dayEndTime): self
    {
        $this->dayEndTime = $dayEndTime;

        return $this;
    }

    public function getMinuteSteps(): ?int
    {
        return $this->minuteSteps;
    }

    public function setMinuteSteps(int $minuteSteps): self
    {
        $this->minuteSteps = $minuteSteps;

        return $this;
    }
}
