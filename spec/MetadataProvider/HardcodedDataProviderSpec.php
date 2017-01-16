<?php

namespace spec\DigitalBackstage\OrangeJuicer\MetadataProvider;

use DigitalBackstage\OrangeJuicer\MetadataProvider\HardcodedDataProvider;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class HardcodedDataProviderSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(HardcodedDataProvider::class);
    }

    function it_produces_a_manifest_containing_the_necessary_hardcoded_fields()
    {
        $this->provideData()->shouldReturn([
            'multi_format_set' => [
                'type' => 'program',
                'encoding' => [
                    'platform' => 'CMS',
                    'delivery' => 'CE',
                    'color' => 'Color',
                    'file_format' => 'MPG',
                    'video_coding' => 'MPEG2'
                ]
            ]
        ]);
    }
}
