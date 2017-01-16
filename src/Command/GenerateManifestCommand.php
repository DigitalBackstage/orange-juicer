<?php

namespace DigitalBackstage\OrangeJuicer\Command;

use DigitalBackstage\OrangeJuicer\ManifestGenerator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class GenerateManifestCommand extends Command
{
    private $manifestGenerator;

    public function __construct(ManifestGenerator $manifestGenerator)
    {
        parent::__construct('generate-manifest');
        $this->manifestGenerator = $manifestGenerator;
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
        $commandIo = new SymfonyStyle($input, $output);
        $commandIo->title("Let's generate a manifest, shall we?");
        $title = $commandIo->ask('Title', null, function ($value) {
            if (trim($value) === '') {
                throw new \UnexpectedValueException('The title cannot be empty');
            }

            return $value;
        });
        $audioLanguage = $commandIo->ask('Audio language', null, function ($value) {
            if (strlen($value) !== 3) {
                throw new \UnexpectedValueException(
                    'The audio language must be 3 letters long'
                );
            }

            return $value;
        });
        $subtitlingLanguage = $commandIo->ask('Subtitle language (optional)', null, function ($value) {
            if (is_null($value)) {
                return $value;
            }

            if (strlen($value) !== 3) {
                throw new \UnexpectedValueException(
                    'The audio language must be 3 letters long'
                );
            }

            return $value;
        });

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
