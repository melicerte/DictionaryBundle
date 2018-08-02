<?php

namespace Knp\DictionaryBundle\Command;

use Knp\DictionaryBundle\Dictionary\DictionaryRegistry;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Dump dictionaries with their related values.
 */
class DictionaryDumpCommand extends Command
{
    protected static $defaultName = 'knp:dictionary:dump';

    /**
     * @var DictionaryRegistry
     */
    private $registry;

    public function __construct(DictionaryRegistry $registry)
    {
        parent::__construct();
        $this->registry = $registry;
    }

    protected function configure()
    {
        $this
            ->setDescription('Dump Dictionaries')
            ->setHelp('This command allows you to dump KNP dictionnaries and their values')
            ->addArgument(
                'dictionary',
                InputArgument::OPTIONAL,
                'Dictionary name you want to display'
            )
            ->setHelp(<<<EOF
The <info>%command.name%</info> list all dictionaries with their related values.
You can choose to list a specific dictionary:

  <info>php %command.full_name% your_dictionary_name</info>
EOF
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dictionaryName = $input->getArgument('dictionary');
        $io = new SymfonyStyle($input, $output);
        $errorIo = $io->getErrorStyle();

        // Add output context text
        $io->title('KNP Dictionary');
        $io->text('Here are the dictionaries with their related key-values:');
        if (!\is_null($dictionaryName)) {
            $io->text("Search for dictionary named <fg=green>$dictionaryName</fg=green>");
        }
        $io->newLine();

        // Read dictionaries information
        if (\is_null($dictionaryName)) {
            $dictionaries = $this->registry->all();
        } else {
            $dictionaries = [$this->registry->get($dictionaryName)];
        }

        $tableRows = $this->getDictionariesDetail($dictionaries);

        // Output data
        if (\count($tableRows) > 0) {
            foreach ($tableRows as $header => $rows) {

                // If too big dictionary and many dictionaries are to list, then we cut the result
                if (\count($rows) > 15 && \is_null($dictionaryName)) {
                    $lessRows = array_slice($rows, 0, 14);
                    $this->table($output, $header, $lessRows);

                    // Support grep anyway by printing keys
                    $output->writeln('The table was cut, all these keys are available:');
                    $output->writeln(implode(', ', array_map(function ($row) {
                        return $row[0];
                    }, $rows)));
                } else {
                    $this->table($output, $header, $rows);
                }

                $output->writeln('');
            }
        } elseif (!\is_null($dictionaryName) && 0 === \count($tableRows)) {
            $errorIo->error("No dictionary named $dictionaryName");
        }
    }

    private function table(OutputInterface $output, $header, $rows)
    {
        $table = new Table($output);
        $table->setHeaders([$header])->setRows($rows);

        try {
            // box style only available in 4.1 version of Symfony
            $table->setStyle('box');
        } catch (InvalidArgumentException $e) {
            // Do nothing. It's ok if nice style is not available :)
        }

        $table->render();
    }

    /**
     * Get all dictionaries with they values.
     *
     * @param array $dictionaries
     *
     * @return array rows to display
     */
    private function getDictionariesDetail(array $dictionaries = [])
    {
        $tableRows = [];
        foreach ($dictionaries as $dico) {
            foreach ($dico as $key => $value) {
                $dictionaryName = "<fg=cyan>{$dico->getName()}</fg=cyan>";
                if (\is_array($value)) {
                    $tableRows[$dictionaryName][] = ["Category : {$key}"];
                    foreach ($value as $catKey => $catKey) {
                        $tableRows[$dictionaryName][] = [$catKey, $catKey];
                    }
                } else {
                    $tableRows[$dictionaryName][] = [$key, $value];
                }
            }
        }

        return $tableRows;
    }
}
