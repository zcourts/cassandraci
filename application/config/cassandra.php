<?php

/**
 * @author Courtney Robinson <courtney@crlog.info>
 * Using the command line i.e cassandra-cli create a test keyspace for e.g
 * connect localhost/9160;
 * create keyspace Keyspace1;
 * use Keyspace1;
 * create column family Standard1;
 * 
 */
$servers[] = '127.0.0.1:9160';//If omitted, the port defaults to 9160.
//$servers[] = '127.0.0.2:9160';
//$servers[] = '127.0.0.3:9160';
$config['cassandra_servers'] = $servers;
$config['keyspace'] = "Keyspace1";
$config['default_cf'] = "Standard1";
//set of column families your application uses and you want to have initialied and cached
$config['init_cf'] = array("Standard1"); //set of configs to initalize by default
$config['max_retries'] = 5;
$config['send_timeout'] = 5000;
$config['recv_timeout'] = 5000;
$config['recycle'] = 10000;
//if needed then array("username"=>user1,"password"=>pass); replaces NULL
$config['credentials'] = NULL;
$config['framed_transport'] = true;