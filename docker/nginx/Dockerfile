FROM nginx:latest

ARG ENVIRONMENT

ENV ENVIRONMENT=$ENVIRONMENT

COPY conf.d/${ENVIRONMENT}/app.conf /etc/nginx/conf.d/app.conf

WORKDIR /var/www/html

EXPOSE 80

CMD ["nginx", "-g", "daemon off;"]