<?php

namespace DigitalBackstage\OrangeJuicer\MetadataProvider;

trait FilePathAwareMetadataProviderTrait
{
    private $filePath;

    public function setFilePath(string $filePath)
    {
        $this->filePath = $filePath;
    }
}
