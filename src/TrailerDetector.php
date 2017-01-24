<?php

namespace DigitalBackstage\OrangeJuicer;

use League\Flysystem\Filesystem;

class TrailerDetector
{
    private $filesystem;

    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    public function getTrailerFilename(string $programFilename): string
    {
        return preg_replace(
            '|MPEG\d+\.\mpg|',
            'MPEG15_Trailer.mpg',
            $programFilename
        );
    }

    public function trailerExists(string $programFilename): bool
    {
        return $this->filesystem->has($this->getTrailerFilename($programFilename));
    }
}
