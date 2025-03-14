<?php

namespace App\Service;

use App\Dto\FreelanceJeanPaulDto;
use App\Entity\Freelance;
use App\Entity\FreelanceJeanPaul;
use Doctrine\ORM\EntityManagerInterface;

readonly class InsertFreelanceJeanPaul
{
    public function __construct(private EntityManagerInterface $entityManager) {}

    public function insertFreelanceJeanPaul(FreelanceJeanPaulDto $dto): FreelanceJeanPaul
    {
        $freelanceJeanPaul = $this->entityManager->getRepository(FreelanceJeanPaul::class)->findOneBy(['jeanPaulId' => $dto->jeanPaulId]);
        if (!$freelanceJeanPaul) {
            $freelanceJeanPaul = new FreelanceJeanPaul();
            $freelanceJeanPaul->setJeanPaulId($dto->jeanPaulId);

            $currentDate = new \DateTime();
            $freelanceJeanPaul->setCreatedAt($currentDate);
            $freelanceJeanPaul->setUpdatedAt($currentDate);



            $this->entityManager->persist($freelanceJeanPaul); // Ajout du persis
        }


        if (!$freelanceJeanPaul->getFreelance()) {
            $freelance = new Freelance();
            $freelance->addFreelanceJeanPaul($freelanceJeanPaul);
            $this->entityManager->persist($freelance); // Ajout du persis
        }

        $freelanceJeanPaul->setFirstName($dto->firstName);
        $freelanceJeanPaul->setLastName($dto->lastName);
        $freelanceJeanPaul->setJobTitle($dto->jobTitle);
        $this->entityManager->flush(); // Envoi des modifications à la base de données


        return $freelanceJeanPaul;
    }
}
