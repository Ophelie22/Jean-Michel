<?php

namespace App\Command;

use App\Dto\FreelanceJeanPaulDto;
use App\Dto\FreelanceLinkedInDto;
use App\Message\InsertFreelanceJeanPaulMessage;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Serializer\SerializerInterface;

#[AsCommand(
    name: 'app:scrap:jean-paul',
    description: 'Scrap data from JeanPaul',
)]
class ScrapJeanPaulCommand extends Command
{
    public function __construct(private readonly SerializerInterface $serializer, private readonly MessageBusInterface $bus)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $jsonData = file_get_contents('./datas/jean-paul.json');

        try {
            // Désérialisation du JSON dans les objets FreelanceJeanPaulDto
            $jeanPaulDtos = $this->serializer->deserialize($jsonData, FreelanceJeanPaulDto::class . '[]', 'json');
        } catch (\Symfony\Component\Serializer\Exception\NotEncodableValueException $e) {
            // En cas d'erreur de désérialisation, afficher le message d'erreur
            $io->error("Error during deserialization: " . $e->getMessage());
            return Command::FAILURE; // Retourner un code d'échec
        }

        $io->success('Data deserialized successfully');

        /** @var FreelanceJeanPaulDto $jeanPaulDto */
        foreach ($jeanPaulDtos as $jeanPaulDto) {
            $this->bus->dispatch(new InsertFreelanceJeanPaulMessage($jeanPaulDto));
            $io->note('Dispatching message for ' . $jeanPaulDto->firstName . ' ' . $jeanPaulDto->lastName);
        }
        $io->success('All messages have been dispatched.');

        return Command::SUCCESS;
    }
}
