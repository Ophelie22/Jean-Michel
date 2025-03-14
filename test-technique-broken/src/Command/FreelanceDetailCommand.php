<?php

namespace App\Command;

use App\Entity\Freelance;
use App\Service\FreelanceSerializer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Serializer\SerializerInterface;

#[AsCommand(
    name: 'app:freelance:detail',
    description: 'Get freelance as Json',
)]
class FreelanceDetailCommand extends Command
{
    public function __construct(
        private readonly FreelanceSerializer $freelanceSerializer,
        private readonly EntityManagerInterface $entityManager
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('freelanceId', InputArgument::REQUIRED, 'Freelance ID');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $freelanceId = $input->getArgument('freelanceId');

        $freelance = $this->entityManager->getRepository(Freelance::class)->find($freelanceId);
        if (!$freelance) {
            $io->error('Freelance not found');
            return Command::FAILURE;
        }
        // Utilisation d'un gestionnaire de référence circulaire
        $context = [
            'circular_reference_handler' => function ($object) {
                return $object->getId();  // On retourne simplement l'ID pour éviter une boucle infinie
            },
        ];
        $freelanceJson = $this->freelanceSerializer->serializeFreelance($freelance, ['freelance_detail']);
        dump($freelanceJson);
        return Command::SUCCESS;
    }
}
