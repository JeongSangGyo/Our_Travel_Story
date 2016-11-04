<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Plan extends CI_Controller {
	function __construct() {
		parent::__construct();
		$this->load->database();
		$this->load->helper('url');
		$this->load->model('Plan_model');
		$this->load->model('Storymodel');
	}

	public function index() {
		$_SESSION['nowTab'] = 'mystory';
		$_SESSION['create_story'] = false;

		$this->load->view('../views/public/header_plan.php');
		$this->load->view('../views/plan/index.php');
		$this->load->view('../views/public/footer_plan.php');
	}

	// 새로운 스토리 생성
	public function createStory() {
		$story_name = $_POST['story_name'];
		$story_start_date = date("Y-m-d");
		$story_leader_idx = $_SESSION['MemberIdx'];
		$story_type = $_POST['story_type'];

		$data = array(
			'StoryName' => $story_name,
			'StoryRepresentImage' => '',
			'StoryRepresentImageExt' => '',
			'StoryStartDate' => $story_start_date,
			'MemberIdx' => $story_leader_idx,
			'StoryFormation' => $story_type,
			'StoryPublicCheck' => '1'
		);

		$story_idx = $this->Plan_model->input_new_story($data);
		$data = array(
			'StoryIdx' => $story_idx,
			'MemberIdx' => $story_leader_idx,
			'CompanionJoinCheck' => '1'
		);

		$this->Plan_model->input_new_companion($data);
		redirect("/plan/$story_idx");
	}

	// 여행 시작일 변경
	public function setPlanStartDate() {
		$postData = json_decode(file_get_contents("php://input"));

		$story_idx = $postData->story_idx;
		$story_start_date = $postData->story_start_date;
		$story_start_date_gap = $postData->story_place_date_gap;
		$this->Plan_model->set_story_start_date($story_idx, $story_start_date);
		$this->Plan_model->set_story_event_date($story_idx, $story_start_date_gap);
		$result = $this->Plan_model->get_story_place($story_idx);

		echo json_encode($result);
	}

	// 특정 장소 추가
	public function addPlace() {
		$postData = $_POST['addPlaceInfo'];

		$result = $this->Plan_model->is_place_name($postData['name']);

		$arr = [];
		$temp = new stdClass();
		if ( !$result ) {
				// 중복되지 않은 장소 이름이라면
				$postImages = $_FILES['placeImages'];
				$cnt = count($postImages['error']);
				for ( $iCount = 0; $iCount < $cnt; $iCount++ ) {
						if ( $postImages['error'][$iCount] != 0 ) {
								return false;
						}
				}

				// 카테고리 이름으로 카테고리의 Idx 받아오기
				$place_category = $postData['category'];
				$place_category_idx = $this->Plan_model->get_place_category_idx($place_category);

				// 새로 입력할 장소정보 저장
				$add_place_info = array(
					'CategoryPlaceIdx' => $place_category_idx[0]->CategoryPlaceIdx,
					'MemberIdx' => 1,
					'PlaceName' => $postData['name'],
					'PlaceExplain' => $postData['description'],
					'PlaceTel' => $postData['phone'],
					'PlaceLatitude' => $postData['latitude'],
					'Placelongtitude' => $postData['longtitude'],
					'PlaceNation' => $postData['nation'],
					'PlaceRegion' => $postData['region'],
					'PlaceFootprint' => 0
				);

				// 새로운 장소 정보 입력
				$place_idx = $this->Plan_model->input_new_place($add_place_info);

				// 등록된 장소에 대한 사진을 저장할 수 있는 폴더 만들기
				$imageSavePath = $_SERVER['DOCUMENT_ROOT'] . '/images/place/' . $place_idx;
				mkdir($imageSavePath);

				$now = date('YmdHis');
				$imageInfo = [];
				for ( $iCount = 0; $iCount < $cnt; $iCount++ ) {
						$imageInfo[$iCount]['PlaceImageName'] = $now . '_' . md5($postImages['name'][$iCount]);
						$imageInfo[$iCount]['PlaceImageExt'] = pathinfo($postImages['name'][$iCount], PATHINFO_EXTENSION);

						$tmp_path = $postImages['tmp_name'][$iCount];
						$save_path =  $_SERVER['DOCUMENT_ROOT'] . '/images/place/' . $place_idx . '/' . $imageInfo[$iCount]['PlaceImageName'] . '.' . $imageInfo[$iCount]['PlaceImageExt'];
						move_uploaded_file($tmp_path, $save_path);
				}

				$cnt = count($imageInfo);
				for ( $iCount = 0; $iCount < $cnt; $iCount++ ) {
						$imageInfo[$iCount]['PlaceIdx'] = $place_idx;
						$this->Plan_model->set_place_image($imageInfo[$iCount]);
				}
				$temp->image = $imageInfo;

				$temp->msg = 'success';
		} else {
				// 중복된 장소 이름이라면
				$temp->msg = 'already';
		}

		$arr = $temp;
		echo json_encode($arr);
	}

	// 계획 화면에 들어갔을 때, 처음 받아와지는 장소 정보들
	public function getStartPlaceInfo() {
		$start_place_info = $this->Plan_model->get_start_place_info();
	}

	// 특정 장소 검색
	public function toPlaceSearch() {
		$postData = json_decode(file_get_contents("php://input"));

		// 장소가 위치한 지역과 이름을 통해서 검색
		$place_region = $postData->region;
		$place_name = $postData->name;
		$place_category = $postData->category;

		if (!$place_category) {
			// 카테고리가 설정되어 있지 않다면
			$place_category_idx = '';
		} else {
			// 카테고리가 설정되어 있다면, 장소 카테고리와 일치하는 idx를 가져오기
			$result = $this->Plan_model->get_place_category_idx($place_category);
			$place_category_idx = $result[0]->CategoryPlaceIdx;
		}

		// 국가와 지역을 구분
		$nation_arr = [
			'대한민국', '일본', '몽골', '중국', '러시아', '베트남'
		];

		$result = strcmp($nation_arr[0], $place_region);
		$nation_arr_cnt = count($nation_arr);

		$nation_region_identifier = false;
		for ($iCount = 0; $iCount < $nation_arr_cnt; $iCount++) {
			if (strcmp($nation_arr[$iCount], $place_region) == 0) {
				$nation_region_identifier = true;
				break;
			}
		}

		if (!$place_name && !$place_region) {
			// 국가나 지역과 이름을 검색하지 않았을 경우
			$result = $this->Plan_model->get_place_category_search($place_category_idx);
		} else if (!$place_name) {
			// 이름을 검색하지 않았을 경우
			if ($nation_region_identifier) {
				// 국가를 검색했을 경우
				$result = $this->Plan_model->get_place_nation_search($place_region, $place_category_idx);
			} else {
				// 지역을 검색했을 경우
				$result = $this->Plan_model->get_place_region_search($place_region, $place_category_idx);
			}
		} else if (!$place_region) {
			// 이름만 검색했을 경우
			$result = $this->Plan_model->get_place_name_search($place_name, $place_category_idx);
		} else {
			// 국가나 지역, 이름을 같이 검색했을 경우
			$result = $this->Plan_model->get_place_whole_search($place_region, $place_name, $place_category_idx);
		}

		echo json_encode($result);
	}

	// 나의 준비물 검색
	public function getMyMaterial() {
		$postData = json_decode(file_get_contents("php://input"));

		$story_idx = $postData->story_idx;
		$member_idx = $postData->member_idx;

		$result = $this->Plan_model->get_my_material($story_idx, $member_idx);

		echo json_encode($result);
	}

	// 나의 준비물 추가
	public function addMyMaterial() {
		$postData = json_decode(file_get_contents("php://input"));

		$material_category = $postData->material_category;
		$str = strcmp($material_category, 'none');

		if ($str == -1) {
			$material_category_idx = $this->Plan_model->get_material_category_idx($material_category);

			$add_material_info = array(
				'MaterialCategoryIdx' => $material_category_idx[0]->MaterialCategoryIdx,
				'MemberIdx' => $postData->member_idx,
				'StoryIdx' => $postData->story_idx,
				'MaterialName' => $postData->material_name
			);
		} else {
			$add_material_info = array(
				'MaterialCategoryIdx' => 0,
				'MemberIdx' => $postData->member_idx,
				'StoryIdx' => $postData->story_idx,
				'MaterialName' => $postData->material_name
			);
		}

		$result = $this->Plan_model->add_my_material($add_material_info);
		echo json_encode($result);
	}

	// 나의 준비물 제거
	public function deleteMyMaterial() {
		$postData = json_decode(file_get_contents("php://input"));

		$delete_material_info = array(
			'MemberIdx' => $postData->member_idx,
			'StoryIdx' => $postData->story_idx,
			'MaterialName' => $postData->material_name
		);

		$result = $this->Plan_model->delete_my_material($delete_material_info);
		echo json_encode($result);
	}
	/* -------------------- 준비물 끝 ------------------ */

	/* -------------------- 동행자 시작 ------------------ */
	public function getStoryCompanion() {
		$postData = json_decode(file_get_contents("php://input"));

		$story_idx = $postData->story_idx;
		$result = $this->Plan_model->get_story_companion($story_idx);

		echo json_encode($result);
	}

	public function getRealtimeCompanion() {
		$postData = json_decode(file_get_contents("php://input"));

		$story_idx = $postData->story_idx;
		$typed_companion_nickname = $postData->typed_text;
		$result = $this->Plan_model->get_realtime_companion($story_idx, $typed_companion_nickname);
		echo json_encode($result);
	}

	public function getRealtimeCompanion_result() {
		$postData = json_decode(file_get_contents("php://input"));

		$story_idx = $postData->typed_text;
		$typed_companion_nickname = $postData->typed_text;
		$result = $this->Plan_model->get_realtime_companion_result($story_idx, $typed_companion_nickname);
		echo json_encode($result);
	}

	public function getClickMemberInfo() {
		$postData = json_decode(file_get_contents("php://input"));

		$member_nickname = $postData->member_nickname;
		$result = $this->Plan_model->get_click_member_info($member_nickname);
		echo json_encode($result);
	}

	public function addRequestCompanion() {
		$postData = json_decode(file_get_contents("php://input"));

		$story_idx = $postData->story_idx;
		$member_nickname = $postData->member_nickname;
		$this->Plan_model->add_request_companion($story_idx, $member_nickname);
		$result = $this->Plan_model->get_story_companion($story_idx);

		echo json_encode($result);
	}
	/* -------------------- 동행자 끝 ------------------ */

	// 스토리 정보 리턴
	public function getStory() {
		$postData = json_decode(file_get_contents("php://input"));

		$story_idx = $postData->story_idx;
		$result = $this->Plan_model->get_story($story_idx);

		echo json_encode($result);
	}

	// 스토리 계획에 추가된 장소를 리턴
	public function getStoryPlace() {
		$postData = json_decode(file_get_contents("php://input"));

		$story_idx = $postData->story_idx;
		$result = $this->Plan_model->get_story_place($story_idx);

		echo json_encode($result);
	}

	// 스토리에 해당되는 날자에 계획된 장소들의 정보를 리턴
	public function getSpecificDateStoryPlace() {
		$postData = json_decode(file_get_contents("php://input"));

		$story_idx = $postData->story_idx;
		$story_date = $postData->story_date;

		$result = $this->Plan_model->get_specific_date_story_place($story_idx, $story_date);
		echo json_encode($result);
	}

	// 특정 스토리에 특정 장소에 대한 계획 추가
	public function addStoryPlace() {
		$postData = json_decode(file_get_contents("php://input"));

		$result = $this->Plan_model->get_story_place_date_number($postData->story_idx, $postData->place_date);

		$add_story_place_info = array(
			'StoryIdx' => $postData->story_idx,
			'PlaceIdx' => $postData->place_idx,
			'StoryPlaceStartTime' => $postData->start_time,
			'StoryPlaceEndTime' => $postData->end_time,
			'StoryPlaceDate' => $postData->place_date,
			'StoryPlaceDateNumber' => $result[0]->date_time,
			'StoryPlaceStandardTime' => null,
			'StoryPlaceMemo' => null
		);

		$result = $this->Plan_model->add_story_place($add_story_place_info);
		echo json_encode($result);
	}

	// 특정 스토리에 등록된 장소 계획 제거
	public function delStoryPlace() {
		$postData = json_decode(file_get_contents("php://input"));

		$del_story_place_info = array(
			'StoryIdx' => $postData->story_idx,
			'StoryPlaceIdx' => $postData->storyplace_idx
		);

		$this->Plan_model->del_story_place($del_story_place_info);
		$result = $this->Plan_model->get_story_place($postData->story_idx);
		echo json_encode($result);
	}

	public function setStoryPlace() {
		$postData = json_decode(file_get_contents("php://input"));

		$result = $this->Plan_model->get_story_place_date_number($postData->story_idx, $postData->place_date);

		$condition = array(
			'StoryIdx' => $postData->story_idx,
			'PlaceIdx' => $postData->place_idx,
			'StoryPlaceIdx' => $postData->storyplace_idx
		);

		$data = array(
			'StoryPlaceStartTime' => $postData->start_time,
			'StoryPlaceEndTime' => $postData->end_time,
			'StoryPlaceDate' => $postData->place_date,
			'StoryPlaceDateNumber' => $result[0]->date_time
		);

		$result = $this->Plan_model->set_story_place($condition, $data);
		echo json_encode($postData);
	}

	// 공유된 모든 장소의 정보를 리턴
	public function getStoryWholeBookmarkPlace() {
		$postData = json_decode(file_get_contents("php://input"));

		$story_idx = $postData->story_idx;
		$result = $this->Plan_model->get_story_whole_bookmark_place($story_idx);
		echo json_encode($result);
	}

	// 공유된 장소의 정보를 리턴
	public function getStorySharePlace() {
		$postData = json_decode(file_get_contents("php://input"));

		$place_idx = $postData->place_idx;
		$result = $this->Plan_model->get_story_share_place($place_idx);
		echo json_encode($result);
	}

	// 공유된 장소의 정보를 추가
	public function addStoryBookmarkPlace() {
		$postData = json_decode(file_get_contents("php://input"));

		$data = array(
			'StoryIdx' => $postData->story_idx,
			'PlaceIdx' => $postData->place_idx
		);

		$result = $this->Plan_model->add_story_bookmark_place($data);
		echo json_encode($result);
	}

	// 공유된 장소의 정보를 삭제
	public function delStoryBookmarkPlace() {
		$postData = json_decode(file_get_contents("php://input"));

		$data = array(
			'StoryIdx' => $postData->story_idx,
			'PlaceIdx' => $postData->place_idx
		);

		$this->Plan_model->del_story_bookmark_place($data);
		$result = $this->Plan_model->get_story_whole_bookmark_place($postData->story_idx);
		echo json_encode($result);
	}

	/* 장소의 자세한 정보 */
	public function getDetailPlaceInfo() {
		$postData = json_decode(file_get_contents("php://input"));
		$place_idx = $postData->place_idx;

		$place_info = $this->Plan_model->get_detail_place_info($place_idx);
		$place_images_info = $this->Plan_model->get_detail_place_images_info($place_idx);
		$place_story_info = $this->Plan_model->get_detail_place_story_info($place_idx);

		echo json_encode(array('placeInfo' => $place_info, 'placeImagesInfo' => $place_images_info, 'placeStoryInfo' => $place_story_info));
	}

	/* 장소의 자세한 정보 끝 */

	/* ------------------------------- 스토리 요약 페이지 ------------------------------- */

	// 계획페이지(StoryPlan)에서 '여행요약'버튼을 클릭시 실행
	function StoryStart( $StoryIdx, $StoryPlaceDateNumber = 1 ){
		// 해당 스토리정보 select--------------------------------------------------
		// 매개변수 스토리번호(StoryIdx)
		// StoryStartSelect 모델 쿼리
		$StoryStartInfo['StoryInfo']=$this->Storymodel->StoryStartSelect( $StoryIdx );

		// 해당 스토리 동행자 출력-------------------------------------------------
		// 매개변수 스토리번호(StoryIdx)
		// StoryCompanionSelect 모델 쿼리
		$StoryStartInfo['Companions']=$this->Storymodel-> StoryCompanionSelect( $StoryIdx );

		// 해당 스토리 일정 출력---------------------------------------------------
		// 매개변수 스토리번호(StoryIdx)
		// StoryPlaceSelect 모델 쿼리
		$StoryStartInfo['StoryPlaces']=$this->Storymodel-> StoryPlaceSelect($StoryIdx , $StoryPlaceDateNumber=0  );

		// 해당 스토리 일자 차수 max,min 출력 -------------------------------------
		// 매개변수 스토리번호(StoryIdx)
		// StoryPlaceStoryPlaceDateNumberMaxAndMinSelect 모델 쿼리
		$MaxMinPlaceDateNumber=$this->Storymodel->StoryPlaceStoryPlaceDateNumberMaxAndMinSelect( $StoryIdx );

		// 해당 스토리의 여행일자차수 가장 높은 수
		$StoryStartInfo['MaxPlaceDateNumber']=$MaxMinPlaceDateNumber['result']->StoryPlaceMaxDateNumber;

		// 해당 스토리의 여행일자차수 가장 낮은 수
		$StoryStartInfo['MinPlaceDateNumber']=$MaxMinPlaceDateNumber['result']->StoryPlaceMinDateNumber;

		// 해당 스토리 여행 마지막날
		if($StoryStartInfo['MaxPlaceDateNumber']!=null&&$StoryStartInfo['MinPlaceDateNumber']!=null){
			$StoryLastDate= $this->Storymodel->StoryLastDateSelect($StoryIdx,$StoryStartInfo['MaxPlaceDateNumber']);
			$StoryStartInfo['LastDate']=$StoryLastDate['result']->LastDate;
		}else{
			$StoryStartInfo['LastDate']=null;
		}
		// 해당 스토리 준비물 출력-------------------------------------------------
		// 매개변수 스토리번호(StoryIdx), 로그인한 사용자번호(LoginMemberIdx)=로그인 회원 세션
		$StoryStartInfo['Materials']=$this->Storymodel->StoryMaterialSelect( $StoryIdx, $_SESSION['MemberIdx'] );

		// View 이동
		$this->load->view('../views/public/header.php');
		$this->load->view('../views/plan/StoryStart.php',$StoryStartInfo);
		$this->load->view('../views/public/footer.php');
	}

	function StoryStartPlaceDateAjax(){
		$result = array();
		$callback = $_GET['callback'];
		$StoryPlaceDateNumber=$_GET['PlaceDateNumData'];
		$StoryIdx=$_GET['StoryIdxData'];
		$StoryPlaces['result']=$this->Storymodel-> StoryPlaceSelect( $StoryIdx , $StoryPlaceDateNumber  );
		echo $callback."(".json_encode($StoryPlaces['result']).")";
	}

	// 해당 스토리의 스토리 디테일 페이지 이동 및 출력 메소드
	function StoryDetail($StoryIdx , $StoryPlaceDateNumber=1 , $StoryPlaceIdx=null){

		// 해당 스토리 일정 출력---------------------------------------------------
		// 매개변수 스토리번호(StoryIdx)
		$StoryDetailInfo['StoryPlaces']=$this->Storymodel-> StoryPlaceSelect( $StoryIdx , $StoryPlaceDateNumber );
		$StoryDetailInfo['StoryIdx']=$StoryIdx;

		// 해당 스토리 일자 차수 max,min 출력 -------------------------------------
		// 매개변수 스토리번호(StoryIdx)
		// StoryPlaceStoryPlaceDateNumberMaxAndMinSelect 모델 쿼리
		$MaxMinPlaceDateNumber=$this->Storymodel-> StoryPlaceStoryPlaceDateNumberMaxAndMinSelect( $StoryIdx );

		// 해당 스토리의 여행일자차수 가장 높은 수
		$StoryDetailInfo['MaxPlaceDateNumber']=$MaxMinPlaceDateNumber["result"]->StoryPlaceMaxDateNumber;

		// 해당 스토리의 여행일자차수 가장 낮은 수
		$StoryDetailInfo['MinPlaceDateNumber']=$MaxMinPlaceDateNumber["result"]->StoryPlaceMinDateNumber;

		// View 이동
		$this->load->view('../views/public/header.php');
		$this->load->view('../views/elbum/StoryStart.php',$StoryDetailInfo);
		$this->load->view('../views/public/footer.php');
	}

	function StoryPrint($StoryIdx){
		$StoryPrint['StoryInfo']=$this->Storymodel->StoryStartSelect( $StoryIdx );
		$StoryPrint['Companions']=$this->Storymodel-> StoryCompanionSelect( $StoryIdx );
		$StoryPrint['StoryPlaces']=$this->Storymodel-> StoryPlaceSelect($StoryIdx , 0  );
		$MaxMinPlaceDateNumber=$this->Storymodel-> StoryPlaceStoryPlaceDateNumberMaxAndMinSelect( $StoryIdx );
		$StoryPrint['MaxPlaceDateNumber']=$MaxMinPlaceDateNumber["result"]->StoryPlaceMaxDateNumber;
		$StoryPrint['MinPlaceDateNumber']=$MaxMinPlaceDateNumber["result"]->StoryPlaceMinDateNumber;
		$StoryPrint['Materials']=$this->Storymodel->StoryMaterialSelect( $StoryIdx, $_SESSION['MemberIdx'] );
		if($StoryPrint['MaxPlaceDateNumber']!=null||$StoryPrint['MinPlaceDateNumber']!=null){
			$StoryLastDate= $this->Storymodel->StoryLastDateSelect($StoryIdx,$StoryPrint['MaxPlaceDateNumber']);
			$StoryPrint['LastDate']=$StoryLastDate['result']->LastDate;
		}else{
			$StoryPrint['LastDate']=null;
		}
		$this->load->view('../views/plan/print.php',$StoryPrint);
	}

	function MaterialClick(){
		$result = array();
		$callback = $_GET['callback'];
		$MaterialIdxData=$_GET['MaterialIdxData'];
		$StoryPlaces['result']=$this->Storymodel-> MaterailIdxInsert($MaterialIdxData);
		echo $callback."(".json_encode($StoryPlaces['result']).")";
	}
}
?>
