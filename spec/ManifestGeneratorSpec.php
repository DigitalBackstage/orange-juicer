<?php

namespace spec\DigitalBackstage\OrangeJuicer;

use DigitalBackstage\OrangeJuicer\ManifestGenerator;
use League\Flysystem\FilesystemInterface;
use Mhor\MediaInfo\Container\MediaInfoContainer;
use Mhor\MediaInfo\MediaInfo;
use Mhor\MediaInfo\Type\General;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Serializer\Encoder\EncoderInterface;
use Symfony\Component\Serializer\Encoder\XmlEncoder;

class ManifestGeneratorSpec extends ObjectBehavior
{
    function let(FilesystemInterface $filesystem)
    {
        $encoder = new XmlEncoder();
        $this->beConstructedWith(
            $encoder,
            $filesystem,
            []
        );
        $filesystem->getSize(Argument::any())->willReturn(42);
        $filesystem->readStream(Argument::any())->willReturn('a stream');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ManifestGenerator::class);
    }

    function it_produces_xml_encoded_in_utf8(FilesystemInterface $filesystem)
    {
        $filesystem->write(Argument::any(), Argument::containingString(
            'encoding="utf-8"'
        ))->shouldBeCalled();
        $this->generateManifest('movie.mpg', 'Some title', 'FRA', 'FRA');
    }

    function it_produces_a_manifest_containing_the_provided_title(
        FilesystemInterface $filesystem
    ) {
        $filesystem->write(Argument::any(), Argument::containingString(
            '<title_1>Some title</title_1>'
        ))->shouldBeCalled();
        $this->generateManifest('movie.mpg', 'Some title', 'FRA', 'FRA');
    }
}
