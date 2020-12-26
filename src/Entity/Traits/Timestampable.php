<?php

namespace App\Entity\Traits;

trait Timestampable{

    /**
     * @ORM\Column(type="datetime", options={"default":"CURRENT_TIMESTAMP"})
     */
    private $date_publication;

    public function getDatePublication(): ?\DateTimeInterface
    {
        return $this->date_publication;
    }

    public function setDatePublication(\DateTimeInterface $date_publication): self
    {
        $this->date_publication = $date_publication;

        return $this;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updateTimestamps(){
        if($this->getDatePublication()===null) {
            $this->setDatePublication(new \DateTimeImmutable);
        }
    }

}