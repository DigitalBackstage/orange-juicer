<?php

namespace spec\DigitalBackstage\OrangeJuicer\MetadataProvider;

use DigitalBackstage\OrangeJuicer\MetadataProvider\MediaInfoProvider;
use Mhor\MediaInfo\Attribute\Rate;
use Mhor\MediaInfo\Container\MediaInfoContainer;
use Mhor\MediaInfo\MediaInfo;
use Mhor\MediaInfo\Type\Audio;
use Mhor\MediaInfo\Type\General;
use Mhor\MediaInfo\Type\Video;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class MediaInfoProviderSpec extends ObjectBehavior
{
    function let(MediaInfo $mediaInfo)
    {
        $this->beConstructedWith($mediaInfo, '/path/to/videos/');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(MediaInfoProvider::class);
    }

    function it_produces_specific_data_for_sd(
        MediaInfo $mediaInfo,
        MediaInfoContainer $mediaInfoContainer,
        General $generalInfo,
        Video $video
    ) {
        $mediaInfo->getInfo('/path/to/videos/file.mpg')->willReturn($mediaInfoContainer);
        $mediaInfoContainer->getGeneral()->willReturn($generalInfo);
        $mediaInfoContainer->getVideos()->willReturn([$video]);
        $generalInfo->get('duration')->shouldBeCalled()->willReturn(6542);
        $video->get('display_aspect_ratio')->willReturn('16:9');
        $video->get('height')->willReturn(new Rate('720', '720 px'));
        $this->setFilePath('file.mpg');
        $this->provideData()->shouldReturn([
            'multi_format_set' => [
                'encoding' => [
                    'bitrate' => '15000000',
                    'profile' => 'MPG15',
                    'aspect_ratio' => '16:09',
                    'audio_coding_1' => 'MPEG1',
                    'audio_mix_1' => 'STEREO',
                    'duration' => 7,
                ]
            ],
        ]);
    }

    function it_produces_specific_data_for_hd(
        MediaInfo $mediaInfo,
        MediaInfoContainer $mediaInfoContainer,
        General $generalInfo,
        Video $video,
        Audio $audio
    ) {
        $mediaInfo->getInfo('/path/to/videos/file.mpg')->willReturn($mediaInfoContainer);
        $mediaInfoContainer->getGeneral()->willReturn($generalInfo);
        $mediaInfoContainer->getVideos()->willReturn([$video]);
        $mediaInfoContainer->getAudios()->willReturn([$audio]);
        $audio->get('commercial_name')->shouldBeCalled()->willReturn('MPEG Audio');
        $generalInfo->get('duration')->shouldBeCalled()->willReturn(6542);
        $video->get('display_aspect_ratio')->willReturn('16:9');
        $video->get('height')->willReturn(new Rate('1080', '1 080 px'));
        $this->setFilePath('file.mpg');
        $this->provideData()->shouldReturn([
            'multi_format_set' => [
                'encoding' => [
                    'bitrate' => '50000000',
                    'profile' => 'MPG50',
                    'aspect_ratio' => 'HD',
                    'audio_coding_1' => 'MPEG1',
                    'audio_mix_1' => 'STEREO',
                    'duration' => 7,
                ]
            ],
        ]);
    }
}
