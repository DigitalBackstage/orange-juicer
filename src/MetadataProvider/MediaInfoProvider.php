<?php

namespace DigitalBackstage\OrangeJuicer\MetadataProvider;

use Mhor\MediaInfo\MediaInfo;

final class MediaInfoProvider implements FilePathAwareMetadataProvider
{
    use FilePathAwareMetadataProviderTrait;

    private $mediaInfoWrapper;

    /**
     * Path to the directory where videos are stored.
     * Should include a trailing slash.
     */
    private $fsRoot;

    public function __construct(MediaInfo $mediaInfoWrapper, string $fsRoot)
    {
        $this->mediaInfoWrapper = $mediaInfoWrapper;
        $this->fsRoot = $fsRoot;
    }

    public function provideData()
    {
        $info = $this->mediaInfoWrapper
            ->getInfo($this->fsRoot . $this->filePath);
        $aspectRatio = (string) current($info->getVideos())
            ->get('display_aspect_ratio');

        list($width, $height) = explode(':', $aspectRatio);
        if (current($info->getVideos())->get('height')->getAbsoluteValue() === 1080) {
            // HD
            $encoding = [
                'bitrate' => '50000000',
                'profile' => 'MPG50',
                'aspect_ratio' => 'HD',
            ];

            if (current($info->getAudios())->get('commercial_name') === 'MPEG Audio') {
                $encoding += [
                    'audio_coding_1' => 'MPEG1',
                    'audio_mix_1' => 'STEREO',
                ];
            } else {
                $encoding += [
                    'audio_coding_1' => 'DolbyDigital',
                    'audio_mix_1' => '5.1',
                ];
            }
        } else {
            // SD
            $encoding = [
                'bitrate' => '15000000',
                'profile' => 'MPG15',
                'aspect_ratio' => sprintf('%d:%02d', $width, $height),
                'audio_coding_1' => 'MPEG1',
                'audio_mix_1' => 'STEREO',
            ];
        }

        return [
            'multi_format_set' => [
                'encoding' => $encoding + [
                    'duration' => (int) round(// round gives a more precise result
                        // we want seconds, and we get milliseconds
                        (string) $info->getGeneral()->get('duration') / 1000
                    ),
                ],
            ],
        ];
    }
}
