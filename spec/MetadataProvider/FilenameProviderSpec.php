<?php

namespace spec\DigitalBackstage\OrangeJuicer\MetadataProvider;

use DigitalBackstage\OrangeJuicer\MetadataProvider\FilenameProvider;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FilenameProviderSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(FilenameProvider::class);
    }

    function it_provides_data_from_the_filename()
    {
        $this->setFilePath('CHOUFXXXXXXW0117603_MPEG.mpg');
        $this->provideData()
            ->shouldReturn([
                'id_won' => 'CHOUFXXXXXXW0117603',
                'multi_format_set' => [
                    'encoding' => [
                        'job_id' => 'CHOUFXXXXXXW0117603',
                        'file_name' => 'CHOUFXXXXXXW0117603_MPEG.mpg',
                    ]
                ],
            ]);
    }

    function it_takes_the_optional_suffix_into_account()
    {
        $this->setFilePath('CHOUFXXXXXXW0117603_MPEG50.mpg');
        $this->provideData()
            ->shouldReturn([
                'id_won' => 'CHOUFXXXXXXW0117603',
                'multi_format_set' => [
                    'encoding' => [
                        'job_id' => 'CHOUFXXXXXXW0117603',
                        'file_name' => 'CHOUFXXXXXXW0117603_MPEG50.mpg',
                    ]
                ],
            ]);
    }

    public function it_supports_trailer_suffix()
    {
        $this->setFilePath('CHOUFXXXXXXW0117603_MPEG15_Trailer.mpg');
        $this->provideData()
            ->shouldReturn([
                'id_won' => 'CHOUFXXXXXXW0117603',
                'multi_format_set' => [
                    'encoding' => [
                        'job_id' => 'CHOUFXXXXXXW0117603',
                        'file_name' => 'CHOUFXXXXXXW0117603_MPEG15_Trailer.mpg',
                    ]
                ],
            ]);
    }
}
