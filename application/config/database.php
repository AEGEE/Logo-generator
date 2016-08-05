<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------
| DATABASE CONNECTIVITY SETTINGS
| -------------------------------------------------------------------
| This file will contain the settings needed to access your database.
|
| For complete instructions please consult the 'Database Connection'
| page of the User Guide.
|
| -------------------------------------------------------------------
| EXPLANATION OF VARIABLES
| -------------------------------------------------------------------
|
|	['hostname'] The hostname of your database server.
|	['username'] The username used to connect to the database
|	['password'] The password used to connect to the database
|	['database'] The name of the database you want to connect to
|	['dbdriver'] The database type. ie: mysql.  Currently supported:
				 mysql, mysqli, postgre, odbc, mssql, sqlite, oci8
|	['dbprefix'] You can add an optional prefix, which will be added
|				 to the table name when using the  Active Record class
|	['pconnect'] TRUE/FALSE - Whether to use a persistent connection
|	['db_debug'] TRUE/FALSE - Whether database errors should be displayed.
|	['cache_on'] TRUE/FALSE - Enables/disables query caching
|	['cachedir'] The path to the folder where cache files should be stored
|	['char_set'] The character set used in communicating with the database
|	['dbcollat'] The character collation used in communicating with the database
|				 NOTE: For MySQL and MySQLi databases, this setting is only used
| 				 as a backup if your server is running PHP < 5.2.3 or MySQL < 5.0.7
|				 (and in table creation queries made with DB Forge).
| 				 There is an incompatibility in PHP with mysql_real_escape_string() which
| 				 can make your site vulnerable to SQL injection if you are using a
| 				 multi-byte character set and are running versions lower than these.
| 				 Sites using Latin-1 or UTF-8 database character set and collation are unaffected.
|	['swap_pre'] A default table prefix that should be swapped with the dbprefix
|	['autoinit'] Whether or not to automatically initialize the database.
|	['stricton'] TRUE/FALSE - forces 'Strict Mode' connections
|							- good for ensuring strict SQL while developing
|
| The $active_group variable lets you choose which connection group to
| make active.  By default there is only one group (the 'default' group).
|
| The $active_record variables lets you determine whether or not to load
| the active record class
*/

$active_group = 'logo';
$query_builder = TRUE;

$db['logo'] = array(
	'dsn'	=> '',
	'hostname' => 'localhost',
	'username' => 'logo-generator',
	'password' => 'YZ*WLf:GvZ3;PSgQ+\o#BcI+#4Ws',
	'database' => 'logo-generator',
	'database2' => 'ab',
	'dbdriver' => 'mysqli',
	'dbprefix' => '',
	'pconnect' => FALSE,
	'db_debug' => (ENVIRONMENT !== 'production'),
	'cache_on' => FALSE,
	'cachedir' => '',
	'char_set' => 'utf8',
	'dbcollat' => 'utf8_general_ci',
	'swap_pre' => '',
	'encrypt' => FALSE,
	'compress' => FALSE,
	'stricton' => FALSE,
	'failover' => array(),
	'save_queries' => TRUE
);


/*
$db['logo']['hostname'] = 'localhost';
$db['logo']['username'] = 'logo-generator';
$db['logo']['password'] = 'YZ*WLf:GvZ3;PSgQ+\o#BcI+#4Ws';
$db['logo']['database'] = 'logo-generator';
$db['logo']['dbdriver'] = 'mysql';
$db['logo']['dbprefix'] = '';
$db['logo']['pconnect'] = TRUE;
$db['logo']['db_debug'] = TRUE;
$db['logo']['cache_on'] = FALSE;
$db['logo']['cachedir'] = '';
$db['logo']['char_set'] = 'utf8';
$db['logo']['dbcollat'] = 'utf8_general_ci';
$db['logo']['swap_pre'] = '';
$db['logo']['autoinit'] = TRUE;
$db['logo']['stricton'] = FALSE;


$db['aegeeorg']['hostname'] = 'localhost';
$db['aegeeorg']['username'] = 'logo-generator';
$db['aegeeorg']['password'] = 'YZ*WLf:GvZ3;PSgQ+\o#BcI+#4Ws';
$db['aegeeorg']['database'] = 'ab';
$db['aegeeorg']['dbdriver'] = 'mysql';
$db['aegeeorg']['dbprefix'] = '';
$db['aegeeorg']['pconnect'] = TRUE;
$db['aegeeorg']['db_debug'] = TRUE;
$db['aegeeorg']['cache_on'] = FALSE;
$db['aegeeorg']['cachedir'] = '';
$db['aegeeorg']['char_set'] = 'utf8';
$db['aegeeorg']['dbcollat'] = 'utf8_general_ci';
$db['aegeeorg']['swap_pre'] = '';
$db['aegeeorg']['autoinit'] = TRUE;
$db['aegeeorg']['stricton'] = FALSE;
*/

/* End of file database.php */
/* Location: ./application/config/database.php */