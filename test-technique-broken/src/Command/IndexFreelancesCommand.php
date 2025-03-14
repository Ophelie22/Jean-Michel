<?php

namespace App\Command;

use App\Entity\Freelance;
use App\Service\FreelanceSearchService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class IndexFreelancesCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private FreelanceSearchService $searchService
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('app:index-freelances')
            ->setDescription('Index all freelances in Elasticsearch');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $freelances = $this->entityManager->getRepository(Freelance::class)->findAll();
        
        foreach ($freelances as $freelance) {
            $this->searchService->indexFreelance($freelance);
        }

        $output->writeln('All freelances have been indexed.');
        return Command::SUCCESS;
    }
} 