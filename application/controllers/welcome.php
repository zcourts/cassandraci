<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {

    function index() {
        //query using default cf set in cassandra.php
        $this->db->query()->insert('key', array('column1' => 'value1', 'column2' => 'value2'));
        //query using users cf init from setting in cassandra.php
        //$this->db->query("users")->insert('key', array('column1' => 'value1', 'column2' => 'value2'));
        //no param but still query using users until another call to query
        //specifies a different cf the users cf will be used
        $this->db->query()->insert('key1', array('column3' => 'value1', 'column4' => 'value4'));
        $r1 = $this->db->query()->get('key');
        $r2 = $this->db->query()->get('key1');
        //query using the default cf set in cassandra.php
        $r3 = $this->db->query($this->config->item('default_cf'))->get('key');
        $val = array('query1' => $r1, 'query2' => $r2, 'query3' => $r3);
        $this->load->view('welcome_message', $val);
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */