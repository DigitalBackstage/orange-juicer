<?php

namespace DigitalBackstage\OrangeJuicer;

final class Md5FileHasher implements FileHasher
{
    /**
     * @param string $filePath absolute file path
     *
     * @return string an md5 hash
     */
    public function hash(string $filePath): string
    {
        return md5_file($filePath);
    }
}
