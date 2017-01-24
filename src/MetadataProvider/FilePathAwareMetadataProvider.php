<?php

namespace DigitalBackstage\OrangeJuicer\MetadataProvider;

interface FilePathAwareMetadataProvider extends MetadataProvider
{
    public function setFilePath(string $filePath);
}
