FROM postgres:11.5-alpine

RUN apk add --update \
    tzdata

ENV TZ=Asia/Almaty

RUN rm -rf /var/cache/apk/* && rm -rf /tmp/*

CMD ["postgres"]

EXPOSE 5432
