#!/bin/bash
certbot renew --quiet --nginx >> /var/log/letsencrypt/renew.log 2>&1