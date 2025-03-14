<?php

namespace App\Service;

use App\Entity\Freelance;
use App\Entity\FreelanceConso;
use Symfony\Component\Serializer\SerializerInterface;

readonly class FreelanceSerializer
{
    public function __construct(private SerializerInterface $serializer) {}

    public function serializeFreelance(Freelance $freelance, array $groups): string
    {
        $context = [
            'groups' => $groups,
            'circular_reference_handler' => function ($object) {
                return $object->getId();  // On retourne l'ID pour éviter les boucles infinies
            },
        ];
        return $this->serializer->serialize($freelance, 'json', $context);
    }

    public function serializeFreelances(array $freelances, array $groups): string
    {
        $context = [
            'groups' => $groups,
            'circular_reference_handler' => function ($object) {
                return $object->getId();  // On retourne l'ID pour éviter les boucles infinies
            },
        ];
        return $this->serializer->serialize($freelances, 'json', $context);
    }

    public function serializeFreelancesConso(array $freelances, array $groups): string
    { // Contexte pour gérer les références circulaires
        $context = [
            'groups' => $groups,
            'circular_reference_handler' => function ($object) {
                return $object->getId();  // On retourne l'ID pour éviter les boucles infinies
            },
        ];
        return $this->serializer->serialize($freelances, 'json', $context);
    }
}
