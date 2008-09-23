<?php

require_once 'Proexc/Admin/Controller/Action.php';

class Admin_IndexController extends Proexc_Admin_Controller_Action {

    function indexAction()
    {
    	$this->view->title = 'Admin';
    }

}