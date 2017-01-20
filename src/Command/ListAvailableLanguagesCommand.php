<?php

namespace DigitalBackstage\OrangeJuicer\Command;

use DigitalBackstage\OrangeJuicer\Console\OrangeJuicerStyle;
use DigitalBackstage\OrangeJuicer\ManifestGenerator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Yaml\Yaml;

class ListAvailableLanguagesCommand extends Command
{
    private $availableLanguages;

    public function __construct(array $availableLanguages)
    {
        parent::__construct('list-languages');
        $this->availableLanguages = $availableLanguages;
    }

    public function configure()
    {
        $this->setDescription(
            'List languages that are supported by orange juicer'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        foreach ($this->availableLanguages as $code => $language) {
            $output->writeln("$code: $language");
        }
    }
}
