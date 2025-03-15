<?php

namespace App\Dto;


class SearchFreelanceConsoDto
{
    public function __construct(
        public string $query
    ) {}

    // // ajout de contrtaintes de validation pour la requête
    // class SearchFreelanceConsoDto
    // {
    //     public function __construct(
    //         #[Assert\NotBlank(message: 'La requête ne doit pas être vide.')]
    //         #[Assert\Length(
    //             min: 3,
    //             minMessage: 'La requête doit comporter au moins {{ limit }} caractères.'
    //         )]
    //         public string $query
    //     )
    //     {
    //     }
}
