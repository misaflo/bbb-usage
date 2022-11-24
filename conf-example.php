<?php

// Enter your settings and save as conf.php

// Put false if you don't have the meta bbb-origin-server-name configured on your meeting reponse xml
$metaServer = true;

// If you want to protect the site you can define access codes here with $secrets
// Don't mix up these secrets with your BBB-secret
// if $secrets is not set, anyone can access the site

// If you just have one frontend (e.g. Greenlight) you can use following setting
// When opening the page enter "MySecret" and hit return

//$secrets = array ("MySecret" => "%");

// You can define different secrets (access codes) to filter different frontends (API-parameter bbb-origin-server-name)
// Use % as wildcard to show all origin servers
/*$secrets = array ("<Secret_to_show_all_origin_servers>" => "%",
                    "<SecondSecret>" => "origin.server1.tld",
                    "<ThirdSecret>"  => "origin.server2.tld" );
*/

$bbb_secret="your BBB secret";
$servername="your server name";         // e.g. https://bbb.yourdomain.com

// Or if you want to monitor multiple BigBlueButton Server just use servers.yml like servers-example.yml
// WARNING you need php-yaml extension installed on your server

$timezone = "Europe/Vienna";            // your timezone

$db_server = "localhost";
$db_port = "3306";
$db_user = "<username>";
$db_password = "<password>";
$db_name = "bbb-usage";                 // database has to be created before starting bbb-usage

/*
 * DON'T MODIFY ANTYHING UNDER THIS COMMENT
 */

define('META_SERVER',$metaServer);