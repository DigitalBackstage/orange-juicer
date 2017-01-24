<?php

namespace DigitalBackstage\OrangeJuicer\MetadataProvider;

use DigitalBackstage\OrangeJuicer\FileHasher;
use League\Flysystem\FilesystemInterface;

final class FilesystemProvider implements FilePathAwareMetadataProvider
{
    use FilePathAwareMetadataProviderTrait;

    private $filesystem;
    private $fileHasher;

    public function __construct(FilesystemInterface $filesystem, FileHasher $fileHasher)
    {
        $this->filesystem = $filesystem;
        $this->fileHasher = $fileHasher;
    }

    public function provideData()
    {
        return [
            'multi_format_set' => [
                'encoding' => [
                    'file_size' => $this->filesystem->getSize($this->filePath),
                    'md5_sum' => $this->fileHasher->hash(
                        $this->filesystem->getAdapter()->applyPathPrefix($this->filePath)
                    ),
                ],
            ],
        ];
    }
}
