Apache configuration

```lang-bsh
Alias "/sf" "/var/www/html/hk/symfony-server/public/"
<Directory "/var/www/html/hk/symfony-server/public/">
  AllowOverride None
  Require all granted
  Allow from All
  FallbackResource /index.php
  RewriteEngine On
  RewriteCond %{REQUEST_URI}::$1 ^(/.+)/(.*)::\2$
  RewriteRule ^(.*) - [E=BASE:%1]
  RewriteCond %{HTTP:Authorization} .
  RewriteRule ^ - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]
  RewriteCond %{ENV:REDIRECT_STATUS} ^$
  RewriteRule ^index\.php(?:/(.*)|$) %{ENV:BASE}/$1 [R=301,L]
  RewriteCond %{REQUEST_FILENAME} -f
  RewriteRule ^ - [L]
  RewriteRule ^ %{ENV:BASE}/index.php [L]
</Directory>
```
