#!/bin/sh

FLAGS="-v /Users/hailong11/Documents/src/git.kkcoding.com/xcar/vr/php:/data/xcar"

docker run -d --name php-fpm  -v `pwd`:/data $FLAGS registry.cn-beijing.aliyuncs.com/kk/kk-php:latest

docker run -d --name nginx -p 8080:80 --link=php-fpm -v `pwd`:/data -v `pwd`/etc/nginx/conf.d:/etc/nginx/conf.d $FLAGS nginx
