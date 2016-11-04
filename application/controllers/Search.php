<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Search extends CI_Controller {
	function __construct() {
		parent::__construct();
		$this->load->helper('url');
	}

	public function index() {
    $_SESSION['nowTab'] = 'search';
		$_SESSION['create_story'] = true;

		$this->load->view('../views/public/header.php');
		$this->load->view('../views/search/index.php');
		$this->load->view('../views/public/footer.php');
	}
}
?>
