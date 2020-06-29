<?php

namespace App;

use App\Engine\Wikipedia\WikipediaEngine;
use App\Engine\Wikipedia\WikipediaParser;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpClient\HttpClient;

class Wikipedia extends Command
{
    protected function configure()
    {
        $this->setName('wikipedia');
        $this->addArgument('busca');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $wiki = new WikipediaEngine(new WikipediaParser(), HttpClient::create());
        $result = $wiki->search($input->getArgument('busca'));

        $iterator = $result->getIterator();
        $rows = [];
        foreach ($iterator as $item) {
            $title = $item->getTitle();
            $preview = $item->getPreview();
            $rows[] = [$title,$preview];
        }
        $resultsTable = new Table($output);
        $resultsTable->setHeaders(['Titulo', 'Resumo'])->setRows($rows);
        $resultsTable->render();
        return 0;
    }
}
