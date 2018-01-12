FROM nginx:1.10

RUN \
  echo "deb http://ftp.debian.org/debian jessie-backports main" > /etc/apt/sources.list.d/backports.list \
  && apt-get update && apt-get install -y certbot -t jessie-backports

