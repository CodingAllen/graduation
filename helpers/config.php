<?php
//define('DSN', 'sqlsrv:server=10.32.97.1\sotsu;database=22jn01_J');
//define('DB_USER', '22jn01_J');
//define('DB_PASSWORD', '22jn01_J');


define('DSN', getenv('MY_DB_DSN') ?: 'sqlsrv:server=10.32.97.1\sotsu;database=22jn01_J');
define('DB_USER', getenv('MY_DB_USER') ?: '22jn01_J');
define('DB_PASSWORD', getenv('MY_DB_PASSWORD') ?: '22jn01_J');
