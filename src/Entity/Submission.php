<?php

namespace App\Entity;

use App\Repository\SubmissionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SubmissionRepository::class)]
class Submission
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: Assignment::class, inversedBy: 'submissions')]
    private $Assignment;

    #[ORM\ManyToOne(targetEntity: Student::class, inversedBy: 'submissions')]
    private $Student;

    #[ORM\Column(type: 'datetime')]
    private $SubmitDate;

    #[ORM\Column(type: 'text')]
    private $filer;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAssignment(): ?Assignment
    {
        return $this->Assignment;
    }

    public function setAssignment(?Assignment $Assignment): self
    {
        $this->Assignment = $Assignment;

        return $this;
    }

    public function getStudent(): ?Student
    {
        return $this->Student;
    }

    public function setStudent(?Student $Student): self
    {
        $this->Student = $Student;

        return $this;
    }

    public function getSubmitDate(): ?\DateTimeInterface
    {
        return $this->SubmitDate;
    }

    public function setSubmitDate(\DateTimeInterface $SubmitDate): self
    {
        $this->SubmitDate = $SubmitDate;

        return $this;
    }

    public function getFiler(): ?string
    {
        return $this->filer;
    }

    public function setFiler(string $filer): self
    {
        $this->filer = $filer;

        return $this;
    }
}
