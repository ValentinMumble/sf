Apache configuration

```lang-bsh
Alias "/sf" "/var/www/html/hk/symfony-server/public/"
<Directory "/var/www/html/hk/symfony-server/public/">
  AllowOverride All
  Require all granted
  Allow from All
</Directory>
```
