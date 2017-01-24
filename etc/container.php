<?php

use DigitalBackstage\OrangeJuicer\Command\GenerateManifestCommand;
use DigitalBackstage\OrangeJuicer\Command\ListAvailableLanguagesCommand;
use DigitalBackstage\OrangeJuicer\ManifestGenerator;
use DigitalBackstage\OrangeJuicer\Md5FileHasher;
use DigitalBackstage\OrangeJuicer\MetadataProvider\ConfigurationProvider;
use DigitalBackstage\OrangeJuicer\MetadataProvider\FilenameProvider;
use DigitalBackstage\OrangeJuicer\MetadataProvider\FilesystemProvider;
use DigitalBackstage\OrangeJuicer\MetadataProvider\HardcodedDataProvider;
use DigitalBackstage\OrangeJuicer\MetadataProvider\InputDataProvider;
use DigitalBackstage\OrangeJuicer\MetadataProvider\MediaInfoProvider;
use DigitalBackstage\OrangeJuicer\TrailerDetector;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use Mhor\MediaInfo\MediaInfo;
use Symfony\Component\Console\Application;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Yaml\Yaml;

if (!isset($_ENV['FS_ROOT'])) {
    $dotEnv = new Dotenv();
    $dotEnv->load(__DIR__ . '/../.env');
}

$container = new ContainerBuilder();

$container->register('local_fs_adapter', Local::class)
    ->addArgument($_ENV['FS_ROOT']);

$container->register('filesystem', Filesystem::class)
    ->addArgument(new Reference('local_fs_adapter'));

$container->register('file_hasher', Md5FileHasher::class);

$container->register('mediainfo', MediaInfo::class)
    ->setPublic(false);

$container->register('trailer_detector', TrailerDetector::class)
    ->addArgument(new Reference('filesystem'))
    ->setPublic(false);

$container->register('manifest_encoder', XmlEncoder::class)
    ->addArgument('product')
    ->setPublic(false);

$container->register('hardcoded_data_provider', HardcodedDataProvider::class)
    ->setPublic(false);

$container->register('input_data_provider', InputDataProvider::class)
    ->setPublic(false);

$container->register('config_data_provider', ConfigurationProvider::class)
    ->setArguments([$_ENV['PRODUCTION_COMPANY'], $_ENV['FIRM']])
    ->setPublic(false);

$container->register('filename_data_provider', FilenameProvider::class)
    ->setPublic(false);

$container->register('mediainfo_data_provider', MediaInfoProvider::class)
    ->setArguments([new Reference('mediainfo'), '/data/'])
    ->setPublic(false);

$container->register('filesystem_data_provider', FilesystemProvider::class)
    ->setArguments([
        new Reference('filesystem'),
        new Reference('file_hasher'),
    ])
    ->setPublic(false);


$container->register('manifest_generator', ManifestGenerator::class)
    ->setPublic(false)
    ->setArguments([
        new Reference('manifest_encoder'),
        new Reference('filesystem'),
        [
            new Reference('hardcoded_data_provider'),
            new Reference('config_data_provider'),
            new Reference('input_data_provider'),
            new Reference('filename_data_provider'),
            new Reference('mediainfo_data_provider'),
            new Reference('filesystem_data_provider'),
        ]
    ]);

$container->setParameter(
    'available_languages',
    Yaml::parse(file_get_contents(__DIR__ . '/languages.yml'))
);

$container->register('generate_manifest_command', GenerateManifestCommand::class)
    ->setPublic(false)
    ->setArguments([
        new Reference('manifest_generator'),
        new Reference('trailer_detector'),
        $container->getParameter('available_languages')
    ]);

$container->register('list_languages_command', ListAvailableLanguagesCommand::class)
    ->setPublic(false)
    ->addArgument($container->getParameter('available_languages'));


$container->register('application', Application::class)
    ->addArgument('orange_juicer')
    ->addMethodCall('add', [new Reference('generate_manifest_command')])
    ->addMethodCall('add', [new Reference('list_languages_command')]);

return $container;
