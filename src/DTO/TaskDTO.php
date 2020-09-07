<?php


namespace App\DTO;


class TaskDTO
{
    private $id;
    private $name;
    private $date;


    public function getId() : int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDate() : \DateTimeInterface
    {
        return $this->date;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;
        return $this;
    }


}