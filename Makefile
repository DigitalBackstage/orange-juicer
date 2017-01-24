.PHONY: build

# To be run from the host
.env:
	cp $@.dist $@
	sed --in-place "s/{your_unix_local_username}/$(shell whoami)/" $@
	sed --in-place "s/{your_unix_local_uid}/$(shell id --user)/" $@

build:
	docker build --tag digital-backstage/mediainfo docker
