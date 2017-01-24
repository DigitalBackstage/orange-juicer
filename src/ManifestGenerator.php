<?php

namespace DigitalBackstage\OrangeJuicer;

use DigitalBackstage\OrangeJuicer\MetadataProvider\FilePathAwareMetadataProvider;
use DigitalBackstage\OrangeJuicer\MetadataProvider\InputAwareMetadataProvider;
use DigitalBackstage\OrangeJuicer\MetadataProvider\MetadataProvider;
use League\Flysystem\FilesystemInterface;
use Symfony\Component\Serializer\Encoder\EncoderInterface;

class ManifestGenerator
{
    private $encoder;
    private $filesystem;
    private $metadataProviders;

    public function __construct(
        EncoderInterface $encoder,
        FilesystemInterface $filesystem,
        array $metadataProviders
    ) {
        $this->encoder = $encoder;
        $this->filesystem = $filesystem;
        $this->metadataProviders = [];
        foreach ($metadataProviders as $metadataProvider) {
            $this->addMetadataProvider($metadataProvider);
        }
    }

    /**
     * The sole benefit of this method is the type hinting.
     */
    private function addMetadataProvider(MetadataProvider $metadataProvider)
    {
        $this->metadataProviders[] = $metadataProvider;
    }

    public function generateProgramManifest(
        string $filePath,
        string $title,
        string $audioLanguage,
        string $subtitlingLanguage = null
    ) {
        return $this->generateManifest(
            $filePath,
            $title,
            $audioLanguage,
            $subtitlingLanguage,
            false
        );
    }

    public function generateTrailerManifest(
        string $filePath,
        string $title,
        string $audioLanguage,
        string $subtitlingLanguage = null
    ) {
        return $this->generateManifest(
            $filePath,
            $title,
            $audioLanguage,
            $subtitlingLanguage,
            true
        );
    }

    private function generateManifest(
        string $filePath,
        string $title,
        string $audioLanguage,
        string $subtitlingLanguage = null,
        bool $isTrailer
    ) {
        $manifestPath = "$filePath.xml";
        if ($this->filesystem->has($manifestPath)) {
            throw new \RuntimeException("$manifestPath already exists!");
        }
        $metadata = [];
        foreach ($this->metadataProviders as $metadataProvider) {
            if ($metadataProvider instanceof FilePathAwareMetadataProvider) {
                $metadataProvider->setFilePath($filePath);
            }
            if ($metadataProvider instanceof InputAwareMetadataProvider) {
                $metadataProvider->setInput(compact(
                    'isTrailer',
                    'subtitlingLanguage',
                    'audioLanguage',
                    'title'
                ));
            }
            $metadata = array_merge_recursive(
                $metadata,
                $metadataProvider->provideData()
            );
        }
        array_multisort($metadata);

        $this->filesystem->write($manifestPath, $this->encoder->encode(
            $metadata,
            'xml' /* duh! */,
            ['xml_format_output' => true, 'xml_encoding' => 'utf-8']
        ));
    }
}
