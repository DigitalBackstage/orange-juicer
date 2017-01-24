<?php

namespace DigitalBackstage\OrangeJuicer\MetadataProvider;

final class FilenameProvider implements FilePathAwareMetadataProvider
{
    use FilePathAwareMetadataProviderTrait;

    public function provideData()
    {
        if (!preg_match('#_MPEG(\d+)?(_Trailer)?.[mM][pP][gG]$#', $this->filePath, $matches)) {
            throw new \UnexpectedValueException(sprintf(
                'file "%s" is badly named',
                $this->filePath
            ));
        }
        return [
            'id_won' => $idWon = substr(
                $this->filePath,
                0,
                - strlen($matches[0])
            ),
            'multi_format_set' => [
                'encoding' => [
                    'job_id' => $idWon,
                    'file_name' => $this->filePath,
                ],
            ],
        ];
    }
}
