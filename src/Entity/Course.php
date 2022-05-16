<?php

namespace App\Entity;

use App\Repository\CourseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CourseRepository::class)]
class Course
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $Title;

    #[ORM\ManyToOne(targetEntity: Department::class, inversedBy: 'courses')]
    #[ORM\JoinColumn(nullable: false)]
    private $Department;

    #[ORM\ManyToMany(targetEntity: Instructor::class, inversedBy: 'courses')]
    private $Instructors;

    #[ORM\ManyToMany(targetEntity: Student::class, inversedBy: 'courses')]
    private $Students;

    #[ORM\ManyToOne(targetEntity: Instructor::class, inversedBy: 'mycourses')]
    private $CourseHead;

    #[ORM\OneToMany(mappedBy: 'Course', targetEntity: Schedule::class)]
    private $schedules;

    #[ORM\OneToMany(mappedBy: 'Course', targetEntity: Assignment::class)]
    private $assignments;

    #[ORM\Column(type: 'text', nullable: true)]
    private $description;

    #[ORM\OneToMany(mappedBy: 'course', targetEntity: CourseWeek::class)]
    private $courseWeeks;



    public function __construct()
    {
        $this->Instructors = new ArrayCollection();
        $this->Students = new ArrayCollection();
        $this->schedules = new ArrayCollection();
        $this->assignments = new ArrayCollection();
        $this->courseWeeks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->Title;
    }

    public function setTitle(string $Title): self
    {
        $this->Title = $Title;

        return $this;
    }

    public function getDepartment(): ?Department
    {
        return $this->Department;
    }

    public function setDepartment(?Department $Department): self
    {
        $this->Department = $Department;

        return $this;
    }

    /**
     * @return Collection<int, Instructor>
     */
    public function getInstructors(): Collection
    {
        return $this->Instructors;
    }

    public function addInstructor(Instructor $instructor): self
    {
        if (!$this->Instructors->contains($instructor)) {
            $this->Instructors[] = $instructor;
        }

        return $this;
    }

    public function removeInstructor(Instructor $instructor): self
    {
        $this->Instructors->removeElement($instructor);

        return $this;
    }

    /**
     * @return Collection<int, Student>
     */
    public function getStudents(): Collection
    {
        return $this->Students;
    }

    public function addStudent(Student $student): self
    {
        if (!$this->Students->contains($student)) {
            $this->Students[] = $student;
        }

        return $this;
    }

    public function removeStudent(Student $student): self
    {
        $this->Students->removeElement($student);

        return $this;
    }

    public function getCourseHead(): ?Instructor
    {
        return $this->CourseHead;
    }

    public function setCourseHead(?Instructor $CourseHead): self
    {
        $this->CourseHead = $CourseHead;

        return $this;
    }

    /**
     * @return Collection<int, Schedule>
     */
    public function getSchedules(): Collection
    {
        return $this->schedules;
    }

    public function addSchedule(Schedule $schedule): self
    {
        if (!$this->schedules->contains($schedule)) {
            $this->schedules[] = $schedule;
            $schedule->setCourse($this);
        }

        return $this;
    }

    public function removeSchedule(Schedule $schedule): self
    {
        if ($this->schedules->removeElement($schedule)) {
            // set the owning side to null (unless already changed)
            if ($schedule->getCourse() === $this) {
                $schedule->setCourse(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Assignment>
     */
    public function getAssignments(): Collection
    {
        return $this->assignments;
    }

    public function addAssignment(Assignment $assignment): self
    {
        if (!$this->assignments->contains($assignment)) {
            $this->assignments[] = $assignment;
            $assignment->setCourse($this);
        }

        return $this;
    }

    public function removeAssignment(Assignment $assignment): self
    {
        if ($this->assignments->removeElement($assignment)) {
            // set the owning side to null (unless already changed)
            if ($assignment->getCourse() === $this) {
                $assignment->setCourse(null);
            }
        }

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, CourseWeek>
     */
    public function getCourseWeeks(): Collection
    {
        return $this->courseWeeks;
    }

    public function addCourseWeek(CourseWeek $courseWeek): self
    {
        if (!$this->courseWeeks->contains($courseWeek)) {
            $this->courseWeeks[] = $courseWeek;
            $courseWeek->setCourse($this);
        }

        return $this;
    }

    public function removeCourseWeek(CourseWeek $courseWeek): self
    {
        if ($this->courseWeeks->removeElement($courseWeek)) {
            // set the owning side to null (unless already changed)
            if ($courseWeek->getCourse() === $this) {
                $courseWeek->setCourse(null);
            }
        }

        return $this;
    }


}
