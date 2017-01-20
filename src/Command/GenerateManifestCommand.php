<?php

namespace DigitalBackstage\OrangeJuicer\Command;

use DigitalBackstage\OrangeJuicer\Console\OrangeJuicerStyle;
use DigitalBackstage\OrangeJuicer\ManifestGenerator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class GenerateManifestCommand extends Command
{
    private $manifestGenerator;
    private $availableLanguages;

    public function __construct(
        ManifestGenerator $manifestGenerator,
        array $availableLanguages
    ) {
        parent::__construct('generate-manifest');
        $this->manifestGenerator = $manifestGenerator;
        $this->availableLanguages = $availableLanguages;
    }

    public function configure()
    {
        $this->setDescription(
            'Gets metadata from a master file, and generates an XML manifest'
        )
            ->addArgument(
                'filePath',
                InputArgument::REQUIRED,
                'absolute path to the file from which to extract information'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $commandIo = new OrangeJuicerStyle($input, $output);
        $commandIo->title("Let's generate a manifest, shall we?");
        $title = $commandIo->ask('Title', null, function ($value) {
            if (trim($value) === '') {
                throw new \UnexpectedValueException('The title cannot be empty');
            }

            return $value;
        });
        $audioLanguage = $commandIo->choice(
            'Pick an audio language (defaults to french)',
            $this->availableLanguages,
            'FRA'
        );
        $subtitlingLanguage = $commandIo->choice(
            'Pick a subtitle language (optional)',
            $this->availableLanguages,
            null
        );

        $commandIo->note('Generating the manifest, this may take some time');

        $this->manifestGenerator->generateManifest(
            $input->getArgument('filePath'),
            $title,
            $audioLanguage,
            $subtitlingLanguage
        );
        $commandIo->success('The manifest was successfully generated.');
    }
}
