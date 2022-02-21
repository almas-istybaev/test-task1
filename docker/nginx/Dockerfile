FROM alpine:latest

RUN apk add --update \
    nginx \
    bash \
    curl \
    tzdata

ENV TZ=Asia/Almaty

RUN rm -rf /var/cache/apk/* && rm -rf /tmp/*

ADD nginx.conf /etc/nginx/

RUN adduser -D -g '' -G www-data www-data && \
    echo "upstream phpupstream { server test-task1_app:9000; }" > /etc/nginx/conf.d/upstream.conf && \
    rm /etc/nginx/conf.d/default.conf

RUN chown -R www-data:www-data /var/lib/nginx

ADD sites-available/test-task1.conf /etc/nginx/sites-available/test-task1.conf

CMD ["nginx", "-g", "daemon off;"]

EXPOSE 80
