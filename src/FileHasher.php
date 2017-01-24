<?php

namespace DigitalBackstage\OrangeJuicer;

interface FileHasher
{
    /**
     * @param string $filePath absolute file path
     *
     * @return string a hash
     */
    public function hash(string $filePath): string;
}
