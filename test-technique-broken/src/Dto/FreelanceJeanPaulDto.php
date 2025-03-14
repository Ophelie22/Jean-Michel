<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class FreelanceJeanPaulDto
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\NotNull]
        public string $firstName,

        #[Assert\NotBlank]
        #[Assert\NotNull]
        public string $lastName,

        #[Assert\NotBlank]
        #[Assert\NotNull]
        public string $jobTitle, // Assurez-vous que jobTitle est initialisé

        #[Assert\Type('integer')]
        public int $jeanPaulId
    ) {}
}
