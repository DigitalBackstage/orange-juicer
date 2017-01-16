<?php

namespace DigitalBackstage\OrangeJuicer\MetadataProvider;

final class ConfigurationProvider implements MetadataProvider
{
    private $productionCompany;
    private $firm;

    public function __construct(string $productionCompany, string $firm)
    {
        $this->productionCompany = $productionCompany;
        $this->firm = $firm;
    }

    public function provideData()
    {
        return [
            'PRODUCTION_COMPANY' => $this->productionCompany,
            'multi_format_set' => [
                'firm' => $this->firm,
            ],
        ];
    }
}
