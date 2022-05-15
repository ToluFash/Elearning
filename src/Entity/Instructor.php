<?php

namespace App\Entity;

use App\Repository\InstructorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InstructorRepository::class)]
class Instructor
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToMany(targetEntity: Course::class, mappedBy: 'Instructors')]
    private $courses;

    #[ORM\OneToOne(mappedBy: 'CourseHead', targetEntity: Course::class, cascade: ['persist', 'remove'])]
    private $course;

    #[ORM\OneToMany(mappedBy: 'CourseHead', targetEntity: Course::class)]
    private $mycourses;

    #[ORM\OneToOne(inversedBy: 'instructor', targetEntity: User::class, cascade: ['persist', 'remove'])]
    private $User;

    #[ORM\ManyToOne(targetEntity: Department::class, inversedBy: 'Instructors')]
    private $department;

    #[ORM\OneToMany(mappedBy: 'Instructor', targetEntity: Assignment::class)]
    private $yes;

    public function __construct()
    {
        $this->courses = new ArrayCollection();
        $this->mycourses = new ArrayCollection();
        $this->yes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Course>
     */
    public function getCourses(): Collection
    {
        return $this->courses;
    }

    public function addCourse(Course $course): self
    {
        if (!$this->courses->contains($course)) {
            $this->courses[] = $course;
            $course->addInstructor($this);
        }

        return $this;
    }

    public function removeCourse(Course $course): self
    {
        if ($this->courses->removeElement($course)) {
            $course->removeInstructor($this);
        }

        return $this;
    }

    public function getCourse(): ?Course
    {
        return $this->course;
    }

    public function setCourse(?Course $course): self
    {
        // unset the owning side of the relation if necessary
        if ($course === null && $this->course !== null) {
            $this->course->setCourseHead(null);
        }

        // set the owning side of the relation if necessary
        if ($course !== null && $course->getCourseHead() !== $this) {
            $course->setCourseHead($this);
        }

        $this->course = $course;

        return $this;
    }

    /**
     * @return Collection<int, Course>
     */
    public function getMycourses(): Collection
    {
        return $this->mycourses;
    }

    public function addMycourse(Course $mycourse): self
    {
        if (!$this->mycourses->contains($mycourse)) {
            $this->mycourses[] = $mycourse;
            $mycourse->setCourseHead($this);
        }

        return $this;
    }

    public function removeMycourse(Course $mycourse): self
    {
        if ($this->mycourses->removeElement($mycourse)) {
            // set the owning side to null (unless already changed)
            if ($mycourse->getCourseHead() === $this) {
                $mycourse->setCourseHead(null);
            }
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->User;
    }

    public function setUser(?User $User): self
    {
        $this->User = $User;

        return $this;
    }

    public function getDepartment(): ?Department
    {
        return $this->department;
    }

    public function setDepartment(?Department $department): self
    {
        $this->department = $department;

        return $this;
    }

    /**
     * @return Collection<int, Assignment>
     */
    public function getYes(): Collection
    {
        return $this->yes;
    }

    public function addYe(Assignment $ye): self
    {
        if (!$this->yes->contains($ye)) {
            $this->yes[] = $ye;
            $ye->setInstructor($this);
        }

        return $this;
    }

    public function removeYe(Assignment $ye): self
    {
        if ($this->yes->removeElement($ye)) {
            // set the owning side to null (unless already changed)
            if ($ye->getInstructor() === $this) {
                $ye->setInstructor(null);
            }
        }

        return $this;
    }
}
