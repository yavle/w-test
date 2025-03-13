<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

use App\Application\ImportXmlProductFeedService;

#[AsCommand(
    name: 'app:import-xml',
    description: 'This command loads an xml feed from a given url',
)]
class ImportXmlFeedCommand extends Command
{
    private $batchSize = 200;

    public function __construct(private ImportXmlProductFeedService $service)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('url', InputArgument::REQUIRED, 'Xml product feed url')
            ->addOption('batch', 'b', InputOption::VALUE_OPTIONAL, "Write batch size (default {$this->batchSize})")
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $url = $input->getArgument('url');

        $init = microtime(1);

        if ($input->getOption('batch') && (int)$input->getOption('batch') != 0) {
            $this->batchSize = (int)$input->getOption('batch');
        }
        
        $io->note(sprintf('Reading feed: %s, batch size: %s', $url, $this->batchSize));


        $this->service->loadFeed($url, $this->batchSize);

        $io->success('Feed has been successfully downloaded in: ' . (microtime(1) - $init));

        return Command::SUCCESS;
    }
}
