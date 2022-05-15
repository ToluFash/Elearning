<?php

namespace App\Entity;

use App\Repository\TimeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TimeRepository::class)]
class Time
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'datetime')]
    private $ScheduleFrom;

    #[ORM\Column(type: 'datetime')]
    private $ScheduleTo;

    #[ORM\ManyToOne(targetEntity: Schedule::class, inversedBy: 'Times')]
    private $schedule;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getScheduleFrom(): ?\DateTimeInterface
    {
        return $this->ScheduleFrom;
    }

    public function setScheduleFrom(\DateTimeInterface $ScheduleFrom): self
    {
        $this->ScheduleFrom = $ScheduleFrom;

        return $this;
    }

    public function getScheduleTo(): ?\DateTimeInterface
    {
        return $this->ScheduleTo;
    }

    public function setScheduleTo(\DateTimeInterface $ScheduleTo): self
    {
        $this->ScheduleTo = $ScheduleTo;

        return $this;
    }

    public function getSchedule(): ?Schedule
    {
        return $this->schedule;
    }

    public function setSchedule(?Schedule $schedule): self
    {
        $this->schedule = $schedule;

        return $this;
    }
}
