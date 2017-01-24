<?php

namespace DigitalBackstage\OrangeJuicer\MetadataProvider;

interface InputAwareMetadataProvider extends MetadataProvider
{
    public function setInput(array $input);
}
