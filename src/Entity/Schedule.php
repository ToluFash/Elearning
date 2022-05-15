<?php

namespace App\Entity;

use App\Repository\ScheduleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ScheduleRepository::class)]
class Schedule
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\OneToMany(mappedBy: 'schedule', targetEntity: Time::class)]
    private $Times;

    #[ORM\ManyToOne(targetEntity: Course::class, inversedBy: 'schedules')]
    private $Course;

    public function __construct()
    {
        $this->Times = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Time>
     */
    public function getTimes(): Collection
    {
        return $this->Times;
    }

    public function addTime(Time $time): self
    {
        if (!$this->Times->contains($time)) {
            $this->Times[] = $time;
            $time->setSchedule($this);
        }

        return $this;
    }

    public function removeTime(Time $time): self
    {
        if ($this->Times->removeElement($time)) {
            // set the owning side to null (unless already changed)
            if ($time->getSchedule() === $this) {
                $time->setSchedule(null);
            }
        }

        return $this;
    }

    public function getCourse(): ?Course
    {
        return $this->Course;
    }

    public function setCourse(?Course $Course): self
    {
        $this->Course = $Course;

        return $this;
    }
}
