<?php

namespace spec\DigitalBackstage\OrangeJuicer\MetadataProvider;

use DigitalBackstage\OrangeJuicer\MetadataProvider\InputDataProvider;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class InputDataProviderSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(InputDataProvider::class);
    }

    public function it_should_provide_metadat_based_on_the_input()
    {
        $this->setInput([
            'title' => 'My super film',
            'isTrailer' => true,
            'audioLanguage' => 'FRA',
            'subtitlingLanguage' => 'ESL',
        ]);
        $this->provideData()
            ->shouldReturn([
                'title_1' => 'My super film',
                'multi_format_set' => [
                    'type' => 'trailer',
                    'encoding' => [
                        'audio_language_1' => 'FRA',
                        'subtitling_burned_1' => 'true',
                        'subtitling_language_1' => 'ESL',
                    ]
                ]
            ]);

        $this->setInput([
            'title' => 'My super film',
            'isTrailer' => false,
            'audioLanguage' => 'FRA',
            'subtitlingLanguage' => 'ESL',
        ]);
        $this->provideData()
            ->shouldReturn([
                'title_1' => 'My super film',
                'multi_format_set' => [
                    'type' => 'program',
                    'encoding' => [
                        'audio_language_1' => 'FRA',
                        'subtitling_burned_1' => 'true',
                        'subtitling_language_1' => 'ESL',
                        'subtitling_open_caption_1' => 'false',
                        'subtitling_impairedhearing_1' => 'false',
                    ]
                ]
            ]);
    }
}
