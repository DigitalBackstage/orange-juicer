FROM digitalbackstage/php:1.1.0-alpine

ENV LANG='en_US.UTF-8' \
    LANGUAGE='en_US.UTF-8' \
    TERM='xterm'

ENV MEDIAINFO_VERSION='0.7.87'

RUN echo "@community http://nl.alpinelinux.org/alpine/edge/community" >> /etc/apk/repositories

RUN apk -U upgrade && \
    apk -U add \
        ca-certificates \
        make \
        g++ \
        shadow@community \
        xz \
        wget \
        zlib zlib-dev && \
    cd / && \
\
    wget "https://mediaarea.net/download/binary/mediainfo/${MEDIAINFO_VERSION}/MediaInfo_CLI_${MEDIAINFO_VERSION}_GNU_FromSource.tar.xz" \
        -O "/MediaInfo_CLI_${MEDIAINFO_VERSION}_GNU_FromSource.tar.xz" && \
    cd / && \
    tar xpf "/MediaInfo_CLI_${MEDIAINFO_VERSION}_GNU_FromSource.tar.xz" && \
\
    cd /MediaInfo_CLI_GNU_FromSource && \
    ./CLI_Compile.sh && \
    cd /MediaInfo_CLI_GNU_FromSource/MediaInfo/Project/GNU/CLI/ && \
    make install && \
\
    apk del \
    make \
    g++ \
    wget && \
    rm -rf "/MediaInfo_CLI_${MEDIAINFO_VERSION}_GNU_FromSource.tar.xz" && \
    rm -rf /MediaInfo_CLI_GNU_FromSource && \
    rm -rf /var/cache/apk/*

