<?php

/* Database config */

$db_host		= 'fdb3.biz.nf';
$db_user		= '1142346_aregawi';/*'root';*/
$db_pass		= 'AbuneAregawi24';/*'root';*/
$db_database		= '1142346_aregawi';

/* End config */


$link = @mysql_connect($db_host,$db_user,$db_pass) or die('Unable to establish a DB connection');
/*mysql_set_charset('utf8');*/  /*DY This line is giving me a pain. the db is utf anyways. */
mysql_query("SET NAMES 'utf8'");
mysql_select_db($db_database,$link);

?>