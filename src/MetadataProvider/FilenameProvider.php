<?php

namespace DigitalBackstage\OrangeJuicer\MetadataProvider;

final class FilenameProvider implements FilePathAwareMetadataProvider
{
    use FilePathAwareMetadataProviderTrait;

    public function provideData()
    {
        if (!preg_match('#_MPEG.[mM][pP][gG]$#', $this->filePath)) {
            throw new \UnexpectedValueException(sprintf(
                'file "%s" is badly named',
                $this->filePath
            ));
        }
        return [
            'id_won' => $idWon = substr(
                pathinfo($this->filePath, PATHINFO_FILENAME),
                0,
                - strlen('_MPEG')
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
