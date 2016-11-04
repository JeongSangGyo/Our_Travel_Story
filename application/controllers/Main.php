<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller {
	function __construct() {
		parent::__construct();
		$this->load->helper('url');
		$this->load->database();
		$this->load->model('storymodel');
	}

	// Main Page - Category All
	public function index() {
		$_SESSION['nowTab'] = 'main';
		$_SESSION['create_story'] = true;

		$StoryList = $this->storymodel->StoryCategoryAll();
		$Companion = $this->storymodel->StorySelectCompanion();
		$StoryGood = $this->storymodel->StoryCountGood();

		$this->load->view('/public/header');
		$this->load->view('/main/index', array('StoryList' => $StoryList, 'Companion' => $Companion, 'StoryGood' => $StoryGood));
		$this->load->view('/public/footer');
	}

	// Main Page - Category Checked
	public function Category() {
		$latest = $_GET['latest'];
		$late = $_GET['late'];
		$best = $_GET['best'];
		$month = $_GET['month'];
		$formation = $_GET['formation'];

		$StoryList = $this->storymodel->StoryCategory($latest, $late, $best, $month, $formation);
		$Companion = $this->storymodel->StorySelectCompanion();
		$StoryGood = $this->storymodel->StoryCountGood();

		$result = array('StoryList' => $StoryList, 'Companion' => $Companion, 'StoryGood' => $StoryGood);

		echo json_encode($result);
	}
}
?>
