<?php
  defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!DOCTYPE html>
<html>
  <head>

    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://code.getmdl.io/1.1.3/material.indigo-pink.min.css">
    <script defer src="https://code.getmdl.io/1.1.3/material.min.js"></script>

    <!-- 아작스 CDN 시작 -->
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
    <!-- 아작스 CDN 끝 -->
    <style>
      body {
          width: 100%;
          height: 100%;
          margin: 0;
          padding: 0;
          background-color: #ddd;
          z-index: 1;
      }

      * {
          box-sizing: border-box;
          -moz-box-sizing: border-box;
      }

      .paper {
          width: 210mm;
          min-height: 297mm;
          padding: 20mm; /* set contents area */
          margin: 10mm auto;
          border-radius: 5px;
          background: #fff;
          box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
      }
      .content {
          padding: 0;
          height: 257mm;
      }

      @page {
          size: A4;
          margin: 16;
      }

      @media print {
          html, body {
              width: 210mm;
              height: 297mm;
              background: #fff;
          }
          .paper {
              margin: 0;
              border: initial;
              border-radius: initial;
              width: initial;
              min-height: initial;
              box-shadow: initial;
              background: initial;
              page-break-after: auto ;
          }
      }

      #title{
        text-align: center;
      }

      table{
        width:170mm;
      }

      th, td{
        padding : 10px;
      }

      #banner {
          position: absolute;
          left:0;top:0;
          z-index: 2;
          padding:5px;
          text-align:center;
          font-weight:bold;
      }

      a.top {
          position: fixed;
          left: 50%;
          z-index: 2;
          bottom: 50px;
          display: none;
      }


    </style>

    <script>
      //
      // 페이지 모두 읽을때까지 로딩 표시
      // body태그에 onload="loading();"
      // body내용중 최상단에 div preview, show
      function loading()
      {
          document.all.preview.style.display = "none";
          document.all.show.style.display = "";
      }
      // 역지오코딩 주소 저장 변수
      var ReverseGeocoding_address = "";

      // 역지오코딩
      function googleapisView(lat_input,lng_input) {
          var lat = lat_input; // 위도
          var lng = lng_input; // 경도
          var geocode = "http://maps.googleapis.com/maps/api/geocode/json?latlng="+lat+","+lng+"&sensor=false";
          jQuery.ajax({
              url: geocode,
              // 지도보다 역지오코딩의 아작스 속도가 빠른 탓에 비동기->동기 설정
              async:false,
              type: 'POST',
                 success: function(myJSONResult){
                          if(myJSONResult.status == 'OK') {
                              ReverseGeocoding_address=myJSONResult.results[0].formatted_address;
                          }
                  }
          });
      }
      // 역지오코딩 끝

    // 맵 off 클릭시 해당 맵 삭제
      function Map_Off_CheckBox(Map_Off_CheckBox_StoryPlaceIdx){
                  var CheckBox_state = $('[name=Map_Off_Click_name_'+Map_Off_CheckBox_StoryPlaceIdx+']').is(':checked');
                  console.log(CheckBox_state);
        // 매개변수가 -1일 경우는 전체맵 off
        if(Map_Off_CheckBox_StoryPlaceIdx==-1){
          $( ".days_directions_map_div").slideUp( "slow");
        }else{
          $( "#days_directions_map_div_"+Map_Off_CheckBox_StoryPlaceIdx ).slideUp( "slow");
        }
      }

    // 프린팅 버튼 클릭시 프린트
      function Printing(){
        // 프린트페이지의 오른쪽 메뉴바를 삭제
        $('#menubar').remove();
        window.print();
      }
    // 프린팅 버튼 클릭시 프린트 끝

    // 윈도우 스크롤과 함께 이동하는 메뉴바
      $(window).scroll(function()
      {
          if ( $( this ).scrollTop() > 200 ) {
              $( '.top' ).fadeIn();
          } else {
              $( '.top' ).fadeOut();
          }
          $('#banner').animate({top:$(window).scrollTop()+"px" },{queue: false, duration: 350});
      });

      // 메뉴바(배너) 클릭시
      $('#banner').click(function()
      {
          $('#banner').animate({ top:"+=15px",opacity:0 }, "slow");
      });
    // 윈도우 스크롤과 함께 이동하는 메뉴바 끝

    // 맨위로 버튼
      $( '.top' ).click( function() {
          $( 'html, body' ).animate( { scrollTop : 0 }, 400 );
          return false;
      } );
    // 맨위로 버튼 끝

    </script>

    <div id="menubar" style="position:relative;float:right;width:80px;top:45%;">
      <a href="#" class="top mdl-button mdl-js-button mdl-js-ripple-effect" ><i class="material-icons">vertical_align_top</i></a>
      <div id="banner">
        <button class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored" onClick="window.location.reload(true)">
          <i class="material-icons">restore_page</i>
        </button>
        <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent" onClick="Printing()">
          <i class="material-icons">print</i>
        </button>
      </div>
    </div>
  </head>
  <body onload="loading()">
    <div id="preview">
      인쇄를 준비하고 있습니다. 기다려주세요.
    </div>
    <div id="show" style="display:none">
        <div class="paper">
            <div class="content" style="display:inline">

              <!-- 계획서 타이틀 -->
              <h3 id="title"><b>Travel Plan</b></h3>
              <!-- 계획서 타이틀 끝 -->

              <hr>

              <!-- 여행정보 -->
              <div style="background-color:#607D8B; color:white;">
                <h4> 1. Travel Information</h4>
              </div>
              <ul>
                <li>
                  <h5>Name : <b><?=$StoryInfo->StoryName?></b></h5>
                </li>
                <li>
                  <h5>Date : <b><?=$StoryInfo->StoryStartDate?> ~ <?=$LastDate?></b></h5>
                </li>
                <li>
                  <h5>Companion : <b><?=$Companions['num_rows']?>peaple</b></h5>
                </li>
              </ul>
              <!-- 여행정보 끝 -->

              <!-- 동행자 -->
              <div style="background-color:#607D8B; color:white;">
                <h4>2. Companions</h4>
              </div>
              <table class="mdl-data-table mdl-js-data-table mdl-shadow--2dp">
                <tr>
                  <th>Number</th>
                  <th>Picture</th>
                  <th>Name</th>
                  <th>Check</th>
                </tr>
                <?php
                  if($Companions['num_rows']!=0){
                      $CompanionsNum=1;
                      foreach($Companions['result'] as $Companion){
                        echo"<tr><td width='10'>$CompanionsNum</td><td>";
                        if($Companion->MemberProfile!=null){
                ?>
                <img src="<?=URL;?>/images/user_profile_image/<?=$Companion->MemberIdx?>.jpg" style="max-width:100%; width:40px;">
                                            <?php
                        }else{
                          echo"none";
                        }
                        echo"</td><td>$Companion->MemberNickname</td><td width='50'><input type='checkbox' class='mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect'></td></tr>";
                        $CompanionsNum++;
                      }
                  }
                  else{
                    echo"<td colspan='4'>동행자가 없습니다.</td>";
                  }
                ?>
              </table>
              <!-- 동행자 끝 -->

              <!-- 일정 -->
              <div style="background-color:#607D8B; color:white;">
                <h4>3. Date</h4>
              </div>
              <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect">
                <input type="checkbox" class="mdl-checkbox__input" name="Map_Off_Click_name_1" onclick="Map_Off_CheckBox(-1)" />
                <span class="mdl-checkbox__label">
                  Total Map Off
                </span>
              </label>
              <h5>1days</h5>
              <table class="mdl-data-table mdl-js-data-table mdl-shadow--2dp">
                <tr>
                  <th width="50">Num</th>
                  <th>Information</th>
                  <th width="200">Time</th>
                </tr>
                <?php
                  $days_numbering=1;
                  $StoryPlaceDateNum=1;
                  $before_lat=0;
                  $before_lot=0;

                  foreach($StoryPlaces['result'] as $StoryPlace){
                    if($StoryPlaceDateNum==1&&$days_numbering==1){
                      $before_lat=$StoryPlace->PlaceLatitude;
                      $before_lot=$StoryPlace->PlaceLongtitude;
                    }

                    if($days_numbering!=1){
                ?>
                    <tr class="days_directions_map_div" id="days_directions_map_div_<?=$StoryPlace->StoryPlaceIdx?>">
                      <td colspan="6">
                        <div>
                          <!-- 각 경로별 경로 표시(origin, destination) -->
                          <iframe
                          width="600"
                          height="300"
                          frameborder="0"
                          style="border:0"
                          src="https://www.google.com/maps/embed/v1/directions?key=AIzaSyBz651lggTDPwHiyFAsjFI9I0S7Lu5LBBE
                              &origin=<?=$before_lat?>,<?=$before_lot?>
                              &destination=<?=$StoryPlace->PlaceLatitude?>,<?=$StoryPlace->PlaceLongtitude?>
                              &avoid=tolls|highways
                              "
                          >
                          </iframe>
                          <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect">
                            <input type="checkbox" class="mdl-checkbox__input" name="Map_Off_Click_name_<?=$StoryPlace->StoryPlaceIdx?>" onclick="Map_Off_CheckBox(<?=$StoryPlace->StoryPlaceIdx?>)" />
                            <span class="mdl-checkbox__label">
                              Map Off
                            </span>
                          </label>
                        </div>
                      </td>
                    </tr>
                <?php
                        $before_lat=$StoryPlace->PlaceLatitude;
                        $before_lot=$StoryPlace->PlaceLongtitude;
                    }
                    if($StoryPlaceDateNum!=$StoryPlace->StoryPlaceDateNumber){
                      $days_numbering=1;
                      $StoryPlaceDateNum=$StoryPlace->StoryPlaceDateNumber;
                      echo"
                            </table>
                            <h5>$StoryPlaceDateNum days</h5>
                            <table class='mdl-data-table mdl-js-data-table mdl-shadow--2dp'>
                                      <tr>
                                        <th width='50'>Num</th>
                                        <th>Information</th>
                                        <th width='200'>Time</th>
                                      </tr>
                      ";
                    }
                ?>

                    <tr>
                      <td>
                          <?=$days_numbering?>
                      </td>
                      <td>
                          <b><?=$StoryPlace->PlaceName?></b>
                          (☎<?=$StoryPlace->PlaceTel?>)
                          <br/>
                        <script>
                            googleapisView(<?=$StoryPlace->PlaceLatitude?>,<?=$StoryPlace->PlaceLongtitude?>);
                            document.write(ReverseGeocoding_address);
                        </script>
                        <br/>
                        <?=$StoryPlace->PlaceExplain?>
                        <br/>
                      </td>
                      <td>
                          <?=$StoryPlace->StoryPlaceStartTime?>
                      </td>
                    </tr>


                <?php
                    $days_numbering++;
                  }
                ?>

              </table>
                <div id="map"></div>
              <!-- 일정 끝 -->

              <!-- 준비물 -->
              <h4>4. Materials(total<?=$Materials['num_rows']?>)</h4>
              <table class="mdl-data-table mdl-js-data-table mdl-shadow--2dp">
                <tr>
                  <th>Number</th>
                  <th>Name</th>
                  <th>Check</th>
                </tr>
                <?php
                $MaterialsNum=1;
                  if($Materials['num_rows']!=0){
                    foreach($Materials['result'] as $Material){
                ?>
                    <tr>
                      <td>
                        <?=$MaterialsNum?>
                      </td>
                      <td>
                        <?=$Material->MaterialName?>
                      </td>
                      <td>
                        <input type="checkbox" class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect">
                      </td>
                    </tr>

                <?php
                      $MaterialsNum++;
                    }
                  }
                  else{
                    echo"<td colspan='4'>No Materials</td>";
                  }
                ?>
              </table>
              <!-- 준비물 끝 -->

              <!-- 노트 -->
              <h4>*. Note</h4>
              <textarea rows="20" cols="103">개인적으로 준비해야할 코멘트를 남겨주세요.</textarea>
              <!-- 노트 끝 -->
            </div>
        </div>
    </div>
  </body>
</html>
