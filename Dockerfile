FROM bingewave/php8-fpm-java-python-node-base:latest

RUN apt install -y zip unzip zlib1g-dev libzip-dev

RUN docker-php-ext-install zip

# Install mhsendmail
ENV GOLANG_VERSION 1.17.1

RUN curl -sSL https://storage.googleapis.com/golang/go$GOLANG_VERSION.linux-arm64.tar.gz \
		| tar -v -C /usr/local -xz

ENV PATH /usr/local/go/bin:$PATH

RUN mkdir -p /go/src /go/bin && chmod -R 777 /go
ENV GOROOT /usr/local/go
ENV GOPATH /go

RUN go get github.com/mailhog/mhsendmail
RUN cp /go/bin/mhsendmail /usr/local/bin/mhsendmail
COPY ./.docker/local/php.ini /usr/local/etc/php/conf.d/docker-php-mhsendmail.ini

    
RUN curl -sS https://getcomposer.org/installer | \
    php -- --install-dir=/usr/bin/ --filename=composer
