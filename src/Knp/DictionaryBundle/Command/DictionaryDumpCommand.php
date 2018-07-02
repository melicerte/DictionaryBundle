<?php

namespace Knp\DictionaryBundle\Command;

use Knp\DictionaryBundle\Dictionary\DictionaryRegistry;
use Symfony\Component\Console\Command\Command;
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
        if (\sizeof($tableRows) > 0) {
            foreach ($tableRows as $header => $raws) {
                $io->table([$header], $raws);
            }
        } elseif (!\is_null($dictionaryName) && 0 === \sizeof($tableRows)) {
            $errorIo->error("No dictionary named $dictionaryName");
        }
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
                $tableRows["<fg=cyan>{$dico->getName()}</fg=cyan>"][] = [$key, $value];
            }
        }

        return $tableRows;
    }
}
