<?php

namespace spec\DigitalBackstage\OrangeJuicer\MetadataProvider;

use DigitalBackstage\OrangeJuicer\FileHasher;
use DigitalBackstage\OrangeJuicer\MetadataProvider\FilesystemProvider;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FilesystemProviderSpec extends ObjectBehavior
{
    function let(Filesystem $filesystem, FileHasher $fileHasher)
    {
        $this->beConstructedWith($filesystem, $fileHasher);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(FilesystemProvider::class);
    }

    function it_produces_data_containing_the_filesize_and_md5_sum(
        Filesystem $filesystem,
        FileHasher $fileHasher,
        Local $adapter
    ) {
        $filesystem->getSize('file.mpg')->shouldBeCalled()->willReturn(42);
        $filesystem->getAdapter()
            ->shouldBeCalled()
            ->willReturn($adapter);
        $adapter->applyPathPrefix('file.mpg')
            ->willReturn('/data/file.mpg');
        $fileHasher->hash('/data/file.mpg')
            ->shouldBeCalled()
            ->willReturn('b6472a8832d812e63b0d687b09e953ad');
        $this->setFilePath('file.mpg');
        $this->provideData()->shouldReturn([
            'multi_format_set' => [
                'encoding' => [
                    'file_size' => 42,
                    'md5_sum' => 'b6472a8832d812e63b0d687b09e953ad'
                ]
            ],
        ]);
    }
}
