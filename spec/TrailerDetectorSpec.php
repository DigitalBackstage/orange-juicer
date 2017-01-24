<?php

namespace spec\DigitalBackstage\OrangeJuicer;

use DigitalBackstage\OrangeJuicer\TrailerDetector;
use League\Flysystem\Filesystem;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class TrailerDetectorSpec extends ObjectBehavior
{
    function let(Filesystem $filesystem)
    {
        $this->beConstructedWith($filesystem);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(TrailerDetector::class);
    }

    function it_can_compute_the_trailer_filename_from_the_program_filename()
    {
        $this->getTrailerFilename('FRONTERASXXW0118000_MPEG50.mpg')
            ->shouldBe('FRONTERASXXW0118000_MPEG15_Trailer.mpg');
    }

    function it_finds_existing_trailers(Filesystem $filesystem)
    {
        $filesystem->has('FRONTERASXXW0118000_MPEG15_Trailer.mpg')
            ->shouldBeCalled()
            ->willReturn(true);
        $this->trailerExists('FRONTERASXXW0118000_MPEG50.mpg')
            ->shouldBe(true);
    }
}
