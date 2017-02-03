# Orange Juicer

Extracts tasty metadata from a master file and produces an XML file as
specified by [Orange](http://www.orange.fr/).

## Usage

We're assuming your master files are stored in `/path/to/master`,
you have to name them depending on their Orange `IDWON`, like follows: `IDWON_MPEG.mpg`.
An XML manifest named `IDWON_MPEG.mpg.xml` should be produced.

### Docker image

```sh
docker run -it \
	-v ~/path/to/master:/data \
	-e FS_ROOT='/data' \
	-e PRODUCTION_COMPANY='MADE_UP_COMPANY' \
	-e FIRM='MUC' \
	digitalbackstage/orange-juicer su-exec $(id -u):$(id -g) \
	bin/orange_juicer generate-manifest IDWON_MPEG.mpg
```

### Native usage

Install php, Composer and then, you may run, from the directory of the project:

```sh
composer install
FS_ROOT='/path/to/master' PRODUCTION_COMPANY='MADE_UP_COMPANY' FIRM='MUC' \
	bin/orange_juicer generate-manifest IDWON_MPEG.mpg
```
