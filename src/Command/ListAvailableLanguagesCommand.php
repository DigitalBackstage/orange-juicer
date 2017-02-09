<?php

namespace DigitalBackstage\OrangeJuicer\Command;

use ISOCodes\ISO639_2\Adapter\Json;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ListAvailableLanguagesCommand extends Command
{
    private $languageCodeProvider;

    public function __construct(Json $languageCodeProvider)
    {
        parent::__construct('list-languages');
        $this->languageCodeProvider = $languageCodeProvider;
    }

    public function configure()
    {
        $this->setDescription(
            'List languages that are supported by orange juicer'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        foreach ($this->languageCodeProvider->getAll() as $language) {
            $output->writeln(sprintf(
                '%s: %s',
                strtoupper($language->getAlpha3()),
                $language->getName()
            ));
        }
    }
}
