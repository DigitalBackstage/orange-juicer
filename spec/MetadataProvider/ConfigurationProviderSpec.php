<?php

namespace spec\DigitalBackstage\OrangeJuicer\MetadataProvider;

use DigitalBackstage\OrangeJuicer\MetadataProvider\ConfigurationProvider;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ConfigurationProviderSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('ACME', 'ACM');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ConfigurationProvider::class);
    }

    function it_provides_data_from_the_configuration() {
        $this->provideData()->shouldReturn([
            'PRODUCTION_COMPANY' => 'ACME',
            'multi_format_set' => [
                'firm' => 'ACM'
            ],
        ]);
    }
}
