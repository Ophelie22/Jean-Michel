<?php
namespace App\Service;

use App\Entity\Freelance;
use App\Entity\FreelanceConso;
use Symfony\Component\Serializer\SerializerInterface;

readonly class FreelanceSerializer
{
    public function __construct(private SerializerInterface $serializer)
    {
    }

    public function serializeFreelance(Freelance $freelance, array $groups): string
    {
        return $this->serializer->serialize($freelance, 'json', $groups);
    }

    public function serializeFreelances(array $freelances, array $groups): string
    {
        return $this->serializer->serialize($freelances, 'json', $groups);
    }

    public function serializeFreelancesConso(array $freelances, array $groups): string
    {
        return $this->serializer->serialize($freelances, 'json', $groups);
    }
}