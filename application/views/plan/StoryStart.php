<?php
// 여행일정 요약 페이지
  defined('BASEPATH') OR exit('No direct script access allowed');
?>
  <style>
  .demo-list-action {
    width: 500px;
  }
  .demo-list-icon {
    width: 300px;
  }
  /*여행 요약(동행자, 일정, 준비물) div Css*/
  #div_height{
    height: 300px;
    width:600px;
    /*가로 스크롤 없애기*/
    overflow-x: auto;
  }
  /*일정 table CSS*/
  #Placetable{
    width: 100%;
    height: 100%;
  }

  /*지도 크기*/
    html, body {
      height: 100%;
      margin: 0;
      padding: 0;
    }

    /*Map 높이*/
    #map {
      height: 28%;
    }
  /*지도 크기 끝*/
  </style>

  <!-- 아작스 CDN 시작 -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
  <!-- 아작스 CDN 끝 -->

<!-- 준비물 클릭 아작스 -->
  <script>
    function MaterialClick(Material_Click_Num){
      var MaterialIdx = Material_Click_Num;
      $.ajax({
        url : '/Plan/MaterialClick',
        data :{
          MaterialIdxData:MaterialIdx,
        },
        dataType:'jsonp',
        success:function(data){

        },
        error: function(){
          window.alert('오류가 발생하였습니다.');
        }
      });
    }
  </script>
<!-- 준비물 클릭 아작스 끝 -->

<!-- 일차 아작스 -->
  <script>
    function StoryPlaceDateBtn_Click(Click_DateNum){
        var PlaceDateNum = Click_DateNum;
        var StoryIdxNum = '<?php echo $StoryInfo->StoryIdx; ?>';
        $.ajax({
          url : '/Plan/StoryStartPlaceDateAjax',
          data :{
            PlaceDateNumData:PlaceDateNum,
            StoryIdxData:StoryIdxNum
          },
          dataType:'jsonp',
          success:function(data){
            console.log(data);
              var output="<table class='mdl-data-table mdl-js-data-table  mdl-shadow--2dp' id='Placetable'><thead><tr><th class='mdl-data-table__cell--non-numeric'>순번</th><th class='mdl-data-table__cell--non-numeric'>장소명</th><th class='mdl-data-table__cell--non-numeric'>시간</th></tr></thead><tbody>";
              for(var i =0; i<data.result.length;i++){
                output+="<tr><td>"+(i+1)+"</td><td><li class='mdl-list__item'><span class='mdl-list__item-primary-content'><i class='material-icons mdl-list__item-icon'>account_balance</i>"+
                    data.result[i]['PlaceName']+"</span></li></td><td>"+data.result[i]['StoryPlaceStartTime']+"</td></tr>";
              }
              output+="</tbody></table>";
              $('#StoryPlaceInfoAjax').html("");
              $('#StoryPlaceInfoAjax').html(output);
              initMap_click(data);
          },
          beforeSend:function(){
            $('#StoryPlaceInfoAjax').html("");
            $('#StoryPlaceInfoAjax').html("<div class='mdl-spinner mdl-js-spinner is-active'></div>");
          },
          error: function(){
            window.alert('오류가 발생하였습니다.');
          }
        });
      }
  </script>
<!-- 일차 아작스 끝 -->

<!-- 지도 클릭 js -->
  <script>

      // 일차 클릭시 변화 함수
      function initMap_click(data) {
        // 첫 화면에 보여질 줌상태와 지도 중간 지점 설정
        console.log(data);
        for(var i =0; i<data.result.length;i++){
          console.log(data.result[i].PlaceName);
        }

        var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 13,
          center: {
            lat:parseInt(data.result[0].PlaceLatitude),
            lng:parseInt(data.result[0].PlaceLongtitude)
          }
        });

        // 지도에서 버튼을 클릭하며 나타나는 알림창, 형식 : '내용',
        var secretMessages = [];

        for(var i =0; i<data.result.length;i++){

          // 역지오코딩
          googleapisView(data.result[i].PlaceLatitude,data.result[i].PlaceLongtitude);
          console.log(ReverseGeocoding_address);
          // 역지오코딩 끝

          secretMessages.push(
            "<b>"+data.result[i].PlaceName+"</b>(☎"+data.result[i].PlaceTel+")</br>"+
            ReverseGeocoding_address+"</br>"+data.result[i].PlaceExplain
          );
        }
        secretMessages.push();

        var locations=[];

        console.log(secretMessages);
        var bounds = new google.maps.LatLngBounds();
        for(var i =0; i<data.result.length;i++){
          var marker = new google.maps.Marker({
            position: {
                lat  : parseFloat(data.result[i].PlaceLatitude)
                ,
                lng  : parseFloat(data.result[i].PlaceLongtitude)
            },

            map: map
          });
          locations.push({
                  latlng:new google.maps.LatLng(parseFloat(data.result[i].PlaceLatitude),parseFloat(data.result[i].PlaceLongtitude))
          });
          bounds.extend(locations[i].latlng);
          attachSecretMessage(marker, secretMessages[i]);
        }

            map.setCenter(bounds.getCenter());
           map.fitBounds(bounds);
      }

      function attachSecretMessage(marker, secretMessage) {
        var infowindow = new google.maps.InfoWindow({
          content: secretMessage
        });

        marker.addListener('click', function() {
          console.log(marker.get('map'));
          infowindow.open(marker.get('map'), marker);
        });
      }

  </script>
<!-- 지도 클릭 js 끝 -->

<!-- 지도 javascript -->
      <script>
          function initMap(){
            StoryPlaceDateBtn_Click(0);
          }
      </script>
      <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBDjE8Q53z6sD0dqbuy5sLeEVKzipdaJD4&callback=initMap&signed_in=true" async defer>
      </script>
<!-- 지도 javascript 끝 -->
<!-- 지도 시작 -->
      <div id="map">
          일정설정(목적지,시간)을 정확히 입력해주시면 지도가 나타납니다.
      </div>
      <?php
        echo "<b>".$StoryInfo->StoryName." / ".$StoryInfo->StoryStartDate."~".$LastDate."</b>";
      ?>
<!-- 지도 끝 -->
  <main class="mdl-layout__content">
    <section class="mdl-layout__tab-panel is-active">
      <div class="page-content" >
        <div class="mdl-grid">
          <div>
            <div class="mdl-cell mdl-cell--4-col ">
              <?php
                  echo "<h5><i class='material-icons'>group</i>Companions(".$Companions['num_rows']."명)</h5>";
              ?>
              <div class="demo-list-action mdl-list mdl-shadow--2dp" id="div_height">
                  <?php
                  if($Companions['num_rows']!=0){
                      foreach($Companions['result'] as $Companion){
                  ?>
                  <div class='mdl-list__item'>
                          <span class='mdl-list__item-primary-content'>
                  <?php
                            if($Companion->MemberProfile!=null){
                  ?>
                                   <img src="<?=URL;?>/images/user_profile_image/<?=$Companion->MemberIdx?>.jpg" style="max-width:100%; width:40px;">                  <?php
                            }else{
                  ?>
                                    <i class='material-icons mdl-list__item-avatar'>person</i>
                  <?php
                            }
                  ?>
                      <span><?=$Companion->MemberNickname?></span>
                    </span>
                  </div>
                  <?php
                      }
                  }
                  else{
                    echo"동행자가 없습니다.";
                  }
                  ?>
              </div>
            </div>
          </div>

          <div>
            <div class="mdl-cell mdl-cell--4-col" >
              <?php
                echo "<h5><i class='material-icons'>explore</i>Schedule(".$MaxPlaceDateNumber."Days)</h5>";
                if($MaxPlaceDateNumber!=null||$MinPlaceDateNumber!=null){
               ?>
                <div class="demo-list-action mdl-list" id="div_height">
                   <button onClick='StoryPlaceDateBtn_Click(0)' class='mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect'>TotalDays</button>
                   <?php
                       for($i=$MinPlaceDateNumber;$i<=$MaxPlaceDateNumber;$i++){
                           echo "
                                     <button onClick='StoryPlaceDateBtn_Click(".$i.")' class='mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect'>".$i."Days</button>
                                ";
                       }
                    ?>
                    <!-- 아작스 지도 div -->
                     <div id= "StoryPlaceInfoAjax"></div>
                    <!-- 아작스 지도 div 끝 -->
                </div>
              <?php
                }else{
              ?>
                <div class="demo-list-action mdl-list" id="div_height">
              <?php
                    echo"일정설정(목적지, 시간)을 정확히 입력해주세요.";
              ?>
                </div>
              <?php
                }
              ?>
             </div>
           </div>

           <div>
             <div class="mdl-cell mdl-cell--4-col" >
               <?php
                    echo "<h5><i class='material-icons'>check_circle</i>Materials(".$Materials['num_rows'].'개)</h5>';
               ?>
               <div class="demo-list-action mdl-list mdl-shadow--2dp" id="div_height">
                  <?php
                    if($Materials['num_rows']!=0){

                      foreach($Materials['result'] as $Material){
                  ?>
                        <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect" id="Material_<?=$Material->MaterialIdx?>">
                          <?=$Material->MaterialName?>
                        </button>
                  <?php
                      }
                    }
                    else{
                      echo"준비물이 없습니다.";
                    }
                  ?>
                </div>
              </div>
            </div>
        </div>
      </div>
    </section>
    <div id="print_icon" align="right" style=" padding-right:10px;">
      <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent" onClick="StoryPrint(<?=$StoryInfo->StoryIdx?>)">
        <i class="material-icons">
          print
        </i>
        요약
      </button>
    </div>
</main>

<script>
// 프린트 아작스
  function StoryPrint(Print_StoryNum){
    var thisStoryPrintWindow=window.open("/plan/storyprint/"+Print_StoryNum, "PrintTravel", "width=900, height=800, status=no, toolbar=no, menubar=no, scrollbars=no, resizable=yes" );
    thisStoryPrintWindow.focus();
  }
// 일차 아작스 끝
</script>
<script>
    function googleapisView(lat_input,lng_input) {
        var lat = lat_input; // 위도
        var lng = lng_input; // 경도
        var geocode = "http://maps.googleapis.com/maps/api/geocode/json?latlng="+lat+","+lng+"&sensor=false";
        var ReverseGeocoding_address = "";
        jQuery.ajax({
            url: geocode,
            // 지도보다 역지오코딩의 아작스 속도가 빠른 탓에 비동기->동기 설정
            async:false,
            type: 'POST',
               success: function(myJSONResult){
                        if(myJSONResult.status == 'OK') {
                            var i;
                            ReverseGeocoding_address=myJSONResult.results[0].formatted_address;
                            address_save(ReverseGeocoding_address);
                        }
                }
        });
    }

    function address_save(address_input){
      ReverseGeocoding_address = address_input;
      console.log(ReverseGeocoding_address);
    }
</script>
