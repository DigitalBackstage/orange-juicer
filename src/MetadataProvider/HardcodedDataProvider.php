<?php

namespace DigitalBackstage\OrangeJuicer\MetadataProvider;

final class HardcodedDataProvider implements MetadataProvider
{
    public function provideData()
    {
        return [
            'multi_format_set' => [
                'type' => 'program',
                'encoding' => [
                    'platform' => 'CMS',
                    'delivery' => 'CE',
                    'color' => 'Color',
                    'file_format' => 'MPG',
                    'video_coding' => 'MPEG2',
                ],
            ],
        ];
    }
}
