<?php
$servers[0] = array('host' => '192.168.2.6', 'port' => 9160);
$servers[1] = array('host' => '192.168.2.7', 'port' => 9160);
$servers[3] = array('host' => '192.168.2.8', 'port' => 9160);
$config['cassandra_servers'] = $servers;
$config['keyspace'] = "Keyspace1";//keyspace name
$config['default_cf'] = "Standard";//default column family
//set of column families your application uses and you want to have initialied and cached
$config['init_cf']=array("post","users","settings");