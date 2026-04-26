#!/bin/sh
set -eu

: "${PIKACHU_DB_HOST:=db}"
: "${PIKACHU_DB_PORT:=3306}"
: "${PIKACHU_DB_NAME:=pikachu}"
: "${PIKACHU_DB_USER:=root}"
: "${PIKACHU_DB_PASSWORD:=root}"

cat > /var/www/html/inc/config.inc.php <<PHP
<?php
session_start();
date_default_timezone_set('Asia/Shanghai');
header('Content-type:text/html;charset=utf-8');

define('DBHOST', '${PIKACHU_DB_HOST}');
define('DBUSER', '${PIKACHU_DB_USER}');
define('DBPW', '${PIKACHU_DB_PASSWORD}');
define('DBNAME', '${PIKACHU_DB_NAME}');
define('DBPORT', '${PIKACHU_DB_PORT}');
?>
PHP

exec "$@"
