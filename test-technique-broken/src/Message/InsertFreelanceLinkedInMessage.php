<?php

namespace App\Message;

use App\Dto\FreelanceLinkedInDto;

final class InsertFreelanceLinkedInMessage
{
    public function __construct(
        public FreelanceLinkedInDto $dto
    ) {}
}
//Explication du flux de données :
//Message InsertFreelanceLinkedInMessage : Ce message contient un DTO (ici FreelanceLinkedInDto) qui sera traité par un MessageHandler.
//Handler InsertFreelanceLinkedInMessageHandler : Ce handler écoute le message et appelle le service InsertFreelanceLinkedIn pour insérer un FreelanceLinkedIn dans la base de données.
//Service InsertFreelanceLinkedIn : Ce service prend le DTO, crée une entité FreelanceLinkedIn, et l'enregistre dans la base de données.