<?php

require_once('phpcassa/connection.php');
require_once('phpcassa/columnfamily.php');

/**
 * @author Courtney
 */
class Db {

    private  $cf, $CI;
    /**
     *
     * @var ConnectionPool
     */
    private $conn;
    /**
     * @var ColumnFamily
     */
    private $cfObj;
    private $cfList = array();

    /**
     * Init connection for the database using the settings in the cassandra.php
     * config file...
     */
    public function __construct() {
        $this->CI = & get_instance();
        $this->creatPool($this->CI->config->item('keyspace'), $this->CI->config->item('cassandra_servers'));
        $this->cf = $this->CI->config->item('default_cf');
        $this->initCFs($this->CI->config->item('init_cf'));
    }

    /**
     * Creates a new instance of the given CF which will be returned
     * for interaction by <code>cf()</code>
     * @param string $cf The column family to interact with
     */
    public function setCF($cf) {
        $this->cfObj = new ColumnFamily($this->conn, $cf);
    }

    /**
     * Returns the instance of the last column family created by
     * calling <code>setCF()</code>... If setCF hasn't been called
     * then the default_cf set in cassandra.php config file is returned,
     * once setCF is called then the last one to be set is always returned.
     * @return ColumnFamily
     */
    public function cf() {
        return $this->cfObj;
    }

    /**
     * Allows you to query any CF that was initalised either by setting a list of
     * CF names in the cassandra.php config file or by calling initCFs(array)
     * @param string $cfName The name of the column family to perform the query on
     * this parameter is option. If it is not specified then the last cf used is
     * returned, if its the first call then the default cf is returned
     * - case insensitive
     * @return ColumnFamily
     */
    public function query($cfName=NULL) {
        if ($cfName === NULL) {
            return $this->cfList[strtolower($this->cf)];
        } else {
            //set cf as the last cf so that subsequent queries don't need 
            //to specify the cf name
            $this->cf = $cfName;
            return $this->cfList[strtolower($cfName)];
        }
    }

    /**
     * Initialises a set of column families which are stored in an associative
     * array, keyed by the CF name to avoid using setCF which creates a new
     * instance of your CF each time.
     * @param array $cfl An array containing a list of CFs to initalise and
     * "cache" for queries later...
     * @param $reinit defaults to false, if true then the given cf are re instanciated
     * even if an instance already existed, if false then its not and the
     * arg is ignored
     */
    public function initCFs($cfl, $reinit=false) {
        foreach ($cfl as $icf) {
            $createCFInstance = false;
            if (!isset($this->cfList[strtolower($icf)]) && !empty($this->cfList[strtolower($icf)])) {
                $createCFInstance = true;
            }
            if ($reinit) {
                $createCFInstance = true;
            }
            if ($createCFInstance) {
                //init the CFs to be accessible by name
                $this->cfList[strtolower($icf)] = new ColumnFamily($this->conn, $icf);
            }
        }
    }

    /**
     * A straight rip of phpcassa's old Connection constructor. Connection class
     * is now depreciated so it issues a warning when used, only thing it did
     * was what's contained in this method, which is create an instance of the 
     * ConnectionPool. Without having to modify anything else copying the
     * contructor was the fastest/easiest way to do this...
     * @param type $keyspace
     * @param type $servers
     * @param type $max_retries
     * @param type $send_timeout
     * @param type $recv_timeout
     * @param type $recycle
     * @param type $credentials
     * @param type $framed_transport 
     */
    private function creatPool($keyspace, $servers=NULL) {
        try {
            if ($servers != NULL) {
                $new_servers = array();
                foreach ($servers as $server) {
                    $new_servers[] = $server['host'] . ':' . (string) $server['port'];
                }
            } else {
                $new_servers = NULL;
            }

            $this->conn = new ConnectionPool
                            (
                            $keyspace, $new_servers,
                            $this->CI->config->item('max_retries'),
                            $this->CI->config->item('send_timeout'),
                            $this->CI->config->item('recv_timeout'),
                            $this->CI->config->item('recycle'),
                            $this->CI->config->item('credentials'),
                            $this->CI->config->item('framed_transport')
            );
        } catch (Exception $e) {
            show_error($e->getMessage());
        }
    }

}