plan.directive('backAnimation', ['$browser', '$location', function( $browser, $location ) {
    return {
        link: function( scope, element ) {
            $browser.onUrlChange( function( newUrl ) {
                if ( $location.absUrl() == newUrl ) {
                    element.addClass('reverse');
                }
            });

            scope.__childrenCount = 0;
            scope.$watch( function() {
                scope.__childrenCount = element.children().length;
            });

            scope.$watch('__childrenCount', function( newCount, oldCount ) {
                if ( newCount !== oldCount && newCount === 1 ) {
                    element.removeClass('reverse');
                }
            });
        }
    }
}]);

plan.directive('googleMap', ['$timeout', '$stateParams', 'socket', '$http', '$compile', '$mdDialog', '$mdMedia', function($timeout, $stateParams, socket, $http, $compile, $mdDialog, $mdMedia) {
  return {
    restrict: 'EA',
    link: function(scope, iElement, iAttrs) {
      var cordi = (iAttrs.center !== undefined) ? JSON.parse(iAttrs.center) : [37.561192, 127.030487];
      var zoom = (iAttrs.zoom !== undefined) ? Number(iAttrs.zoom) : 8;

      navigator.geolocation.getCurrentPosition(success, error);

      var current_latitude = 0;
      var current_longitude = 0;
      function success(position) {
        current_latitude = position.coords.latitude;
        current_longitude = position.coords.longitude;
        cordi = [current_latitude, current_longitude];
      }

      function error() {
        current_latitude = 35.895399;
        current_longitude = 128.621567;
        cordi = [current_latitude, current_longitude];
      }

      var el = document.createElement("div");
      el.style.width = "100%";
      el.style.height = "100%";
      iElement.prepend(el);

      var map = new google.maps.Map(el, {});
      var geocoder = new google.maps.Geocoder;
      var infowindow = new google.maps.InfoWindow;
      var directionsService = new google.maps.DirectionsService;
      var directionsDisplay = new google.maps.DirectionsRenderer({
                                suppressMarkers: true,
                                polylineOptions: {
                                    strokeColor: "blue",
                                    strokeWeight: 8
                                }
                              });

      var markers = [];   // 검색 시에 생기는 마커
      var specific_plan_marker = '';    // 특정 장소를 눌렀을 때 생기는 마커
      var share_marker = '';    // 북마크에 공유된 장소를 눌렀을 때 생기는 마커
      var plan_markers = [];    // 특정 일자를 눌렀을 때 생기는 마커
      var waypts = [];    // 이동 경로에 대한 장소 정보를 담고 있는 배열

      var standard_point = {};    // 거리 측정 시, 기준점이 되는 마커
      var standard_point_iCount = null;

      var is_leader = false;    // 리더인 사람을 구분해 주는 변수
      scope.is_glued = true;    // 채팅창 자동 스크롤링 변수

      scope.transport = google.maps.TravelMode.DRIVING;
      scope.transport_distance = 0;
      scope.transport_duration = 0;

      // 스토리 채팅창
      var story_chatting_control = function(controlDiv, map) {
        var story_chatting_UI = document.createElement('div');
        story_chatting_UI.setAttribute('layout', 'column');
        story_chatting_UI.setAttribute('layout-align', 'space-between none');

        story_chatting_UI.style.backgroundColor = '#fff';
        story_chatting_UI.style.background = 'rgba(255, 255, 255, 0.5)';
        story_chatting_UI.style.border = '2px solid #fff';
        story_chatting_UI.style.borderRadius = '3px';
        story_chatting_UI.style.boxShadow = '0 2px 6px rgba(0,0,0,.3)';
        story_chatting_UI.style.cursor = 'pointer';
        story_chatting_UI.style.textAlign = 'center';
        story_chatting_UI.style.height = '395px';
        story_chatting_UI.style.width = '200px';
        controlDiv.appendChild(story_chatting_UI);

        var story_chatting_content_div = document.createElement('div');
        story_chatting_content_div.setAttribute('scroll-glue', 'is_glued');

        story_chatting_content_div.style.overflowX = 'hidden';
        story_chatting_content_div.style.overflowY = 'auto';
        story_chatting_content_div.style.height = '92%';
        story_chatting_UI.appendChild(story_chatting_content_div);

        var story_chatting_content_ul = document.createElement('ul');
        story_chatting_content_ul.style.padding = '0';
        story_chatting_content_ul.style.margin = '0';

        story_chatting_content_div.appendChild(story_chatting_content_ul);

        var story_chatting_content_li = document.createElement('li');

        story_chatting_content_li.setAttribute('ng-repeat', 'message in messagise track by $index');
        story_chatting_content_li.setAttribute('type', 'none');
        story_chatting_content_li.innerHTML = '{{message}}';

        story_chatting_content_li.style.fontWeight = '900';
        story_chatting_content_li.style.fontSize = '14px';
        story_chatting_content_li.style.textAlign = 'left';
        story_chatting_content_li = $compile(story_chatting_content_li)(scope);
        story_chatting_content_ul.appendChild(story_chatting_content_li[0]);
        story_chatting_content_div = $compile(story_chatting_content_div)(scope);

        var story_chatting_input_div = document.createElement('div');
        story_chatting_input_div.style.height = '8%';

        story_chatting_UI.appendChild(story_chatting_input_div);

        var story_chatting_input = document.createElement('input');
        story_chatting_input.setAttribute('ng-model', 'send_message');
        story_chatting_input.setAttribute('ng-keypress', 'keyPress($event)');

        story_chatting_input.style.type = 'text';
        story_chatting_input.style.fontSize = '14px';
        story_chatting_input.style.border = '0';
        story_chatting_input.style.height = '90%';
        story_chatting_input.style.width = '98%';
        story_chatting_input = $compile(story_chatting_input)(scope);
        story_chatting_input_div.appendChild(story_chatting_input[0]);
      };

      // 지도의 크기를 크게 해주는 버튼
      var make_map_size_big_control = function(controlDiv, map) {
        var make_map_size_big_UI = document.createElement('div');
        make_map_size_big_UI.style.backgroundColor = '#fff';
        make_map_size_big_UI.style.border = '2px solid #fff';
        make_map_size_big_UI.style.borderRadius = '3px';
        make_map_size_big_UI.style.boxShadow = '0 2px 6px rgba(0,0,0,.3)';
        make_map_size_big_UI.style.cursor = 'pointer';
        make_map_size_big_UI.style.marginBottom = '22px';
        make_map_size_big_UI.style.textAlign = 'center';
        controlDiv.appendChild(make_map_size_big_UI);

        var make_map_size_big_Text = document.createElement('div');
        make_map_size_big_Text.style.color = 'rgb(25,25,25)';
        make_map_size_big_Text.style.fontFamily = 'Roboto,Arial,sans-serif';
        make_map_size_big_Text.style.fontSize = '16px';
        make_map_size_big_Text.style.lineHeight = '38px';
        make_map_size_big_Text.style.paddingLeft = '5px';
        make_map_size_big_Text.style.paddingRight = '5px';
        make_map_size_big_Text.innerHTML = 'ワイド';
        make_map_size_big_UI.appendChild(make_map_size_big_Text);

        make_map_size_big_UI.addEventListener('click', function() {
          set_wide_map_listener();
        });
      };

      // 지도의 크기를 원 상태로 돌려주는 버튼
      var make_map_size_normal_control = function(controlDiv, map) {
        var make_map_size_normal_UI = document.createElement('div');
        make_map_size_normal_UI.style.backgroundColor = '#fff';
        make_map_size_normal_UI.style.border = '2px solid #fff';
        make_map_size_normal_UI.style.borderRadius = '3px';
        make_map_size_normal_UI.style.boxShadow = '0 2px 6px rgba(0,0,0,.3)';
        make_map_size_normal_UI.style.cursor = 'pointer';
        make_map_size_normal_UI.style.marginBottom = '22px';
        make_map_size_normal_UI.style.textAlign = 'center';
        controlDiv.appendChild(make_map_size_normal_UI);

        var make_map_size_normal_Text = document.createElement('div');
        make_map_size_normal_Text.style.color = 'rgb(25,25,25)';
        make_map_size_normal_Text.style.fontFamily = 'Roboto,Arial,sans-serif';
        make_map_size_normal_Text.style.fontSize = '16px';
        make_map_size_normal_Text.style.lineHeight = '38px';
        make_map_size_normal_Text.style.paddingLeft = '5px';
        make_map_size_normal_Text.style.paddingRight = '5px';
        make_map_size_normal_Text.innerHTML = 'オリジナル';
        make_map_size_normal_UI.appendChild(make_map_size_normal_Text);

        make_map_size_normal_UI.addEventListener('click', function() {
          set_normal_map_listener();
        });
      };

      // 특정인이 주도권을 잡게 되는 버튼
      var mapControl = function(controlDiv, map) {
        // Set CSS for the control border.
        var controlUI = document.createElement('div');
        controlUI.style.backgroundColor = '#fff';
        controlUI.style.border = '2px solid #fff';
        controlUI.style.borderRadius = '3px';
        controlUI.style.boxShadow = '0 2px 6px rgba(0,0,0,.3)';
        controlUI.style.cursor = 'pointer';
        controlUI.style.marginBottom = '22px';
        controlUI.style.textAlign = 'center';
        controlDiv.appendChild(controlUI);

        // Set CSS for the control interior.
        var controlText = document.createElement('div');
        controlText.style.color = 'rgb(25,25,25)';
        controlText.style.fontFamily = 'Roboto,Arial,sans-serif';
        controlText.style.fontSize = '16px';
        controlText.style.lineHeight = '38px';
        controlText.style.paddingLeft = '5px';
        controlText.style.paddingRight = '5px';
        controlText.innerHTML = 'リーダモード';
        controlUI.appendChild(controlText);

        controlUI.addEventListener('click', function() {
          set_leader_map_listener();
        });
      };

      // 주도권을 가진 사람의 정보가 담긴 버튼
      var leader_info_control = function(controlDiv, map) {
        var now_leader_UI = document.createElement('div');
        now_leader_UI.style.backgroundColor = '#fff';
        now_leader_UI.style.border = '2px solid #fff';
        now_leader_UI.style.borderRadius = '3px';
        now_leader_UI.style.boxShadow = '0 2px 6px rgba(0,0,0,.3)';
        now_leader_UI.style.cursor = 'pointer';
        now_leader_UI.style.marginBottom = '22px';
        now_leader_UI.style.textAlign = 'center';
        controlDiv.appendChild(now_leader_UI);

        var now_leader_Text = document.createElement('div');
        now_leader_Text.style.color = 'rgb(25,25,25)';
        now_leader_Text.style.fontFamily = 'Roboto,Arial,sans-serif';
        now_leader_Text.style.fontSize = '16px';
        now_leader_Text.style.lineHeight = '38px';
        now_leader_Text.style.paddingLeft = '5px';
        now_leader_Text.style.paddingRight = '5px';
        now_leader_UI.appendChild(now_leader_Text);
      };

      // 주도권을 가졌을 때, 주도권을 해제하기 위한 버튼
      var leader_exit_control = function(controlDiv, map) {
        var now_leader_exit_UI = document.createElement('div');
        now_leader_exit_UI.style.backgroundColor = '#fff';
        now_leader_exit_UI.style.border = '2px solid #fff';
        now_leader_exit_UI.style.borderRadius = '3px';
        now_leader_exit_UI.style.boxShadow = '0 2px 6px rgba(0,0,0,.3)';
        now_leader_exit_UI.style.cursor = 'pointer';
        now_leader_exit_UI.style.marginBottom = '22px';
        now_leader_exit_UI.style.textAlign = 'center';
        controlDiv.appendChild(now_leader_exit_UI);

        var now_leader_exit_Text = document.createElement('div');
        now_leader_exit_Text.style.color = 'rgb(25,25,25)';
        now_leader_exit_Text.style.fontFamily = 'Roboto,Arial,sans-serif';
        now_leader_exit_Text.style.fontSize = '16px';
        now_leader_exit_Text.style.lineHeight = '38px';
        now_leader_exit_Text.style.paddingLeft = '5px';
        now_leader_exit_Text.style.paddingRight = '5px';
        now_leader_exit_Text.innerHTML = 'エグジット';
        now_leader_exit_UI.appendChild(now_leader_exit_Text);

        now_leader_exit_UI.addEventListener('click', function() {
          del_leader_map_listener();
        });
      };

      // 주도권을 주었을 때, 주도권을 해제할 수 있는 버튼
      var leader_authority_off_control = function(controlDiv, map) {
        var leader_authority_off_UI = document.createElement('div');
        leader_authority_off_UI.style.backgroundColor = '#fff';
        leader_authority_off_UI.style.border = '2px solid #fff';
        leader_authority_off_UI.style.borderRadius = '3px';
        leader_authority_off_UI.style.boxShadow = '0 2px 6px rgba(0,0,0,.3)';
        leader_authority_off_UI.style.cursor = 'pointer';
        leader_authority_off_UI.style.marginBottom = '22px';
        leader_authority_off_UI.style.textAlign = 'center';
        controlDiv.appendChild(leader_authority_off_UI);

        var leader_authority_off_Text = document.createElement('div');
        leader_authority_off_Text.style.color = 'rgb(25,25,25)';
        leader_authority_off_Text.style.fontFamily = 'Roboto,Arial,sans-serif';
        leader_authority_off_Text.style.fontSize = '16px';
        leader_authority_off_Text.style.lineHeight = '38px';
        leader_authority_off_Text.style.paddingLeft = '5px';
        leader_authority_off_Text.style.paddingRight = '5px';
        leader_authority_off_Text.innerHTML = '主導権オフ';
        leader_authority_off_UI.appendChild(leader_authority_off_Text);

        leader_authority_off_UI.addEventListener('click', function() {
          del_leader_authority_listener();
        });
      };

      // 주도권을 주지 않았을 때, 주도권을 줄 수 있는 버튼
      var leader_authority_on_control = function(controlDiv, map) {
        var leader_authority_on_UI = document.createElement('div');
        leader_authority_on_UI.style.backgroundColor = '#fff';
        leader_authority_on_UI.style.border = '2px solid #fff';
        leader_authority_on_UI.style.borderRadius = '3px';
        leader_authority_on_UI.style.boxShadow = '0 2px 6px rgba(0,0,0,.3)';
        leader_authority_on_UI.style.cursor = 'pointer';
        leader_authority_on_UI.style.marginBottom = '22px';
        leader_authority_on_UI.style.textAlign = 'center';
        controlDiv.appendChild(leader_authority_on_UI);

        var leader_authority_on_Text = document.createElement('div');
        leader_authority_on_Text.style.color = 'rgb(25,25,25)';
        leader_authority_on_Text.style.fontFamily = 'Roboto,Arial,sans-serif';
        leader_authority_on_Text.style.fontSize = '16px';
        leader_authority_on_Text.style.lineHeight = '38px';
        leader_authority_on_Text.style.paddingLeft = '5px';
        leader_authority_on_Text.style.paddingRight = '5px';
        leader_authority_on_Text.innerHTML = '主導権オン';
        leader_authority_on_UI.appendChild(leader_authority_on_Text);

        leader_authority_on_UI.addEventListener('click', function() {
          set_leader_authority_listener();
        });
      };

      // 기준점을 정했을 때, 생기는 안내판
      var standard_point_on_control = function(controlDiv, map) {
        var standard_point_on_UI = document.createElement('div');
        standard_point_on_UI.style.backgroundColor = '#fff';
        standard_point_on_UI.style.border = '2px solid #fff';
        standard_point_on_UI.style.borderRadius = '3px';
        standard_point_on_UI.style.boxShadow = '0 2px 6px rgba(0,0,0,.3)';
        standard_point_on_UI.style.cursor = 'pointer';
        standard_point_on_UI.style.marginBottom = '22px';
        standard_point_on_UI.style.textAlign = 'center';
        controlDiv.appendChild(standard_point_on_UI);

        var standard_point_on_Text = document.createElement('div');
        standard_point_on_Text.setAttribute('ng-model', 'standard_point_name');
        standard_point_on_Text.innerHTML = '{{standard_point_name}}';

        standard_point_on_Text.style.color = 'rgb(25,25,25)';
        standard_point_on_Text.style.fontFamily = 'Roboto,Arial,sans-serif';
        standard_point_on_Text.style.fontSize = '16px';
        standard_point_on_Text.style.lineHeight = '38px';
        standard_point_on_Text.style.paddingLeft = '5px';
        standard_point_on_Text.style.paddingRight = '5px';
        standard_point_on_Text.style.display = 'inline';
        standard_point_on_Text = $compile(standard_point_on_Text)(scope);
        standard_point_on_UI.appendChild(standard_point_on_Text[0]);

        var standard_point_on_Exit = document.createElement('i');
        standard_point_on_Exit.setAttribute('class', 'material-icons');
        standard_point_on_Exit.innerHTML = 'clear';

        standard_point_on_Exit.style.display = 'inline';
        standard_point_on_Exit.style.float = 'right';
        standard_point_on_Exit.style.padding = '5px 10px 5px 5px';
        standard_point_on_Exit = $compile(standard_point_on_Exit)(scope);
        standard_point_on_UI.appendChild(standard_point_on_Exit[0]);

        standard_point_on_Exit[0].addEventListener('click', function() {
          for(var iCount in markers) {
            markers[iCount].setAnimation(null);
          }
          standard_point = '';
          standard_point_iCount = '';
          map.controls[google.maps.ControlPosition.BOTTOM_CENTER].removeAt(0);
          map.controls[google.maps.ControlPosition.RIGHT_CENTER].removeAt(1);
          map.controls[google.maps.ControlPosition.RIGHT_CENTER].removeAt(0);
          directionsDisplay.setMap(null);
          scope.transport = google.maps.TravelMode.DRIVING;
        });
      };

      // 기준점을 정했을 때, 이동 수단을 고를 수 있는 메뉴
      var standard_point_transport_control = function(ControlDiv, map) {
        var standard_point_transport_UI = document.createElement('div');
        standard_point_transport_UI.style.backgroundColor = '#fff';
        standard_point_transport_UI.style.border = '2px solid #fff';
        standard_point_transport_UI.style.borderRadius = '3px';
        standard_point_transport_UI.style.boxShadow = '0 2px 6px rgba(0,0,0,.3)';
        standard_point_transport_UI.style.cursor = 'pointer';
        standard_point_transport_UI.style.marginBottom = '22px';
        standard_point_transport_UI.style.textAlign = 'center';
        ControlDiv.appendChild(standard_point_transport_UI);

        var standard_point_transport_driving = document.createElement('i');
        standard_point_transport_driving.setAttribute('class', 'material-icons');
        standard_point_transport_driving.innerHTML = 'directions_car';
        standard_point_transport_driving.style.fontSize = '40px';
        standard_point_transport_driving.style.padding = '10px';
        standard_point_transport_driving.style.color = 'red';
        standard_point_transport_driving = $compile(standard_point_transport_driving)(scope);
        standard_point_transport_UI.appendChild(standard_point_transport_driving[0]);
        standard_point_transport_driving[0].addEventListener('click', function() {
          this.parentNode.childNodes.forEach(function(item){
            item.style.color = 'black';
          });
          this.style.color = 'red';
          scope.transport = google.maps.TravelMode.DRIVING;
        });

        var standard_point_transport_bicycling = document.createElement('i');
        standard_point_transport_bicycling.setAttribute('class', 'material-icons');
        standard_point_transport_bicycling.innerHTML = 'directions_bike';
        standard_point_transport_bicycling.style.fontSize = '40px';
        standard_point_transport_bicycling.style.padding = '10px';
        standard_point_transport_bicycling = $compile(standard_point_transport_bicycling)(scope);
        standard_point_transport_UI.appendChild(standard_point_transport_bicycling[0]);
        standard_point_transport_bicycling[0].addEventListener('click', function() {
          this.parentNode.childNodes.forEach(function(item){
            item.style.color = 'black';
          });
          this.style.color = 'red';
          scope.transport = google.maps.TravelMode.BICYCLING;
        });

        var standard_point_transport_transit = document.createElement('i');
        standard_point_transport_transit.setAttribute('class', 'material-icons');
        standard_point_transport_transit.innerHTML = 'directions_transit';
        standard_point_transport_transit.style.fontSize = '40px';
        standard_point_transport_transit.style.padding = '10px';
        standard_point_transport_transit = $compile(standard_point_transport_transit)(scope);
        standard_point_transport_UI.appendChild(standard_point_transport_transit[0]);
        standard_point_transport_transit[0].addEventListener('click', function() {
          this.parentNode.childNodes.forEach(function(item){
            item.style.color = 'black';
          });
          this.style.color = 'red';
          scope.transport = google.maps.TravelMode.TRANSIT;
        });

        var standard_point_transport_walking = document.createElement('i');
        standard_point_transport_walking.setAttribute('class', 'material-icons');
        standard_point_transport_walking.innerHTML = 'directions_walk';
        standard_point_transport_walking.style.fontSize = '40px';
        standard_point_transport_walking.style.padding = '10px';
        standard_point_transport_walking = $compile(standard_point_transport_walking)(scope);
        standard_point_transport_UI.appendChild(standard_point_transport_walking[0]);
        standard_point_transport_walking[0].addEventListener('click', function() {
          this.parentNode.childNodes.forEach(function(item){
            item.style.color = 'black';
          });
          this.style.color = 'red';
          scope.transport = google.maps.TravelMode.WALKING;
        });
      }

      // 기준점을 고르고, 다른 마커를 클릭했을 때, 결과로 나오는 거리와 시간
      var standard_point_transport_info_control = function(ControlDiv, map) {
        var standard_point_transport_info_UI = document.createElement('div');
        standard_point_transport_info_UI.style.backgroundColor = '#fff';
        standard_point_transport_info_UI.style.border = '2px solid #fff';
        standard_point_transport_info_UI.style.borderRadius = '3px';
        standard_point_transport_info_UI.style.boxShadow = '0 2px 6px rgba(0,0,0,.3)';
        standard_point_transport_info_UI.style.cursor = 'pointer';
        standard_point_transport_info_UI.style.marginBottom = '22px';
        standard_point_transport_info_UI.style.textAlign = 'center';
        standard_point_transport_info_UI.style.fontWeight = 'bold';
        ControlDiv.appendChild(standard_point_transport_info_UI);

        var standard_point_transport_info_distance = document.createElement('span');
        standard_point_transport_info_distance.setAttribute('ng-model', 'transport_distance');
        standard_point_transport_info_distance.innerHTML = '{{transport_distance}}, ';
        standard_point_transport_info_distance.style.fontSize = '24px';
        standard_point_transport_info_distance = $compile(standard_point_transport_info_distance)(scope);
        standard_point_transport_info_UI.appendChild(standard_point_transport_info_distance[0]);

        var standard_point_transport_info_duration = document.createElement('span');
        standard_point_transport_info_duration.setAttribute('ng-model', 'transport_duration');
        standard_point_transport_info_duration.innerHTML = '{{transport_duration}}';
        standard_point_transport_info_duration.style.fontSize = '24px';
        standard_point_transport_info_duration = $compile(standard_point_transport_info_duration)(scope);
        standard_point_transport_info_UI.appendChild(standard_point_transport_info_duration[0]);
      };

      // wide 클릭
      var set_wide_map_listener = function() {
        map.controls[google.maps.ControlPosition.RIGHT_TOP].removeAt(0);

        // 맵의 크기를 원상태로 해주는 버튼을 왼쪽 중앙에 달아준다.
        var make_map_size_normal_control_div = document.createElement('div');
        var make_map_size_normal_control_obj = new make_map_size_normal_control(make_map_size_normal_control_div, map);

        make_map_size_normal_control_obj.index = 1;
        map.controls[google.maps.ControlPosition.RIGHT_TOP].push(make_map_size_normal_control_div);

        scope.is_map_wide = true;
        scope.myStyle = {'height': '920px', 'margin': '0px', 'padding': '0px'};
        scope.$apply();
        google.maps.event.trigger(map, "resize");
      };

      // normal 클릭
      var set_normal_map_listener = function() {
        map.controls[google.maps.ControlPosition.RIGHT_TOP].removeAt(0);

        // 맵의 크기를 크게 해주는 버튼을 왼쪽 중앙에 달아준다.
        var make_map_size_big_control_div = document.createElement('div');
        var make_map_size_big_control_obj = new make_map_size_big_control(make_map_size_big_control_div, map);

        make_map_size_big_control_obj.index = 1;
        map.controls[google.maps.ControlPosition.RIGHT_TOP].push(make_map_size_big_control_div);

        scope.is_map_wide = false;
        scope.myStyle = {'height': '400px', 'margin': '0px', 'padding': '0px', 'transition': '.5s linear all'};
        scope.$apply();

        $timeout(function() {
          google.maps.event.trigger(map, "resize");
        }, 1000);
      };

      // 리더 모드 클릭
      var set_leader_map_listener = function() {
        // 리더 모드를 클릭한 유저의 컨트롤을 바꿔준다.
        is_leader = true;

        // 먼저, 리더 모드 버튼을 삭제해준다.
        map.controls[google.maps.ControlPosition.TOP_RIGHT].removeAt(0);

        // 주도자의 정보가 담긴 정보를 TOP_CENTER에 달아준다.
        var leader_info_control_div = document.createElement('div');
        var leader_info_control_obj = new leader_info_control(leader_info_control_div, map);

        leader_info_control_div.childNodes[0].style.fontSize = '16px';
        leader_info_control_div.childNodes[0].style.lineHeight = '38px';
        leader_info_control_div.childNodes[0].style.paddingLeft = '5px';
        leader_info_control_div.childNodes[0].style.paddingRight = '5px';
        leader_info_control_div.childNodes[0].innerText = login_user_nickname;

        leader_info_control_obj.index = 1;
        map.controls[google.maps.ControlPosition.TOP_CENTER].push(leader_info_control_div);

        // 주도권을 해제하기 위한 버튼을 TOP_RIGHT에 달아준다.
        var leader_exit_control_div = document.createElement('div');
        var leader_exit_control_obj = new leader_exit_control(leader_exit_control_div, map);

        leader_exit_control_obj.index = 1;
        map.controls[google.maps.ControlPosition.TOP_RIGHT].push(leader_exit_control_div);

        // 주도권을 가진 유저의 정보를 서버로 넘겨줘서 현재 계획 페이지에 접속한 유저들에게 전달한다.
        var leader_info = {'leader_nickname': login_user_nickname, 'story_num': $stateParams.story_idx};
        socket.emit('leader:map', leader_info);

        // 클릭한 좌표 넘기기
        google.maps.event.addListener(map, 'click', function (event) {
          var click_info = {lat: parseFloat(event.latLng.lat()), lng: parseFloat(event.latLng.lng())};
          click_info.story_num = $stateParams.story_idx;
          socket.emit('click:map', click_info);
        });

        // 드래그가 끝났을 때의 좌표 넘기기
        google.maps.event.addListener(map, 'dragend', function (event) {
          var dragend_info = {lat: parseFloat(map.getCenter().lat()), lng: parseFloat(map.getCenter().lng())};
          dragend_info.story_num = $stateParams.story_idx;
          socket.emit('dragend:map', dragend_info);
        });

        // 줌이 변경되었을 때의 값 넘기기
        google.maps.event.addListener(map, 'zoom_changed', function (event) {
          var zoom_info = {zoom: map.getZoom()};
          zoom_info.story_num = $stateParams.story_idx;
          socket.emit('zoom_changed:map', zoom_info);
        });
      };

      // 맵이 주도자에게 넘어갔을 때
      socket.on('leader:map', function (data) {
        var confirm = $mdDialog.confirm()
                        .title('地図の主導権を ' + data.leader_nickname + 'さんにくれませんか？')
                        .ok('いいえ')
                        .cancel('はい');
        $mdDialog.show(confirm).then(function() {
          // 주도자에게 맵의 주도권을 주지 않았을 경우

          // 먼저, 리더 모드 버튼을 삭제해준다.
          map.controls[google.maps.ControlPosition.TOP_RIGHT].removeAt(0);

          // 주도자의 정보가 담긴 정보를 TOP_CENTER에 달아준다.
          var leader_info_control_div = document.createElement('div');
          var leader_info_control_obj = new leader_info_control(leader_info_control_div, map);

          leader_info_control_div.childNodes[0].style.fontSize = '16px';
          leader_info_control_div.childNodes[0].style.lineHeight = '38px';
          leader_info_control_div.childNodes[0].style.paddingLeft = '5px';
          leader_info_control_div.childNodes[0].style.paddingRight = '5px';
          leader_info_control_div.childNodes[0].innerText = data.leader_nickname;

          leader_info_control_obj.index = 1;
          map.controls[google.maps.ControlPosition.TOP_CENTER].push(leader_info_control_div);

          // 주도자에게 주도권을 주지 않았으니 주도권을 줄 수 있는 On 버튼을 TOP_RIGHT에 달아준다.
          var leader_authority_on_control_div = document.createElement('div');
          var leader_authority_on_control_obj = new leader_authority_on_control(leader_authority_on_control_div, map);

          leader_authority_on_control_obj.index = 1;
          map.controls[google.maps.ControlPosition.TOP_RIGHT].push(leader_authority_on_control_div);

          /* 주도자가 행한 동작을 받을 수 없게 소켓 리스너를 떼어준다 */
          socket.removeListener('search:map');
          socket.removeListener('click:map');
          socket.removeListener('dragend:map');
          socket.removeListener('zoom_changed:map');
        }, function() {
          // 주도자에게 맵의 주도권을 주었을 경우

          // 먼저, 리더 모드 버튼을 삭제해준다.
          map.controls[google.maps.ControlPosition.TOP_RIGHT].removeAt(0);

          // 주도자의 정보가 담긴 정보를 TOP_CENTER에 달아준다.
          var leader_info_control_div = document.createElement('div');
          var leader_info_control_obj = new leader_info_control(leader_info_control_div, map);

          leader_info_control_div.childNodes[0].style.fontSize = '16px';
          leader_info_control_div.childNodes[0].style.lineHeight = '38px';
          leader_info_control_div.childNodes[0].style.paddingLeft = '5px';
          leader_info_control_div.childNodes[0].style.paddingRight = '5px';
          leader_info_control_div.childNodes[0].innerText = data.leader_nickname;

          leader_info_control_obj.index = 1;
          map.controls[google.maps.ControlPosition.TOP_CENTER].push(leader_info_control_div);

          // 주도자에게 주도권을 주었으니 주도권을 해체할 수 있는 Off 버튼을 TOP_RIGHT에 달아준다.
          var leader_authority_off_control_div = document.createElement('div');
          var leader_authority_off_control_obj = new leader_authority_off_control(leader_authority_off_control_div, map);

          leader_authority_off_control_obj.index = 1;
          map.controls[google.maps.ControlPosition.TOP_RIGHT].push(leader_authority_off_control_div);

          /* 주도자가 행한 동작을 받을 수 있는 소켓 리스너를 달아준다 */
          // 검색된 장소 데이터 받기
          socket.on('search:map', function (data) {
            console.log('data');
            $http({
                method: 'post',
                url: '/Plan/toPlaceSearch',
                data: {'region': data.place_region, 'name': data.place_name, 'category': data.place_category},
                headers: {'Content-Type': 'application/json; charset=utf-8'}
            }).success(function(data, status, headers, config) {
                scope.search_data = data;
            });
          });

          // 클릭된 장소의 좌표 받기
          socket.on('click:marker', function (data) {
            var cnt = markers.length;
            for (var iCount = 0; iCount < cnt; iCount++) {
              if (markers[iCount].position.lat() == data.place_latlng.lat && markers[iCount].position.lng() == data.place_latlng.lng) {
                // 클릭된 장소의 좌표와 마커의 좌표가 같다면
                console.log('dd');
                google.maps.event.trigger(markers[iCount], 'click');
                break;
              }
            }
            map.panTo(data.place_latlng);
          });

          // 클릭된 좌표 받기
          socket.on('click:map', function (data) {
            var click_latlng = {lat: data.lat, lng: data.lng};
            map.panTo(click_latlng);
          });

          // 드래그가 끝났을 때의 좌표 받기
          socket.on('dragend:map', function (data) {
            var dragend_latlng = {lat: data.lat, lng: data.lng};
            map.panTo(dragend_latlng);
          });

          // 줌이 변경되었을 때의 값 받기
          socket.on('zoom_changed:map', function (data) {
            map.setZoom(data.zoom);
          });
        });
      });

      // Exit 클릭
      var del_leader_map_listener = function() {
        is_leader = false;

        // 리더 정보가 담긴 컨트롤 삭제
        map.controls[google.maps.ControlPosition.TOP_CENTER].removeAt(0);

        // Exit 버튼 컨트롤 삭제
        map.controls[google.maps.ControlPosition.TOP_RIGHT].removeAt(0);

        // 리더 모드 버튼을 TOP_RIGHT에 달아준다.
        var centerControlDiv = document.createElement('div');
        var centerControl = new mapControl(centerControlDiv, map);

        centerControl.index = 1;
        map.controls[google.maps.ControlPosition.TOP_RIGHT].push(centerControlDiv);

        // 리더 모드가 해제되었다는 사실을 현재 계획 페이지에 접속해있는 사람들에게 알린다.
        var del_leader_info = {'story_num': $stateParams.story_idx};
        socket.emit('del_leader:map', del_leader_info);
      };

      // 리더 모드가 해제되었다면
      socket.on('del_leader:map', function(data) {
        // 리더 정보가 담긴 컨트롤 삭제
        map.controls[google.maps.ControlPosition.TOP_CENTER].removeAt(0);

        // Exit 버튼 컨트롤 삭제
        map.controls[google.maps.ControlPosition.TOP_RIGHT].removeAt(0);

        // 리더 모드 버튼을 TOP_RIGHT에 달아준다.
        var centerControlDiv = document.createElement('div');
        var centerControl = new mapControl(centerControlDiv, map);

        centerControl.index = 1;
        map.controls[google.maps.ControlPosition.TOP_RIGHT].push(centerControlDiv);

        /* 주도자가 행한 동작을 받을 수 없게 소켓 리스너를 떼어준다 */
        socket.removeListener('click:marker');
        socket.removeListener('click:map');
        socket.removeListener('dragend:map');
        socket.removeListener('zoom_changed:map');
      });

      // Authority Off 클릭
      var del_leader_authority_listener = function() {
        map.controls[google.maps.ControlPosition.TOP_RIGHT].removeAt(0);

        // 주도권을 줄 수 있는 On 버튼을 TOP_RIGHT에 달아준다.
        var leader_authority_on_control_div = document.createElement('div');
        var leader_authority_on_control_obj = new leader_authority_on_control(leader_authority_on_control_div, map);

        leader_authority_on_control_obj.index = 1;
        map.controls[google.maps.ControlPosition.TOP_RIGHT].push(leader_authority_on_control_div);

        // 주도자가 행한 동작을 받을 수 없게 소켓 리스너를 떼어준다.
        socket.removeListener('click:marker');
        socket.removeListener('click:map');
        socket.removeListener('dragend:map');
        socket.removeListener('zoom_changed:map');
      };

      // Authority On 클릭
      var set_leader_authority_listener = function() {
        map.controls[google.maps.ControlPosition.TOP_RIGHT].removeAt(0);

        // 주도권을 해제할 수 있는 Off 버튼을 TOP_RIGHT에 달아준다.
        var leader_authority_off_control_div = document.createElement('div');
        var leader_authority_off_control_obj = new leader_authority_off_control(leader_authority_off_control_div, map);

        leader_authority_off_control_obj.index = 1;
        map.controls[google.maps.ControlPosition.TOP_RIGHT].push(leader_authority_off_control_div);

        /* 주도자가 행한 동작을 받을 수 있는 소켓 리스너를 달아준다 */
        // 검색된 장소 데이터 받기
        socket.on('search:map', function (data) {
          console.log('data');
          $http({
              method: 'post',
              url: '/Plan/toPlaceSearch',
              data: {'region': data.place_region, 'name': data.place_name, 'category': data.place_category},
              headers: {'Content-Type': 'application/json; charset=utf-8'}
          }).success(function(data, status, headers, config) {
              scope.search_data = data;
          });
        });

        // 클릭된 장소의 좌표 받기
        socket.on('click:marker', function (data) {
          var cnt = markers.length;
          for (var iCount = 0; iCount < cnt; iCount++) {
            if (markers[iCount].position.lat() == data.place_latlng.lat && markers[iCount].position.lng() == data.place_latlng.lng) {
              // 클릭된 장소의 좌표와 마커의 좌표가 같다면
              console.log('dd');
              google.maps.event.trigger(markers[iCount], 'click');
              break;
            }
          }
          map.panTo(data.place_latlng);
        });

        // 클릭된 좌표 받기
        socket.on('click:map', function (data) {
          var click_latlng = {lat: data.lat, lng: data.lng};
          map.panTo(click_latlng);
        });

        // 드래그가 끝났을 때의 좌표 받기
        socket.on('dragend:map', function (data) {
          var dragend_latlng = {lat: data.lat, lng: data.lng};
          map.panTo(dragend_latlng);
        });

        // 줌이 변경되었을 때의 값 받기
        socket.on('zoom_changed:map', function (data) {
          map.setZoom(data.zoom);
        });
      };

      // 장소 검색 후, 지도에 마크 찍어주기
      scope.$watch('search_data', function(current, old) {
        if (markers) {
          for (i in markers) {
            if (!isNaN(i)) {
              markers[i].setMap(null);
            }
          }
        }

        if (specific_plan_marker) { specific_plan_marker.setMap(null); specific_plan_marker = ''; }
        if (share_marker) { share_marker.setMap(null); share_marker = ''; }
        directionsDisplay.setMap(null);

        if (plan_markers) { for (i in plan_markers) { plan_markers[i].setMap(null); } }

        if (current) {
          if (scope.nation_identifier) {
            // 해당 국가로 맵을 이동
            geocoder.geocode({'address': scope.nation_identifier}, function(results, status) {
              if (status === google.maps.GeocoderStatus.OK) {
                var latlng = {lat: parseFloat(results[0].geometry.location.lat()), lng: parseFloat(results[0].geometry.location.lng())};
                map.setCenter(latlng);
                map.setZoom(5);
              }
            });
          } else if (scope.region_identifier) {
            // 해당 지역으로 맵을 이동
            geocoder.geocode({'address': scope.region_identifier}, function(results, status) {
              if (status === google.maps.GeocoderStatus.OK) {
                var latlng = {lat: parseFloat(results[0].geometry.location.lat()), lng: parseFloat(results[0].geometry.location.lng())};
                map.setCenter(latlng);
                map.setZoom(10);
              }
            });
          }

          var cnt = current.length;

          for (var iCount = 0; iCount < cnt; iCount++) {
            var icon_url_base = "http://167.88.115.33/images/place_category/";
            switch(current[iCount].CategoryPlaceIdx) {
              case '1': icon_url_base += "time_to_leave-.png"; break;
              case '2': icon_url_base += "restaurant-.png"; break;
              case '3': icon_url_base += "hotel-.png"; break;
              case '4': icon_url_base += "local_grocery_store-.png"; break;
              case '5': icon_url_base += "account_balance-.png"; break;
              case '6': icon_url_base += "help-.png"; break;
            }

            var latlng = {lat: parseFloat(current[iCount].PlaceLatitude), lng: parseFloat(current[iCount].PlaceLongtitude)};
            var title = '<div>' + current[iCount].PlaceName + '<br />' + current[iCount].PlaceExplain + '<br />';
            title += '<md-button class="md-raised md-primary" ng-click="showClickPlaceInfo($event, ' + current[iCount].PlaceIdx +')" style="font-weight: 700; color: white;">詳細を見る</md-button>';
            title += '<md-button class="md-raised md-warn" ng-click="setUpStandardPoint(' + iCount + ')" style="font-weight: 700; color: white;">基準点として設定</md-button></div>';

            markers[iCount] = new google.maps.Marker({
              position: latlng,
              map: map,
              title: title,
              name: current[iCount].PlaceName,
              icon: icon_url_base,
              marker_count: iCount
            });

            markers[iCount].addListener('click', function() {
              if (standard_point_iCount) {
                // 기준점이 존재한다면
                directionsService.route({
                  origin: markers[standard_point_iCount].getPosition(),
                  destination: this.getPosition(),
                  travelMode: scope.transport
                }, function(response, status) {
                  if (status === google.maps.DirectionsStatus.OK) {
                    directionsDisplay.setMap(map);
                    directionsDisplay.setDirections(response);

                    scope.transport_distance = response.routes[0].legs[0].distance.text;
                    scope.transport_duration = response.routes[0].legs[0].duration.text;
                    scope.$apply();

                    console.log('距離 : ' + response.routes[0].legs[0].distance.text + ' / かかる時間 : ' + response.routes[0].legs[0].duration.text);
                    console.log(response);
                  } else {
                    directionsDisplay.setMap(null);

                    scope.transport_distance = 0;
                    scope.transport_duration = 0;
                    scope.$apply();

                    $mdDialog.show(
                      $mdDialog.alert({
                        template:
                          '<md-dialog md-theme="default" ng-class="dialog.css" class="md-default-theme _md-transition-in" role="alertdialog" tabindex="-1" aria-describedby="dialogContent_11" style="bottom: 300px;">' +
                          '  <md-dialog-content class="md-dialog-content" role="document" tabindex="-1" id="dialogContent_11">' +
                          '    <h2 class="md-title ng-binding">検索結果がありません！</h2>' +
                          '  </md-dialog-content>' +
                          '</md-dialog>'
                      })
                      .clickOutsideToClose(true)
                    );
                  }
                });
              }

              if (is_leader) {
                // 맵의 주도자 라면
                infowindow.setContent(this.title);
                infowindow.open(map, this);
                console.log(this);
                var data = {
                    'story_idx': $stateParams.story_idx,
                    'place_latlng': this.position
                };

                socket.emit('click:marker', data);
              } else {
                // 맵의 주도가 아니라면;
                var complied = $compile(this.title)(scope);
                infowindow.setContent(complied[0]);
                infowindow.open(map, this);
              }
            });
          }
        }
      });

      // 검색 된 장소 중에 특정 장소 클릭
      scope.$watch('click_place', function(current, old) {
        if (markers[current]) {
          var click_place_latlng = markers[current].position;
          map.setZoom(14);
          map.setCenter(click_place_latlng);

          var complied = $compile(markers[current].title)(scope);
          infowindow.setContent(complied[0]);
          infowindow.open(map, markers[current]);
        }
      });

      // 검색 된 장소 중에 특정 장소 호버
      scope.$watch('hover_place', function(current, old) {
        if (markers[current]) {
          var click_place_latlng = markers[current].position;
          map.setZoom(14);
          map.setCenter(click_place_latlng);

          var complied = $compile(markers[current].title)(scope);
          infowindow.setContent(complied[0]);
          infowindow.open(map, markers[current]);
        }
      });

      // 계획으로 지정된 장소 중에 특정 장소 클릭
      scope.$watch('click_place_plan', function(current, old) {
        //if (markers) { for (i in markers) { markers[i].setMap(null); } markers = []; }
        if (specific_plan_marker) { specific_plan_marker.setMap(null); specific_plan_marker = ''; }
        if (share_marker) { share_marker.setMap(null); share_marker = ''; }
        directionsDisplay.setMap(null);

        if (plan_markers) { for (i in plan_markers) { plan_markers[i].setMap(null); } }

        if (current) {
          var url = "http://167.88.115.33/Plan/getStorySharePlace";
          var data = {'place_idx': current};

          $http.post(url, data).then(function(response) {
            var icon_url_base = "http://167.88.115.33/images/place_category/";

            switch(response.data[0].CategoryPlaceIdx) {
              case '1': icon_url_base += "time_to_leave-.png"; break;
              case '2': icon_url_base += "restaurant-.png"; break;
              case '3': icon_url_base += "hotel-.png"; break;
              case '4': icon_url_base += "local_grocery_store-.png"; break;
              case '5': icon_url_base += "account_balance-.png"; break;
              case '6': icon_url_base += "help-.png"; break;
            }

            var latlng = {lat: parseFloat(response.data[0].PlaceLatitude), lng: parseFloat(response.data[0].PlaceLongtitude)};
            var title = '<div>' + response.data[0].PlaceName + '<br />' + response.data[0].PlaceExplain + '<br />';
            title += '<button class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored" ng-click="showClickPlaceInfo($event, ' + response.data[0].PlaceIdx +')">詳細を見る</button></div>';

            specific_plan_marker = new google.maps.Marker({
              position: latlng,
              map: map,
              title: title,
              icon: icon_url_base
            });

            map.setCenter(latlng);
            map.setZoom(14);
            var temp_complied = $compile(specific_plan_marker.title)(scope);
            infowindow.setContent(temp_complied[0]);
            infowindow.open(map, specific_plan_marker);

            specific_plan_marker.addListener('click', function() {
              var temp_complied = $compile(this.title)(scope);
              infowindow.setContent(temp_complied[0]);
              infowindow.open(map, this);
            });
          });
        }
      });

      // 특정 장소의 자세히 보기 버튼을 눌렀을 때
      scope.showClickPlaceInfo = function(ev, place_idx) {
        console.log(place_idx);
        var useFullScreen = ($mdMedia('sm') || $mdMedia('xs'))  && scope.customFullscreen;
        $mdDialog.show({
          controller: detailPlaceInfoController,
          templateUrl: 'detail_place_info.html',
          parent: angular.element(document.body),
          targetEvent: ev,
          clickOutsideToClose:true,
          fullscreen: useFullScreen,
          locals: {},
          onComplete: function(scope, element, options) {
            var url = "http://167.88.115.33/Plan/getDetailPlaceInfo";
            var data = {'place_idx': place_idx};

            $http.post(url, data).then(function(response) {
              console.log(response.data);
              scope.detailPlaceInfo = response.data.placeInfo[0];
              scope.detailPlaceImages = response.data.placeImagesInfo;
            });
          }
        })
        .then(function(answer) {
          scope.status = 'You said the information was "' + answer + '".';
        }, function() {
          scope.status = 'You cancelled the dialog.';
        });
        scope.$watch(function() {
          return $mdMedia('xs') || $mdMedia('sm');
        }, function(wantsFullScreen) {
          scope.customFullscreen = (wantsFullScreen === true);
        });
      };

      // 특정 장소의 기준점으로 설정 버튼을 눌렀을 때
      scope.setUpStandardPoint = function(marker_iCount) {
        if ( Object.keys(standard_point).length !== 0) {
          directionsDisplay.setMap(null);
          markers[standard_point_iCount].setAnimation(null);
          map.controls[google.maps.ControlPosition.BOTTOM_CENTER].removeAt(0);
          map.controls[google.maps.ControlPosition.RIGHT_CENTER].removeAt(1);
          map.controls[google.maps.ControlPosition.RIGHT_CENTER].removeAt(0);

          scope.transport = google.maps.TravelMode.DRIVING;
          scope.transport_distance = 0;
          scope.transport_duration = 0;
        }

        markers[marker_iCount].setAnimation(google.maps.Animation.BOUNCE);

        standard_point = markers[marker_iCount];
        standard_point_iCount = marker_iCount;
        scope.standard_point_name = '現在、基準旅先は ' + markers[marker_iCount].name + 'です.';
        console.log(markers[marker_iCount]);

        // 기준점으로 설정된 장소를 BOTTOM_CENTER에 달아준다.
        var standard_point_on_control_div = document.createElement('div');
        var standard_point_on_control_obj = new standard_point_on_control(standard_point_on_control_div, map);
        standard_point_on_control_obj.index = 1;
        map.controls[google.maps.ControlPosition.BOTTOM_CENTER].push(standard_point_on_control_div);

        // 이동 수단 선택 버튼을 RIGHT_CENTER에 달아준다.
        var standard_point_transport_control_div = document.createElement('div');
        var standard_point_transport_control_obj = new standard_point_transport_control(standard_point_transport_control_div, map);
        standard_point_transport_control_obj.index = 1;
        map.controls[google.maps.ControlPosition.RIGHT_CENTER].push(standard_point_transport_control_div);

        // 시작점부터 목적지까지의 거리와 시간을 RIGHT_CENTER에 달아준다.
        var standard_point_transport_info_control_div = document.createElement('div');
        var standard_point_transport_info_control_obj = new standard_point_transport_info_control(standard_point_transport_info_control_div, map);
        standard_point_transport_info_control_obj.index = 1;
        map.controls[google.maps.ControlPosition.RIGHT_CENTER].push(standard_point_transport_info_control_div);
      };

      // 공유 된 장소 중에 특정 장소 클릭
      scope.$watch('click_place_share', function(current, old) {
        //if (markers) { for (i in markers) { markers[i].setMap(null); } markers = []; }
        if (specific_plan_marker) { specific_plan_marker.setMap(null); specific_plan_marker = ''; }
        if (plan_markers) { for (i in plan_markers) { plan_markers[i].setMap(null); } }
        directionsDisplay.setMap(null);

        if (share_marker) { share_marker.setMap(null); share_marker = ''; }

        if (current) {
          var icon_url_base = "http://167.88.115.33/images/place_category/";

          switch(current[0].CategoryPlaceIdx) {
            case '1': icon_url_base += "time_to_leave-.png"; break;
            case '2': icon_url_base += "restaurant-.png"; break;
            case '3': icon_url_base += "hotel-.png"; break;
            case '4': icon_url_base += "local_grocery_store-.png"; break;
            case '5': icon_url_base += "account_balance-.png"; break;
            case '6': icon_url_base += "help-.png"; break;
          }

          var latlng = {lat: parseFloat(current[0].PlaceLatitude), lng: parseFloat(current[0].PlaceLongtitude)};
          var title = '<div>' + current[0].PlaceName + '<br />' + current[0].PlaceExplain + '<br />';
          title += '<button class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored" ng-click="showClickPlaceInfo($event, ' + current[0].PlaceIdx +')">詳細を見る</button></div>';

          share_marker = new google.maps.Marker({
            position: latlng,
            map: map,
            title: title,
            icon: icon_url_base
          });

          map.setZoom(14);
          map.setCenter(latlng);

          var temp_complied = $compile(share_marker.title)(scope);
          infowindow.setContent(temp_complied[0]);
          infowindow.open(map, share_marker);

          share_marker.addListener('click', function() {
            var temp_complied = $compile(this.title)(scope);
            infowindow.setContent(temp_complied[0]);
            infowindow.open(map, this);
          });
        }
      });

      // 특정 일자 클릭
      scope.$watch('specific_date_click', function(current, old) {
        //if (markers) { for (i in markers) { markers[i].setMap(null); } markers = []; }
        if (specific_plan_marker) { specific_plan_marker.setMap(null); specific_plan_marker = ''; }
        if (share_marker) { share_marker.setMap(null); share_marker = ''; }
        directionsDisplay.setMap(null);

        if (plan_markers) { for (i in plan_markers) { plan_markers[i].setMap(null); } }

        if (current) {
          waypts = [];
          var origin = '';
          var destination = '';
          var icon_url_base = "http://167.88.115.33/images/place_number/";

          var cnt = current.length;
          for (var iCount = 0; iCount < cnt; iCount++) {
            var icon_url_base = "http://167.88.115.33/images/place_number/";
            var icon_number = iCount + 1;
            icon_url_base = icon_url_base + icon_number + ".png";
            var latlng = {lat: parseFloat(current[iCount].PlaceLatitude), lng: parseFloat(current[iCount].PlaceLongtitude)};

            plan_markers[iCount] = new google.maps.Marker({
              position: latlng,
              title: current[iCount].PlaceName + '<br/>' + current[iCount].PlaceExplain,
              map: map,
              icon: icon_url_base
            });

            plan_markers[iCount].addListener('click', function() {
              infowindow.setContent(this.title);
              infowindow.open(map, this);
            });

            if (iCount === 0) {
              origin = latlng;
            } else if (iCount === (cnt-1) && iCount !== 0) {
              destination = latlng;
            } else {
              waypts.push({
                location: latlng,
                stopover: true
              })
            }

            if (cnt === 1) {
              destination = latlng;
            }
          }

          console.log(origin);
          console.log(destination);
          console.log(waypts);

          directionsDisplay.setMap(map);
          directionsService.route({
            origin: origin,
            destination: destination,
            waypoints: waypts,
            travelMode: google.maps.TravelMode.WALKING
          }, function(response, status) {
            if (status === google.maps.DirectionsStatus.OK) {
              directionsDisplay.setDirections(response);
              var route = response.routes[0];
              console.log(route.legs[0].distance.text + '/' + route.legs[0].duration.text);
              console.log(response.routes);
            } else {
              alert(status);
            }
          });
        }
      });

      var mapOptions = {
        center: new google.maps.LatLng(cordi[0], cordi[1]),
        zoom: zoom,
        scrollwheel: false,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        mapTypeControl: false
      };

      $timeout(function() {
        google.maps.event.trigger(map, "resize");
      }, 500);

      map.setOptions(mapOptions);

      // 리더 모드 버튼을 TOP_RIGHT에 달아준다.
      var centerControlDiv = document.createElement('div');
      var centerControl = new mapControl(centerControlDiv, map);

      centerControl.index = 1;
      map.controls[google.maps.ControlPosition.TOP_RIGHT].push(centerControlDiv);

      // 맵의 크기를 크게 해주는 버튼을 RIGHT_TOP에 달아준다.
      var make_map_size_big_control_div = document.createElement('div');
      var make_map_size_big_control_obj = new make_map_size_big_control(make_map_size_big_control_div, map);

      make_map_size_big_control_obj.index = 1;
      map.controls[google.maps.ControlPosition.RIGHT_TOP].push(make_map_size_big_control_div);

      // 스토리 채팅창을 TOP_LEFT에 달아준다.
      var story_chatting_control_div = document.createElement('div');
      var story_chatting_control_obj = new story_chatting_control(story_chatting_control_div, map);

      story_chatting_control_obj.index = 1;
      map.controls[google.maps.ControlPosition.TOP_LEFT].push(story_chatting_control_div);
    }
  }
}]);

plan.directive('additionalPlaceMap', ['$timeout', '$http', function($timeout, $http) {
  return {
    restrict: 'EA',
    scope: false,
    link: function(scope, iElement, iAttrs) {
      var el = document.createElement("div");
      el.style.width = "100%";
      el.style.height = "60%";
      iElement.prepend(el);

      var cordi = (iAttrs.center !== undefined) ? JSON.parse(iAttrs.center) : [37.561192, 127.030487];
      var zoom = (iAttrs.zoom !== undefined) ? Number(iAttrs.zoom) : 8;

      var map = new google.maps.Map(el, {});
      var geocoder = new google.maps.Geocoder();
      var marker;

      var mapOptions = {
        center: new google.maps.LatLng(cordi[0], cordi[1]),
        zoom: zoom,
        mapTypeId: google.maps.MapTypeId.ROADMAP
      };

      // 지도 클릭 이벤트
      google.maps.event.addListener(map, 'click', function (event) {
        placeMarker(event.latLng);
      });

      // 마커 제어
      var placeMarker = function(location) {
        if (marker) {
          // 만약, 마커가 있다면 해당 마커 위치 수정
          marker.setPosition(location);
        } else {
          // 마커가 없다면 새로운 마커 생성
          marker = new google.maps.Marker({
            position: location,
            map: map,
            draggable: true
          });
        }

        geocodeLatLng(geocoder, location);
      };

      // 역지오코딩
      var geocodeLatLng = function(geocoder, argLatLng) {
        var latlng = {lat: parseFloat(argLatLng.lat()), lng: parseFloat(argLatLng.lng())};
        geocoder.geocode({'location': latlng}, function(results, status) {
          if (status == google.maps.GeocoderStatus.OK) {
            console.log(results);
            // 구글 맵 API로부터 응답이 성공
            var whole_address_cnt = results.length;

            scope.place.address = results[1].formatted_address;
            $('#place_add_search').addClass('md-input-focused');

            var current_address = results[whole_address_cnt-2].address_components;
            scope.place.latitude = latlng.lat;
            scope.place.longtitude = latlng.lng;

            scope.place.nation = current_address[1].long_name;
            scope.place.region = current_address[0].long_name;
          } else {
            // 구글 맵 API로부터 응답이 실패
            alert('Geocoder failed due to : ' + status);
          }
        });
      };

      $timeout(function() {
        google.maps.event.trigger(map, "resize");
      }, 500);

      map.setOptions(mapOptions);

      scope.$watch('place_add_search_info', function(current, old) {
        console.log(current);
        if (current) {
          if (marker) {
            // 만약, 마커가 있다면 해당 마커 위치 수정
            geocoder.geocode({'address': current}, function(results, status) {
              if (status === google.maps.GeocoderStatus.OK) {
                var whole_address_cnt = results.length;

                scope.place.address = current;
                $('#place_add_search').addClass('md-input-focused');

                scope.place.latitude = results[0].geometry.location.lat();
                scope.place.longtitude = results[0].geometry.location.lng();

                var latlng = {lat: parseFloat(scope.place.latitude), lng: parseFloat(scope.place.longtitude)};
                geocoder.geocode({'location': latlng}, function(results, status) {
                  if (status == google.maps.GeocoderStatus.OK) {
                    console.log(results);
                    // 구글 맵 API로부터 응답이 성공
                    var whole_address_cnt = results.length;

                    var current_address = results[whole_address_cnt-2].address_components;

                    scope.place.nation = current_address[1].long_name;
                    scope.place.region = current_address[0].long_name;

                    map.setCenter(latlng);
                    marker.setPosition(latlng);
                    console.log(scope.place);
                  } else {
                    alert('Geocode was not successful for the following reason: ' + status);
                  }
                });
              } else {
                alert('Geocode was not successful for the following reason: ' + status);
              }
            });
          } else {
            // 마커가 없다면 새로운 마커 생성
            geocoder.geocode({'address': current}, function(results, status) {
              if (status === google.maps.GeocoderStatus.OK) {
                var whole_address_cnt = results.length;

                scope.place.address = current;
                $('#place_add_search').addClass('md-input-focused');

                scope.place.latitude = results[0].geometry.location.lat();
                scope.place.longtitude = results[0].geometry.location.lng();

                var latlng = {lat: parseFloat(scope.place.latitude), lng: parseFloat(scope.place.longtitude)};
                geocoder.geocode({'location': latlng}, function(results, status) {
                  if (status == google.maps.GeocoderStatus.OK) {
                    // 구글 맵 API로부터 응답이 성공
                    var whole_address_cnt = results.length;

                    var current_address = results[whole_address_cnt-2].address_components;

                    scope.place.nation = current_address[1].long_name;
                    scope.place.region = current_address[0].long_name;

                    map.setCenter(latlng);
                    marker = new google.maps.Marker({
                      map: map,
                      position: latlng,
                      draggable: true
                    });
                    marker.setPosition(latlng);
                    console.log(scope.place);
                  } else {
                    alert('Geocode was not successful for the following reason: ' + status);
                  }
                });
              } else {
                alert('Geocode was not successful for the following reason: ' + status);
              }
            });
          }
        }
      });
    }
  }
}]);

plan.directive('scrollToTopWhen', function($timeout) {
  return {
    link: function(scope, element, attrs) {
      scope.$on(attrs.scrollToTopWhen, function() {
        $timeout(function() {
          angular.element(element)[0].scrollTop = 0;
        });
      });
    }
  }
});
