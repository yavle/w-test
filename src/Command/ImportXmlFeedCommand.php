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
    public function __construct(private ImportXmlProductFeedService $service)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('url', InputArgument::REQUIRED, 'Xml product feed url')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $url = $input->getArgument('url');

        if ($url) {
            $io->note(sprintf('Reading feed: %s', $url));
        }

        if ($input->getOption('option1')) {
            // ...
        }
        $this->service->loadFeed($url);
        $io->success('Feed has been successfully downloaded.');

        return Command::SUCCESS;
    }
}
