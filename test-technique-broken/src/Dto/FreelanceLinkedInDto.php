<?php

namespace App\Dto;

use Symfony\Component\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

class FreelanceLinkedInDto
{
    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Serializer\SerializedName('firstName')]
    public string $firstName;

    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Serializer\SerializedName('lastName')]
    public string $lastName;

    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Serializer\SerializedName('jobTitle')]
    public string $jobTitle;

    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Serializer\SerializedName('url')]
    public string $url;

    public function __construct(string $firstName = '', string $lastName = '', string $jobTitle = '', string $url = '')
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->jobTitle = $jobTitle;
        $this->url = $url;
    }
}
