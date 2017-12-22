<?php

namespace Knp\DictionaryBundle\Command;

use Knp\DictionaryBundle\Dictionary;
use Knp\DictionaryBundle\Dictionary\DictionaryRegistry;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Dump dictionaries with their related values
 */
class DictionaryDumpCommand extends Command
{
    protected static $defaultName = 'knp:dictionary:dump';

    /**
     * @var DictionaryRegistry $registry
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
        $tableRows = $this->getDictionariesDetail($dictionaryName);

        // Output data
        if (\sizeof($tableRows) > 0) {
            $io->table([], $tableRows);
        } elseif (!\is_null($dictionaryName) && 0 === \sizeof($tableRows)) {
            $errorIo->error("No dictionary named $dictionaryName");
        }
    }

    /**
     * Get all dictionaries with they values
     * If $dictionaryName is set, only display dictionary matching dictionary
     *
     * @param null|string $dictionaryName the dictionary name asked for filtering
     * @return array rows to display
     */
    private function getDictionariesDetail($dictionaryName = null)
    {
        $tableRows = [];
        /** @var Dictionary $dico */
        foreach ($this->registry->all() as $dico) {
            if (!\is_null($dictionaryName) && $dictionaryName === $dico->getName() || \is_null($dictionaryName)) {
                $tableRows[] = ["<fg=cyan>{$dico->getName()}</fg=cyan>"];
                foreach ($dico as $key => $value) {
                    $tableRows[] = ["   $key\t| $value"];
                }
            }
        }

        return $tableRows;
    }
}
