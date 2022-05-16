<?php

namespace App\Entity;

use App\Repository\CourseWeeksRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CourseWeeksRepository::class)]
class CourseWeek
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: Course::class, inversedBy: 'courseWeeks')]
    private $course;

    #[ORM\OneToMany(mappedBy: 'courseWeek', targetEntity: Assignment::class)]
    private $Assignments;

    #[ORM\OneToMany(mappedBy: 'CourseWeek', targetEntity: Lecture::class)]
    private $lectures;

    #[ORM\Column(type: 'integer')]
    private $cardinality;

    public function __construct()
    {
        $this->Assignments = new ArrayCollection();
        $this->lectures = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCourse(): ?Course
    {
        return $this->course;
    }

    public function setCourse(?Course $course): self
    {
        $this->course = $course;

        return $this;
    }

    /**
     * @return Collection<int, Assignment>
     */
    public function getAssignments(): Collection
    {
        return $this->Assignments;
    }

    public function addAssignment(Assignment $assignment): self
    {
        if (!$this->Assignments->contains($assignment)) {
            $this->Assignments[] = $assignment;
            $assignment->setCourseWeek($this);
        }

        return $this;
    }

    public function removeAssignment(Assignment $assignment): self
    {
        if ($this->Assignments->removeElement($assignment)) {
            // set the owning side to null (unless already changed)
            if ($assignment->getCourseWeek() === $this) {
                $assignment->setCourseWeek(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Lecture>
     */
    public function getLectures(): Collection
    {
        return $this->lectures;
    }

    public function addLecture(Lecture $lecture): self
    {
        if (!$this->lectures->contains($lecture)) {
            $this->lectures[] = $lecture;
            $lecture->setCourseWeek($this);
        }

        return $this;
    }

    public function removeLecture(Lecture $lecture): self
    {
        if ($this->lectures->removeElement($lecture)) {
            // set the owning side to null (unless already changed)
            if ($lecture->getCourseWeek() === $this) {
                $lecture->setCourseWeek(null);
            }
        }

        return $this;
    }

    public function getCardinality(): ?int
    {
        return $this->cardinality;
    }

    public function setCardinality(int $cardinality): self
    {
        $this->cardinality = $cardinality;

        return $this;
    }

    public function getChoiceName(): string
    {
        return $this->getCourse()->getTitle() ." Week ". $this->getCardinality();
    }
}
