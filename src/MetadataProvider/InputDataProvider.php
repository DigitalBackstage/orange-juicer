<?php

namespace DigitalBackstage\OrangeJuicer\MetadataProvider;

class InputDataProvider implements InputAwareMetadataProvider
{
    private $input;

    public function setInput(array $input)
    {
        $this->input = $input;
    }

    public function provideData()
    {
        $isTrailer = $this->input['isTrailer'];
        $encoding = [
            'audio_language_1' => $this->input['audioLanguage'],
        ];
        if (isset($this->input['subtitlingLanguage'])) {
            $encoding += [
                'subtitling_burned_1' => 'true',
                'subtitling_language_1' => $this->input['subtitlingLanguage'],
            ];

            if (!$this->input['isTrailer']) {
                $encoding += [
                    'subtitling_open_caption_1' => 'false',
                    'subtitling_impairedhearing_1' => 'false',
                ];
            }
        }

        return [
            'title_1' => $this->input['title'],
            'multi_format_set' => [
                'type' => $isTrailer ? 'trailer': 'program',
                'encoding' => $encoding,
            ],
        ];
    }
}
