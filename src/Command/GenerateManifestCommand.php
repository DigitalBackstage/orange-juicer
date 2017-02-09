<?php

namespace DigitalBackstage\OrangeJuicer\Command;

use DigitalBackstage\OrangeJuicer\Console\OrangeJuicerStyle;
use DigitalBackstage\OrangeJuicer\ManifestGenerator;
use DigitalBackstage\OrangeJuicer\TrailerDetector;
use ISOCodes\ISO639_2\Adapter\Json;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateManifestCommand extends Command
{
    private $manifestGenerator;
    private $languageCodeProvider;

    public function __construct(
        ManifestGenerator $manifestGenerator,
        TrailerDetector $trailerDetector,
        Json $languageCodeProvider
    ) {
        parent::__construct('generate-manifest');
        $this->manifestGenerator = $manifestGenerator;
        $this->trailerDetector = $trailerDetector;
        $this->languageCodeProvider = $languageCodeProvider;
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
        $availableLanguages = [];
        foreach ($this->languageCodeProvider->getAll() as $code) {
            $availableLanguages[strtoupper($code->getAlpha3())] = strtolower(
                $code->getName()
            );
        }
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
            $availableLanguages,
            'FRA'
        );
        $subtitlingLanguage = $commandIo->choice(
            'Pick a subtitle language (optional)',
            $availableLanguages,
            null
        );
        $filePath = $input->getArgument('filePath');

        if ($this->trailerDetector->trailerExists($filePath)) {
            $commandIo->comment('Detected the trailer');
            $this->manifestGenerator->generateTrailerManifest(
                $this->trailerDetector->getTrailerFilename($filePath),
                $title,
                $audioLanguage,
                $subtitlingLanguage
            );
            $commandIo->success('The trailer manifest was successfully generated.');
        } else {
            $commandIo->note(
                'No trailer detected, skipping to program manifest generation'
            );
        }

        $commandIo->note('Generating the manifest, this may take some time');

        $this->manifestGenerator->generateProgramManifest(
            $filePath,
            $title,
            $audioLanguage,
            $subtitlingLanguage
        );
        $commandIo->success('The program manifest was successfully generated.');
    }
}
