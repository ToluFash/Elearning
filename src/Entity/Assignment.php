<?php

namespace App\Entity;

use App\Repository\AssignmentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AssignmentRepository::class)]
class Assignment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $Title;

    #[ORM\ManyToOne(targetEntity: Course::class, inversedBy: 'assignments')]
    private $Course;

    #[ORM\ManyToOne(targetEntity: Instructor::class, inversedBy: 'yes')]
    private $Instructor;

    #[ORM\OneToMany(mappedBy: 'Assignment', targetEntity: Submission::class)]
    private $submissions;

    public function __construct()
    {
        $this->submissions = new ArrayCollection();
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

    public function getCourse(): ?Course
    {
        return $this->Course;
    }

    public function setCourse(?Course $Course): self
    {
        $this->Course = $Course;

        return $this;
    }

    public function getInstructor(): ?Instructor
    {
        return $this->Instructor;
    }

    public function setInstructor(?Instructor $Instructor): self
    {
        $this->Instructor = $Instructor;

        return $this;
    }

    /**
     * @return Collection<int, Submission>
     */
    public function getSubmissions(): Collection
    {
        return $this->submissions;
    }

    public function addSubmission(Submission $submission): self
    {
        if (!$this->submissions->contains($submission)) {
            $this->submissions[] = $submission;
            $submission->setAssignment($this);
        }

        return $this;
    }

    public function removeSubmission(Submission $submission): self
    {
        if ($this->submissions->removeElement($submission)) {
            // set the owning side to null (unless already changed)
            if ($submission->getAssignment() === $this) {
                $submission->setAssignment(null);
            }
        }

        return $this;
    }
}
