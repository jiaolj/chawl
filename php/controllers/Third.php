<?php
class third extends CI_Controller{
  		function __construct(){
                   parent::__construct();
         }
         function index(){
             $this->load->add_package_path(APPPATH."third_party/");
             $this->load->library("test");
             echo $this->test->getval();
             $this->load->view('third');
             $this->load->remove_package_path(APPPATH."third_party/");
             $this->load->view('third');
        }
}