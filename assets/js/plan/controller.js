plan.controller('planController', ['$scope', '$window', '$http', '$compile', '$mdMedia', '$mdDialog', '$mdToast', '$stateParams', 'socket', 'uiCalendarConfig', 'Upload', function( $scope, $window, $http, $compile, $mdMedia, $mdDialog, $mdToast, $stateParams, socket, uiCalendarConfig, Upload ) {
  // 페이지 이동효과, 페이지 이름, 페이지 수
  $scope.effect = 'slideright';
  $scope.pageClass = 'plan';
  $scope.page = 1;

  $scope.sizes = [ "region", "place" ];
  $scope.search = {
    region: '',
    place: ''
  };

  $scope.is_click_share_place_remove = false;

  // 맵 크기 제어 변수
  $scope.is_map_wide = false;
  $scope.myStyle = {'height': '400px', 'margin': '0px', 'padding': '0px'};

  $scope.click_place = '';
  $scope.click_place_plan = '';
  $scope.click_place_share = '';
  $scope.is_click_remove_event_button = false;

  $scope.place_add_search_info = '';
  $scope.specific_date_click = '';
  $scope.recommend_data = [];

  /* 채팅 */
  $scope.connector = [];  // 현재 접속중인 사람
  $scope.messagise = [];  // 채팅 메세지

  $scope.isDialog = false;

  // 보낸 메세지
  $scope.send = {
    story_num: '',
    id: '',
    message: ''
  };

  // 여행 계획에 들어왔을 때
  $scope.$on('$viewContentLoaded', function() {
    var plan_enter_info = {
      story_num: $stateParams.story_idx,
      nickname: login_user_nickname
    };

    socket.emit('enter:plan', plan_enter_info);
  });

  var plan_start_date_check = false;
  $http({
    method: 'post',
    url: '/Plan/getStory',
    data: {'story_idx': $stateParams.story_idx},
    headers: {'Content-Type': 'application/json; charset=utf8'}
  })
  .success(function(data, status, headers, confing) {
    $scope.story_name = data[0].StoryName;
    $scope.myDate = new Date(data[0].StoryStartDate);
    $scope.uiConfig.calendar.now = moment($scope.myDate).format("YYYY-MM-DD");
    plan_start_date_check = true;
  });

  $http({
    method: 'post',
    url: '/Plan/getStoryPlace',
    data: {'story_idx': $stateParams.story_idx},
    headers: {'Content-Type': 'application/json; charset=utf8'}
  })
  .success(function(data, status, headers, confing) {
    var event_color = '';
    var cnt = data.length;
    for (var iCount = 0; iCount < cnt; iCount++) {
      switch (data[iCount].CategoryPlaceIdx) {
        case '1': event_color = '#31419E'; break;
        case '2': event_color = '#FF5724'; break;
        case '3': event_color = '#00786A'; break;
        case '4': event_color = '#D13030'; break;
        case '5': event_color = '#455A63'; break;
        case '6': event_color = '#4F2DA6'; break;
      }

      $scope.eventSources.push({
        events: [{
          title: data[iCount].PlaceName,
          start: data[iCount].StoryPlaceStartTime,
          end: data[iCount].StoryPlaceEndTime,
          drop_place_idx: data[iCount].PlaceIdx,
          drop_storyplace_idx: data[iCount].StoryPlaceIdx,
          drop_category_idx: data[iCount].CategoryPlaceIdx
        }],
        color: event_color
      });
    }
  })
  .error(function(data, status, headers, config) {});

  $http({
    method: 'post',
    url: '/Plan/getStoryWholeBookmarkPlace',
    data: {'story_idx': $stateParams.story_idx},
    headers: {'Content-Type': 'application/json; charset=utf8'}
  })
  .success(function(data, status, headers, confing) {
    console.log(data);
    var bookmark_place_cnt = data.length;
    for (var iCount = 0; iCount < bookmark_place_cnt; iCount++) {
      var temp_bookmark_place = {
        'PlaceIdx': data[iCount].PlaceIdx,
        'PlaceName': data[iCount].PlaceName
      }
      $scope.recommend_data.push(temp_bookmark_place);
    }
    console.log($scope.recommend_data);
  })
  .error(function(data, status, headers, config) {});

  // 여행 계획에 들어온 사람의 정보를 받는다.
  socket.on('enter:plan', function(data) {
    $scope.connector.push(data.nickname);
    console.log($scope.connector);

    var new_connect = "[ " + data.nickname + " ] " + "さんが入場しました。";
    $scope.messagise.push(new_connect);
  });

  // 메세지를 입력하고, Enter를 누르면 메세지를 보냄
  $scope.keyPress = function(keyEvent) {
      if (keyEvent.which === 13) {
          $scope.to_send_message();
      }
  };

  // 특정 메세지를 날렸을 때
  $scope.to_send_message = function() {
    $scope.send = {
        story_num: $stateParams.story_idx,
        nickname: login_user_nickname,
        message: $scope.send_message
    };

    $scope.send_message = '';
    socket.emit('send:message', $scope.send);
  };

  // 특정 메세지를 받았을 때
  socket.on('receive:message', function(data) {
    var receive_message = "[ " + data.nickname + " ] : " + data.message;
    $scope.messagise.push(receive_message);
  });

  /* 채팅 끝 */

  // 여행 시작일이 바뀌면 감시
  $scope.$watch('myDate', function(current, old) {
    console.log($scope.myDate);
    console.log($scope.uiConfig.calendar.now);
    console.log($scope.uiConfig.calendar.now != moment($scope.myDate).format('YYYY-MM-DD'));
    if ($scope.myDate && $scope.uiConfig.calendar.now && $scope.uiConfig.calendar.now != moment($scope.myDate).format('YYYY-MM-DD')) {
      $scope.uiConfig.calendar.now = moment($scope.myDate).format('YYYY-MM-DD');

      var story_start_date = moment(current).format('YYYY-MM-DD');
      var story_place_date_gap = Math.round((current.getTime() - old.getTime()) / 1000 / 60 / 60 / 24);

      $http({
        method: 'post',
        url: '/Plan/setPlanStartDate',
        data: {'story_idx': $stateParams.story_idx, 'story_start_date': story_start_date, 'story_place_date_gap': story_place_date_gap},
        headers: {'Content-Type': 'application/json; charset=utf-8'}
      }).success(function(data, status, headers, config) {
        $scope.eventSources.splice(0);

        var event_color = '';
        var cnt = data.length;
        for (var iCount = 0; iCount < cnt; iCount++) {
          switch (data[iCount].CategoryPlaceIdx) {
            case '1': event_color = '#31419E'; break;
            case '2': event_color = '#FF5724'; break;
            case '3': event_color = '#00786A'; break;
            case '4': event_color = '#D13030'; break;
            case '5': event_color = '#455A63'; break;
            case '6': event_color = '#4F2DA6'; break;
          }

          $scope.eventSources.push({
            events: [{
              title: data[iCount].PlaceName,
              start: data[iCount].StoryPlaceStartTime,
              end: data[iCount].StoryPlaceEndTime,
              drop_place_idx: data[iCount].PlaceIdx,
              drop_storyplace_idx: data[iCount].StoryPlaceIdx,
              drop_category_idx: data[iCount].CategoryPlaceIdx
            }],
            color: event_color
          });
        }

        var data = {
          'story_idx': $stateParams.story_idx,
          'myDate': $scope.myDate
        };

        $mdToast.show(
          $mdToast.simple()
          .textContent('旅行開始日が ' + story_start_date + 'に修正されました！')
          .position('right')
          .hideDelay(5000)
        );

        socket.emit('reset:plan', data);
      });
    }
  });

  // 여행장이 여행 시작일을 바꾼 것을 캐치했을 때
  socket.on('reset:plan', function( data ) {
    $scope.myDate = new Date(data.myDate);
    $scope.uiConfig.calendar.now = moment($scope.myDate).format("YYYY-MM-DD");
    plan_start_date_check = true;

    $http({
      method: 'post',
      url: '/Plan/getStoryPlace',
      data: {'story_idx': $stateParams.story_idx},
      headers: {'Content-Type': 'application/json; charset=utf-8'}
    }).success(function(data, status, headers, config) {
      $scope.eventSources.splice(0);

      var event_color = '';
      var cnt = data.length;
      for (var iCount = 0; iCount < cnt; iCount++) {
        switch (data[iCount].CategoryPlaceIdx) {
          case '1': event_color = '#31419E'; break;
          case '2': event_color = '#FF5724'; break;
          case '3': event_color = '#00786A'; break;
          case '4': event_color = '#D13030'; break;
          case '5': event_color = '#455A63'; break;
          case '6': event_color = '#4F2DA6'; break;
        }

        $scope.eventSources.push({
          events: [{
            title: data[iCount].PlaceName,
            start: data[iCount].StoryPlaceStartTime,
            end: data[iCount].StoryPlaceEndTime,
            drop_place_idx: data[iCount].PlaceIdx,
            drop_storyplace_idx: data[iCount].StoryPlaceIdx,
            drop_category_idx: data[iCount].CategoryPlaceIdx
          }],
          color: event_color
        });
      }
    });

    $mdToast.show(
      $mdToast.simple()
      .textContent('旅行開始日が ' + moment($scope.myDate).format("YYYY-MM-DD") + 'に修正されました！')
      .position('right')
      .hideDelay(5000)
    );
  });

  // 장소 카테고리 명과 아이콘이 담긴 배열 객체
  $scope.placeCategories = [
      {title: '', icon: 'language'},
      {title: 'traffic', icon: 'time_to_leave'},
      {title: 'food', icon: 'restaurant'},
      {title: 'hotel', icon: 'hotel'},
      {title: 'shopping', icon: 'local_grocery_store'},
      {title: 'attraction', icon: 'account_balance'},
      {title: 'etc', icon: 'check'}
  ];

  $scope.placeCategoryIndex = 0;

  // 장소 추가 모달창
  $scope.showAdvanced = function(ev) {
    var useFullScreen = ($mdMedia('sm') || $mdMedia('xs'))  && $scope.customFullscreen;
    $mdDialog.show({
      controller: additionalPlaceController,
      templateUrl: 'additional_place.html',
      parent: angular.element(document.body),
      targetEvent: ev,
      clickOutsideToClose:true,
      fullscreen: useFullScreen
    })
    .then(function(answer) {
      $scope.status = 'You said the information was "' + answer + '".';
    }, function() {
      $scope.status = 'You cancelled the dialog.';
    });
    $scope.$watch(function() {
      return $mdMedia('xs') || $mdMedia('sm');
    }, function(wantsFullScreen) {
      $scope.customFullscreen = (wantsFullScreen === true);
    });
  };

  // 준비물 추가 모달창
  var add_material_window = false;
  $scope.showMaterial = function(ev) {
    var useFullScreen = ($mdMedia('sm') || $mdMedia('xs'))  && $scope.customFullscreen;
    $mdDialog.show({
      controller: additionalMaterialController,
      templateUrl: 'additional_material.html',
      parent: angular.element(document.body),
      targetEvent: ev,
      clickOutsideToClose:true,
      fullscreen: useFullScreen,
      locals: {'story_idx': $stateParams.story_idx},
      onComplete: function(scope, element, options) {
        $http({
            method: 'post',
            url: '/Plan/getMyMaterial',
            data: {'story_idx': $stateParams.story_idx, 'member_idx': login_user_idx},
            headers: {'Content-Type': 'application/json; charset=utf-8'}
        }).success(function(data, status, headers, config) {
            console.log(data);
            var current_my_material = data;
            var current_my_material_cnt = current_my_material.length;

            scope.selectedFoods = [];
            scope.selectedAccessories = [];
            scope.selectedDrugs = [];
            scope.selectedEtcs = [];
            scope.selectedRecommendes = [];
            scope.add_material_window = false;

            for (var iCount = 0; iCount < current_my_material_cnt; iCount++) {
              switch( current_my_material[iCount].MaterialCategoryIdx ) {
                case '-1':
                  if (current_my_material[iCount].StoryIdx === $stateParams.story_idx) {
                    scope.recommendes.push(current_my_material[iCount].MaterialName);
                    scope.selectedRecommendes.push(current_my_material[iCount].MaterialName);
                    scope.material.push(current_my_material[iCount].MaterialName);
                  } else {
                    scope.recommendes.push(current_my_material[iCount].MaterialName);
                  }
                  break;
                case '0':
                  scope.material.push(current_my_material[iCount].MaterialName);
                  break;
                case '1':
                  scope.selectedFoods.push(current_my_material[iCount].MaterialName);
                  scope.material.push(current_my_material[iCount].MaterialName);
                  break;
                case '2':
                  scope.selectedAccessories.push(current_my_material[iCount].MaterialName);
                  scope.material.push(current_my_material[iCount].MaterialName);
                  break;
                case '3':
                  scope.selectedDrugs.push(current_my_material[iCount].MaterialName);
                  scope.material.push(current_my_material[iCount].MaterialName);
                  break;
                case '4':
                  scope.selectedEtcs.push(current_my_material[iCount].MaterialName);
                  scope.material.push(current_my_material[iCount].MaterialName);
                  break;
              }
            }

            console.log(scope.recommendes);
            scope.add_material_window = true;
        });
      }
    }).then(function(answer) {
      console.log('ccc');
    }, function() {
      // 모달창이 꺼진 후에 실행
    });

    $scope.$watch(function() {
      return $mdMedia('xs') || $mdMedia('sm');
    }, function(wantsFullScreen) {
      $scope.customFullscreen = (wantsFullScreen === true);
      // 모달창이 켜진 후에 실행
    });
  };

  // 동행자 추가 모달창
  $scope.showCompanion = function(ev, item) {
    var useFullScreen = ($mdMedia('sm') || $mdMedia('xs'))  && $scope.customFullscreen;
    $scope.companionDialog = $mdDialog;
    $scope.companionDialog.show({
      controller: additionalCompanionController,
      templateUrl: 'additional_companion.html',
      parent: angular.element(document.body),
      targetEvent: ev,
      clickOutsideToClose:true,
      fullscreen: useFullScreen,
      locals: {'story_idx': $stateParams.story_idx, 'parent_scope': $scope, 'item': item},
      onComplete: function(scope, element, options) {
        $http({
            method: 'post',
            url: '/Plan/getStoryCompanion',
            data: {'story_idx': $stateParams.story_idx},
            headers: {'Content-Type': 'application/json; charset=utf-8'}
        }).success(function(data, status, headers, config) {
            scope.companions = data;
            scope.companion_photo_value = 2;
        });
      }
    })
    .then(function(answer) {
      $scope.status = 'You said the information was "' + answer + '".';
    }, function() {
      $scope.status = 'You cancelled the dialog.';
    });
    $scope.$watch(function() {
      return $mdMedia('xs') || $mdMedia('sm');
    }, function(wantsFullScreen) {
      $scope.customFullscreen = (wantsFullScreen === true);
    });
  };

  // 여행 요약 페이지로 이동
  $scope.showSummary = function() {
    $window.location.href = '/plan/StoryStart/' + $stateParams.story_idx;
    console.log('dd');
  }

  // 장소 검색
  $scope.nation_identifier = '';
  $scope.region_identifier = '';
  $scope.to_place_search = function() {
    var current_place_region = $scope.search.region;
    var current_place_name = $scope.search.place;
    var current_place_category = $scope.placeCategories[$scope.placeCategoryIndex].title;

    if (current_place_region) {
      var nation_arr = [
        '대한민국', '일본', '몽골', '중국', '러시아', '베트남'
      ];

      var nation_arr_cnt = nation_arr.length;

      $scope.nation_identifier = '';
      $scope.region_identifier = '';

      for (var iCount = 0; iCount < nation_arr_cnt; iCount++) {
        if (nation_arr[iCount].indexOf(current_place_region) != -1) {
          $scope.nation_identifier = nation_arr[iCount];
          break;
        }
      }
      $scope.region_identifier = current_place_region;
    }

    $http({
        method: 'post',
        url: '/Plan/toPlaceSearch',
        data: {'region': current_place_region, 'name': current_place_name, 'category': current_place_category},
        headers: {'Content-Type': 'application/json; charset=utf-8'}
    }).success(function(data, status, headers, config) {
        console.log(data);
        $scope.search_data = data;

        var data = {
          'story_idx': $stateParams.story_idx,
          'place_region': current_place_region,
          'place_name': current_place_name,
          'place_category': current_place_category
        };

        socket.emit('search:map', data);
    });
  };

  // 장소 검색 시, 필터
  $scope.place_search_filters = ['Review', 'Star'];
  $scope.place_search_filter_selected = [];
  $scope.toggle = function (item, list) {
    var idx = list.indexOf(item);

    if (idx > -1) {
      list.splice(idx, 1);
    } else {
      list.push(item);
    }

    console.log(list);
  };
  $scope.exists = function (item, list) {
    return list.indexOf(item) > -1;
  };

  // 특정 장소 클릭 시, 지도 이동
  $scope.showPlaceInfo = function(click_place_index) {
    $scope.click_place = click_place_index;
  };

  // 특정 장소를 공유하고자 드랍했다면
  $scope.dropCallback = function(event, ui) {
    var drop_place_idx = ui.helper.context.attributes[0].nodeValue;
    var drop_place_name = ui.helper.context.innerText;

    var drop_place_cnt = $scope.recommend_data.length;
    for (var iCount = 0; iCount < drop_place_cnt; iCount++) {
      if ($scope.recommend_data[iCount].PlaceIdx === drop_place_idx) {
        return;
      }
    }

    $http({
      method: 'post',
      url: '/Plan/addStoryBookmarkPlace',
      data: {'story_idx': $stateParams.story_idx, 'place_idx': drop_place_idx},
      headers: {'Content-Type': 'application/json; charset=utf-8'}
    }).success(function(data, status, headers, config) {
      var temp_drop_data = {
        "PlaceIdx": drop_place_idx,
        "PlaceName": drop_place_name
      };

      $scope.recommend_data.push(temp_drop_data);

      temp_drop_data.story_idx = $stateParams.story_idx;
      socket.emit('drop:place', temp_drop_data);
    });
  };

  // 북마크에 공유된 장소를 쓰레기통으로 드랍했다면
  $scope.delStoryPlaceCallback = function(event, ui) {
    console.log(ui);
    var drop_place_idx = ui.helper.context.attributes[0].nodeValue;
    var drop_place_info = ui.helper.context.innerText.split('\n');
    var drop_place_name = drop_place_info[0];

    if (confirm('ブックマックで ' + drop_place_name + 'を削除しますか？')) {
      var url = 'http://167.88.115.33/Plan/delStoryBookmarkPlace';
      var data = {'story_idx': $stateParams.story_idx, 'place_idx': drop_place_idx};

      $http.post(url, data).then(function(response) {
        $scope.recommend_data.splice(0);

        var cnt = response.data.length;
        for (var iCount = 0; iCount < cnt; iCount++) {
          $scope.recommend_data.push(response.data[iCount]);
        }

        data = {'story_idx': $stateParams.story_idx};
        socket.emit('remove:place', data);
      });
    }
  };

  // 공유된 장소를 받자
  socket.on('drop:place', function(data) {
    var temp_droopped_data = {
      "PlaceIdx": data.PlaceIdx,
      "PlaceName": data.PlaceName
    };
    $scope.recommend_data.push(temp_droopped_data);
    console.log(data);

    var temp_text = 'ブックマックに ' + data.PlaceName + 'が追加されました！';
    $mdToast.show(
      $mdToast.simple()
      .textContent(temp_text)
      .position('right')
      .hideDelay(5000)
    );
  });

  // 북마크에서 특정 장소가 삭제된 행동
  socket.on('remove:place', function(data) {
    var url = 'http://167.88.115.33/Plan/getStoryWholeBookmarkPlace';
    var data = {'story_idx': data.story_idx};

    $http.post(url, data).then(function(response) {
      $scope.recommend_data.splice(0);

      var cnt = response.data.length;
      for (var iCount = 0; iCount < cnt; iCount++) {
        $scope.recommend_data[iCount] = {
          "PlaceIdx": response.data[iCount].PlaceIdx,
          "PlaceName": response.data[iCount].PlaceName
        };
      }
    });
  });

  // 공유된 특정 장소 클릭 시, 지도 이동
  $scope.showPlaceInfo_share = function(share_place_idx) {
    if(!$scope.is_click_share_place_remove) {
      $http({
        method: 'post',
        url: '/Plan/getStorySharePlace',
        data: {'place_idx': share_place_idx},
        headers: {'Content-Type': 'application/json; charset=utf-8'}
      }).success(function(data, status, headers, config) {
        $scope.click_place_share = data;
      });
    }
    $scope.is_click_share_place_remove = false;
  };

  // 공유된 특정 장소 삭제 시
  $scope.removePlaceInfo_share = function(share_place_idx) {
    $scope.is_click_share_place_remove = true;
    console.log(this.data);

    var confirm = $mdDialog.confirm()
                    .title(this.data.PlaceName + 'をブックマックで削除しますか？')
                    .ok('いいえ')
                    .cancel('はい');
    var remove_place_idx = this.data.PlaceIdx;

    $mdDialog.show(confirm).then(function() {
      // 장소를 북마크에서 삭제하지 않을 때
    }, function() {
      // 장소를 북마크에서 삭제할 때
      console.log(this);
      var url = 'http://167.88.115.33/Plan/delStoryBookmarkPlace';
      var data = {'story_idx': $stateParams.story_idx, 'place_idx': remove_place_idx};

      $http.post(url, data).then(function(response) {
        $scope.recommend_data.splice(0);

        var cnt = response.data.length;
        for (var iCount = 0; iCount < cnt; iCount++) {
          $scope.recommend_data.push(response.data[iCount]);
        }

        data = {'story_idx': $stateParams.story_idx};
        socket.emit('remove:place', data);
      });
    });
  };

  //------------------------------ 캘린더 시작 ------------------------------//
  $scope.list1 = {title: 'Cyka'};
  $scope.events = [];
  $scope.uiConfig = {
    calendar: {
      height: 515,
      editable: true,
      droppable: true,
      eventOverlap: false,
      slotEventOverlap: false,
      timezone: 'UTC',
      allDaySlot: false,
      minTime: "00:00:00",
      slotDuration: '00:30:00',
      defaultTimedEventDuration: '00:30:00',
      handleWindowResize: true,
      now: '',
      defaultView: 'agendaFourDay',
      selectable: true,
      views: {
        agendaFourDay: {
          type: 'agenda',
          duration: {days: 4}
        }
      },
      viewRender: function(view) {
        var current_week_date = moment(view.calendar.getDate()._d).format('YYYY-MM-DD');

        if ($scope.uiConfig.calendar.now == current_week_date) {
          // 여행 시작 날짜와 현재 캘린더의 첫 날짜가 일치한다면 prev 버튼을 비활성화
          $(".fc-prev-button").prop('disabled', true);
          $(".fc-prev-button").addClass('fc-state-disabled');
        } else {
          // 여행 시작 날짜와 현재 캘린더의 첫 날짜가 일치하지 않는다면 prev 버튼을 활성화
          $(".fc-prev-button").removeClass('fc-state-disabled');
          $(".fc-prev-button").prop('disabled', false);
        }

        //$("#plan_calendar").find('.fc-toolbar > div > h2').empty();
        // console.log($("#plan_calendar").find('.fc-toolbar > div > h2').html());
        $("#plan_calendar").find(".fc-toolbar > .fc-left").empty();
        var date_picker = '<span style="font-weight: bold; font-size: 22px;">旅行開始日</span><md-datepicker ng-model="myDate" md-placeholder="Enter date"></md-datepicker>';
        var complied = $compile(date_picker)($scope);
        $("#plan_calendar").find(".fc-toolbar > .fc-left").append(complied);
        console.log($("#plan_calendar").find('.fc-toolbar > .fc-left'));

        $('.fc-day-header').click(function() {
          var temp = $(this).context.innerHTML.split(" ");
          temp[1] = '2016/' + temp[1];

          $http({
            method: 'post',
            url: '/Plan/getSpecificDateStoryPlace',
            data: {'story_idx': $stateParams.story_idx, 'story_date': moment(new Date(temp[1])).format('YYYY-MM-DD')},
            headers: {'Content-Type': 'application/json; charset=utf8'}
          })
          .success(function(data, status, headers, confing) {
            $scope.specific_date_click = data;
          })
          .error(function(data, status, headers, config) {});
        });
      },
      select: function(start, end, jsEvent, view) {
        var event_time_arr = [];
        var standard_time = new Date(moment(start._d).utc().format('YYYY-MM-DD HH:mm:ss')).getTime();

        console.log(standard_time);

        event_time_arr['standard_time'] = standard_time;
        for(var attr in $scope.eventSources) {
          event_time_arr[attr] = new Date($scope.eventSources[attr].events[0].start).getTime();
        }

        console.log(event_time_arr);

        event_time_arr.sort(function(a, b) {
          if (a == b) return 0;
          return (a > b) ? 1 : -1;
        });

        var standard_time_iCount = event_time_arr.indexOf(standard_time);
        var start_point_time = event_time_arr[standard_time_iCount - 1];
        var end_point_time = event_time_arr[standard_time_iCount + 1];

        console.log(event_time_arr);
      },
      eventMouseover: function( event, jsEvent, view ) {
        // console.log(event);
      },
      drop: function(date, jsEvent, ui, resourceId) {
        console.log(ui);
        var place_name = ui.helper.context.innerText;
        var start_time = date._d;
        var end_time = new Date(start_time.getTime());
        end_time = end_time.setHours(end_time.getHours() + 1);
        end_time = new Date(end_time);

        var cnt = $scope.eventSources.length;

        // 드래그 & 드랍 된 장소를 현재 스토리의 계획에 추가
        var drop_place_idx = ui.helper.context.attributes[0].nodeValue;
        var place_date = moment(date._d).utc().format('YYYY-MM-DD');

        $http({
          method: 'post',
          url: '/Plan/addStoryPlace',
          data: {'story_idx': $stateParams.story_idx, 'place_idx': drop_place_idx, 'start_time': start_time, 'end_time': end_time, 'place_date': place_date},
          headers: {'Content-Type': 'application/json; charset=utf8'}
        })
        .success(function(data, status, headers, confing) {
          var event_color = '';
          switch (data[0].CategoryPlaceIdx) {
            case '1': event_color = '#31419E'; break;
            case '2': event_color = '#FF5724'; break;
            case '3': event_color = '#00786A'; break;
            case '4': event_color = '#D13030'; break;
            case '5': event_color = '#455A63'; break;
            case '6': event_color = '#4F2DA6'; break;
          }

          $scope.eventSources.push({
            events: [{
              title: data[0].PlaceName,
              start: data[0].StoryPlaceStartTime,
              end: data[0].StoryPlaceEndTime,
              drop_place_idx: data[0].PlaceIdx,
              drop_storyplace_idx: data[0].StoryPlaceIdx,
              drop_category_idx: data[0].CategoryPlaceIdx
            }],
            color: event_color
          });

          var data = {
            'story_idx': $stateParams.story_idx
          };

          socket.emit('drag:plan', data);
        })
        .error(function(data, status, headers, config) {});
      },
      // 이벤트가 클릭되었을 때
      eventClick: function(calEvent, jsEvent, view) {
        if(!$scope.is_click_remove_event_button) {
          $scope.click_place_plan = calEvent.drop_place_idx;
        }
        $scope.is_click_remove_event_button = false;
      },
      // 드래그해서 드랍 되었을 때
      eventDrop: function(event, delta, revertFunc) {
        var place_date = moment(event._start._d).format('YYYY-MM-DD');
        console.log(event);

        // 시작시간과 끝시작을 업데이트 해준다.
        $http({
          method: 'post',
          url: '/Plan/setStoryPlace',
          data: {'story_idx': $stateParams.story_idx, 'place_idx': event.drop_place_idx, 'storyplace_idx': event.drop_storyplace_idx, 'start_time': event._start._d, 'end_time': event._end._d, 'place_date': place_date},
          headers: {'Content-Type': 'application/json; charset=utf8'}
        })
        .success(function(data, status, headers, confing) {
          var drag_plan_info = {
            'story_idx': $stateParams.story_idx,
            'event_id': event.source.__id,
            'start_time': event._start._d,
            'end_time': event._end._d
          };

          socket.emit('drag:plan', drag_plan_info);
        })
        .error(function(data, status, headers, config) {
          console.log(data);
        });
      },
      eventDragStop: function(event, jsEvent, ui, view) {
        // var cnt = $scope.eventSources.length;
        // for (var iCount = 0; iCount < cnt; iCount++) {
        //     if (event == $scope.eventSources[iCount]) {
        //       console.log('cc');
        //     }
        // }
        //
        // var trash_area = $('#calendarTrash');
        // var trash_area_ofs = trash_area.offset();
        //
        // // top과 left 변수가 들어있다.
        // // document 내에서의 좌표라고 할 수 있다.
        // var x1 = trash_area_ofs.left;
        // var x2 = trash_area_ofs.left + trash_area.outerWidth(true);
        // var y1 = trash_area_ofs.top;
        // var y2 = trash_area_ofs.top + trash_area.outerHeight(true);
        //
        // if ( jsEvent.clientX >= x1 && jsEvent.clientX <= x2 &&
        //     jsEvent.clientY >= y1 && jsEvent.clientY <= y2) {
        //   // 쓰레기통에 해당하는 좌표로 이벤트가 드래그 되었을 때
          // if (confirm('해당 장소를 계획에서 삭제하시겠습니까?')) {
          //   var url = 'http://167.88.115.33/Plan/delStoryPlace';
          //   var data = {'story_idx': $stateParams.story_idx, 'storyplace_idx': event.drop_storyplace_idx};
          //
          //   $http.post(url, data).then(function(response) {
          //     $scope.eventSources.splice(0);
          //
          //     var cnt = response.data.length;
          //     for (var iCount = 0; iCount < cnt; iCount++) {
          //       $scope.eventSources.push({
          //         events: [{
          //           title: response.data[iCount].PlaceName,
          //           start: response.data[iCount].StoryPlaceStartTime,
          //           end: response.data[iCount].StoryPlaceEndTime,
          //           drop_place_idx: response.data[iCount].PlaceIdx,
          //           drop_storyplace_idx: response.data[iCount].StoryPlaceIdx
          //         }]
          //       });
          //     }
          //
          //     data = {'story_idx': $stateParams.story_idx};
          //
          //     socket.emit('drag:plan', data);
          //   });
          // }
        // }
      },
      // 이벤트의 시간이 조정되었을 때
      eventResize: function(event, delta, revertFunc) {
        var place_date = moment(event._start._d).format('YYYY-MM-DD');

        // 시작시간과 끝시작을 업데이트 해준다.
        $http({
          method: 'post',
          url: '/Plan/setStoryPlace',
          data: {'story_idx': $stateParams.story_idx, 'place_idx': event.drop_place_idx, 'storyplace_idx': event.drop_storyplace_idx, 'start_time': event._start._d, 'end_time': event._end._d, 'place_date': place_date},
          headers: {'Content-Type': 'application/json; charset=utf8'}
        })
        .success(function(data, status, headers, confing) {
          var drag_plan_info = {
            'story_idx': $stateParams.story_idx,
            'event_id': event.source.__id,
            'start_time': event._start._d,
            'end_time': event._end._d
          };

          socket.emit('resize:plan', drag_plan_info);
        })
        .error(function(data, status, headers, config) {
          console.log(data);
        });
      },
      eventRender: function(event, element, view) {
        console.log('Cyka');
        element.find('.fc-event').css('font-size', '32px');
        element.find('.fc-content').css('color', 'white');
        element.find('.fc-resizer').css('color', 'white');
        element.find('.fc-time').css('background', 'black');

        var remove_event_button = document.createElement('i');
        remove_event_button.setAttribute('class', 'material-icons');
        remove_event_button.innerHTML = 'clear';

        remove_event_button.style.display = 'inline';
        remove_event_button.style.float = 'right';
        remove_event_button.style.fontSize = '14px';
        element.find('.fc-time').append(remove_event_button);

        remove_event_button.addEventListener('click', function() {
          $scope.is_click_remove_event_button = true;
          var confirm = $mdDialog.confirm()
                          .title(event.title + 'をカレンダーで削除しますか？')
                          .ok('いいえ')
                          .cancel('はい');

          $mdDialog.show(confirm).then(function() {
            // 장소를 계획에서 삭제하지 않을 때
          }, function() {
            // 장소를 계획에서 삭제할 때
            var url = 'http://167.88.115.33/Plan/delStoryPlace';
            var data = {'story_idx': $stateParams.story_idx, 'storyplace_idx': event.drop_storyplace_idx};

            $http.post(url, data).then(function(response) {
              $scope.eventSources.splice(0);

              var event_color = '';
              var cnt = response.data.length;
              for (var iCount = 0; iCount < cnt; iCount++) {
                switch (response.data[iCount].CategoryPlaceIdx) {
                  case '1': event_color = '#31419E'; break;
                  case '2': event_color = '#FF5724'; break;
                  case '3': event_color = '#00786A'; break;
                  case '4': event_color = '#D13030'; break;
                  case '5': event_color = '#455A63'; break;
                  case '6': event_color = '#4F2DA6'; break;
                }

                $scope.eventSources.push({
                  events: [{
                    title: response.data[iCount].PlaceName,
                    start: response.data[iCount].StoryPlaceStartTime,
                    end: response.data[iCount].StoryPlaceEndTime,
                    drop_place_idx: response.data[iCount].PlaceIdx,
                    drop_storyplace_idx: response.data[iCount].StoryPlaceIdx,
                    drop_category_idx: response.data[iCount].CategoryPlaceIdx
                  }],
                  color: event_color
                });
              }

              data = {'story_idx': $stateParams.story_idx};
              socket.emit('drag:plan', data);
            });
          });
        });

        // element.find('.fc-bg').css('background', '#31419E');
        // console.log(element.find('.fc-event .fc-bg'));

        // element.find('.fc-time').append('<i class="material-icons" style="font-size: 18px; text-align: right;">clear</i>');
        // element.find('.fc-event').css('background-color', 'red');
      }
    }
  };

  console.log($scope.uiConfig.calendar);
  $scope.eventSources = [];

  //------------------------------ 여행 계획 공유 이벤트 ------------------------------//

  // 장소를 플래너로 드래그 했을 때
  socket.on('drag:plan', function(data) {
    $scope.eventSources.splice(0);

    $http({
      method: 'post',
      url: '/Plan/getStoryPlace',
      data: {'story_idx': data.story_idx},
      headers: {'Content-Type': 'application/json; charset=utf8'}
    })
    .success(function(data, status, headers, confing) {
      var event_color = '';
      var cnt = data.length;
      for (var iCount = 0; iCount < cnt; iCount++) {
        switch (data[iCount].CategoryPlaceIdx) {
          case '1': event_color = '#31419E'; break;
          case '2': event_color = '#FF5724'; break;
          case '3': event_color = '#00786A'; break;
          case '4': event_color = '#D13030'; break;
          case '5': event_color = '#455A63'; break;
          case '6': event_color = '#4F2DA6'; break;
        }

        $scope.eventSources.push({
          events: [{
            title: data[iCount].PlaceName,
            start: data[iCount].StoryPlaceStartTime,
            end: data[iCount].StoryPlaceEndTime,
            drop_place_idx: data[iCount].PlaceIdx,
            drop_storyplace_idx: data[iCount].StoryPlaceIdx,
            drop_category_idx: data[iCount].CategoryPlaceIdx
          }],
          color: event_color
        });
      }
    })
    .error(function(data, status, headers, config) {});
  });

  // 시간이 줄어들거나 늘어났을 때
  socket.on('resize:plan', function(data) {
    $scope.eventSources.splice(0);

    $http({
      method: 'post',
      url: '/Plan/getStoryPlace',
      data: {'story_idx': $stateParams.story_idx},
      headers: {'Content-Type': 'application/json; charset=utf8'}
    })
    .success(function(data, status, headers, confing) {
      var event_color = '';
      var cnt = data.length;
      for (var iCount = 0; iCount < cnt; iCount++) {
        switch (data[iCount].CategoryPlaceIdx) {
          case '1': event_color = '#31419E'; break;
          case '2': event_color = '#FF5724'; break;
          case '3': event_color = '#00786A'; break;
          case '4': event_color = '#D13030'; break;
          case '5': event_color = '#455A63'; break;
          case '6': event_color = '#4F2DA6'; break;
        }

        $scope.eventSources.push({
          events: [{
            title: data[iCount].PlaceName,
            start: data[iCount].StoryPlaceStartTime,
            end: data[iCount].StoryPlaceEndTime,
            drop_place_idx: data[iCount].PlaceIdx,
            drop_storyplace_idx: data[iCount].StoryPlaceIdx,
            drop_category_idx: data[iCount].CategoryPlaceIdx
          }],
          color: event_color
        });
      }
    })
    .error(function(data, status, headers, config) {});
  });
  //------------------------------ 여행 계획 공유 이벤트 끝 ------------------------------//
  //------------------------------ 캘린더 끝 ------------------------------//
}]);

// 장소 추가 컨트롤러
function additionalPlaceController($scope, $mdDialog, $http, Upload) {
  $scope.additionalPlaceCategories = [
      {title: 'traffic', icon: 'time_to_leave'},
      {title: 'food', icon: 'restaurant'},
      {title: 'hotel', icon: 'hotel'},
      {title: 'shopping', icon: 'local_grocery_store'},
      {title: 'attraction', icon: 'account_balance'},
      {title: 'etc', icon: 'check'}
  ];

  $scope.additionalPlaceCategoryIndex = 0;

  $scope.place_add_search = function(keyEvent) {
      if (keyEvent.which === 13) {
          $scope.place_add_search_info = $scope.place.address;
      }
  };

  $scope.hide = function() {
    $mdDialog.hide();
  };

  $scope.cancel = function() {
    $mdDialog.cancel();
  };

  $scope.answer = function(answer) {
    $mdDialog.hide(answer);
  };

  $scope.$watch('files', function(current, old) {
    console.log(current);
  });

  $scope.place = {
    address: '',
    latitude: '',
    longtitude: '',
    nation: '',
    region: ''
  };

  $scope.toAddPlace = function() {
    console.log('Cyka');
      $scope.place.category = $scope.additionalPlaceCategories[$scope.additionalPlaceCategoryIndex].title;
      $scope.isImageFile($scope.files);
      console.log($scope.files);
  };

  $scope.isImageFile = function(files) {
    $scope.imageInfo = [];
    if ( files && files.length ) {
        for ( var iCount = 0; iCount < files.length; iCount++ ) {
            if ( files[iCount].$error ) {
                return false;
            }

            $scope.imageInfo[iCount] = files[iCount];
        }

        Upload.upload({
            method: 'post',
            url: '/Plan/addPlace',
            data: {'addPlaceInfo': $scope.place, placeImages: $scope.imageInfo},
            headers: {'Content-Type': 'application/json; charset=utf-8'}
        }).then(function( response ) {
            console.log(response);
            if (response.data.msg == 'already') {
              alert("既に登録した旅先の名前です。 新しい名前を入力してください！");
            } else {
              $mdDialog.cancel();
            }
        });
    }
  };
};

// 자세한 장소 정보 컨트롤러
function detailPlaceInfoController($scope, $mdDialog) {
  $scope.hide = function() {
    $mdDialog.hide();
  };

  $scope.cancel = function() {
    $mdDialog.cancel();
  };

  $scope.answer = function(answer) {
    $mdDialog.hide(answer);
  };

  $scope.detailPlaceInfo = {};
}

// 준비물 추가 컨트롤러
function additionalMaterialController($scope, $mdDialog, $http, story_idx) {
  $scope.hide = function() {
    $mdDialog.hide();
  };

  $scope.cancel = function() {
    $mdDialog.cancel();
  };

  $scope.answer = function(answer) {
    $mdDialog.hide(answer);
  };

  $scope.material = [];
  $scope.foods = ['キムチ', 'バラ肉', 'ミネラルウォーター', 'ラーメン'];
  $scope.accessories = ['スリッパ', '手袋', '登山靴', 'ジャケット', 'サングラス'];
  $scope.drugs = ['酔い止め薬', '頭痛薬', '消化剤'];
  $scope.etcs = ['自撮り棒', '傘', 'ノートブック', 'イヤホーン'];
  $scope.recommendes = [];

  $scope.searchTerm;
  $scope.clearSearchTerm = function() {
    $scope.searchTerm = '';
  };
  // The md-select directive eats keydown events for some quick select
  // logic. Since we have a search input here, we don't need that logic.
  // angular.element().find('input').on('keydown', function(ev) {
  //   ev.stopPropagation();
  // });

  var now_material_category = '';
  $scope.$watch('selectedFoods', function(current, old) {
    console.log(current);
    console.log(old);
    if ($scope.add_material_window && old !== undefined) {
      // 모달창에서 준비물을 추가하거나 제거할 때
      now_material_category = 'food';
      material_change(current, old, now_material_category);
      now_material_category = '';
    }
  });

  $scope.$watch('selectedAccessories', function(current, old) {
    if ($scope.add_material_window && old !== undefined) {
      console.log($scope.material);
      now_material_category = 'accessory';
      material_change(current, old, now_material_category);
      now_material_category = '';
    }
  });

  $scope.$watch('selectedDrugs', function(current, old) {
    if ($scope.add_material_window && old !== undefined) {
      now_material_category = 'drug';
      material_change(current, old, now_material_category);
      now_material_category = '';
    }
  });

  $scope.$watch('selectedEtcs', function(current, old) {
    if ($scope.add_material_window && old !== undefined) {
      now_material_category = 'etc';
      material_change(current, old, now_material_category);
      now_material_category = '';
    }
  });

  $scope.$watch('selectedRecommendes', function(current, old) {
    if ($scope.add_material_window && old !== undefined) {
      now_material_category = 'none';
      material_change(current, old, now_material_category);
      now_material_category = '';
    }
  });

  // 준비물을 입력했을 때
  $scope.add_material = function(add_chip) {
    // 준비물 카테고리에 있는 준비물을 입력했다면 해당 카테고리의 selected 배열에 넣어준다.
    if ($scope.foods.indexOf(add_chip) != -1) {
      now_material_category = 'food';
      $scope.selectedFoods.push($scope.foods[$scope.foods.indexOf(add_chip)]);
      add_material($scope.foods[$scope.foods.indexOf(add_chip)], now_material_category);
    } else if ($scope.accessories.indexOf(add_chip) != -1){
      now_material_category = 'accessory';
      $scope.selectedAccessories.push($scope.accessories[$scope.accessories.indexOf(add_chip)]);
      add_material($scope.accessories[$scope.accessories.indexOf(add_chip)], now_material_category);
    } else if ($scope.drugs.indexOf(add_chip) != -1) {
      now_material_category = 'drug';
      $scope.selectedDrugs.push($scope.drugs[$scope.drugs.indexOf(add_chip)]);
      add_material($scope.drugs[$scope.drugs.indexOf(add_chip)], now_material_category);
    } else if ($scope.etcs.indexOf(add_chip) != -1) {
      now_material_category = 'etc';
      $scope.selectedEtcs.push($scope.etcs[$scope.etcs.indexOf(add_chip)]);
      add_material($scope.etcs[$scope.etcs.indexOf(add_chip)], now_material_category);
    } else if ($scope.selectedRecommendes.indexOf(add_chip) != -1) {
      $scope.selectedRecommendes.push($scope.recommendes[$scope.recommendes.indexOf(add_chip)]);
      add_material($scope.recommendes[$scope.recommendes.indexOf(add_chip)], now_material_category);
    } else {
      now_material_category = 'none';
      add_material(add_chip, now_material_category);
    }
    now_material_category = '';
  }

  // 준비물을 삭제했을 때
  $scope.delete_material = function(delete_chip) {
    // 준비물 카테고리에 있는 준비물을 삭제했다면 해당 카테고리의 selected 배열에서 삭제한다.
    if ($scope.foods.indexOf(delete_chip) != -1) {
      $scope.selectedFoods.splice($scope.foods[$scope.foods.indexOf(delete_chip)], 1);
      delete_material($scope.foods[$scope.foods.indexOf(delete_chip)]);
    } else if ($scope.accessories.indexOf(delete_chip) != -1){
      $scope.selectedAccessories.splice($scope.accessories[$scope.accessories.indexOf(delete_chip)], 1);
      delete_material($scope.accessories[$scope.accessories.indexOf(delete_chip)]);
    } else if ($scope.drugs.indexOf(delete_chip) != -1) {
      $scope.selectedDrugs.splice($scope.drugs[$scope.drugs.indexOf(delete_chip)], 1);
      delete_material($scope.drugs[$scope.drugs.indexOf(delete_chip)]);
    } else if ($scope.etcs.indexOf(delete_chip) != -1) {
      $scope.selectedEtcs.splice($scope.etcs[$scope.etcs.indexOf(delete_chip)], 1);
      delete_material($scope.etcs[$scope.etcs.indexOf(delete_chip)]);
    } else if ($scope.recommendes.indexOf(delete_chip) != -1) {
      $scope.selectedRecommendes.splice($scope.recommendes[$scope.recommendes.indexOf(delete_chip)], 1);
      delete_material($scope.recommendes[$scope.recommendes.indexOf(delete_chip)]);
    } else {
      delete_material(delete_chip);
    }
  }

  // 준비물 추가
  var add_material = function(material_name, material_category) {
    console.log(material_category);
    $http({
      method: 'post',
      url: '/Plan/addMyMaterial',
      data: {'story_idx': story_idx, 'member_idx': login_user_idx, 'material_name': material_name, 'material_category': material_category},
      headers: {'Content-Type': 'application/json; charset=utf8'}
    })
    .success(function(data, status, headers, confing) { console.log(data); })
    .error(function(data, status, headers, config) { console.log(data); });
  }

  // 준비물 삭제
  var delete_material = function(material_name) {
    $http({
      method: 'post',
      url: '/Plan/deleteMyMaterial',
      data: {'story_idx': story_idx, 'member_idx': login_user_idx, 'material_name': material_name},
      headers: {'Content-Type': 'application/json; charset=utf8'}
    })
    .success(function(data, status, headers, config) { console.log(data); })
    .error(function(data, status, headers, config) {});
  }

  var material_change = function(current, old, category) {
    var current_cnt = current === undefined ? 0 : current.length;
    var old_cnt = old === undefined ? 0 : old.length;

    if ( current_cnt > old_cnt ) {
      // 준비물이 하나 추가된 상태
      $scope.material.push(current[current_cnt-1]);
      add_material(current[current_cnt-1], category);
    } else {
      // 준비물이 하나 제거된 상태
      for (var old_iCount = 0; old_iCount < old_cnt; old_iCount++) {
        if (current.indexOf(old[old_iCount]) == -1) {
          $scope.material.splice($scope.material.indexOf(old[old_iCount]), 1);
          var current_delete_material = old[old_iCount];
          delete_material(current_delete_material);
          break;
        }
      }
    }
  };
}

// 동행자 추가 컨트롤러
function additionalCompanionController($scope, $mdDialog, $http, companionSearch, story_idx, parent_scope, item) {
  $scope.userInfoDialog = parent_scope.showCompanion;

  $scope.hide = function() {
    $mdDialog.hide();
  };

  $scope.cancel = function() {
    $mdDialog.cancel();
  };

  $scope.answer = function(answer) {
    $mdDialog.hide(answer);
  };

  $scope.searchText = "";
  $scope.companion_search_info = [];
  $scope.selectedItem = [];
  $scope.isDisabled = false;
  $scope.noCache = false;

  // 특정 문자열을 입력한 후, 엔터를 친 행위
  $scope.selectedItemChange = function (item) {
    if (item) {
      // 문자열이 존재할 경우, 문자열과 일치하는 닉네임을 가진 회원 정보를 가져온다.
      var url = "http://167.88.115.33/Plan/getRealtimeCompanion_result";
      var data = {'typed_text': item};
      $http.post(url, data).then(function(response) {
        $scope.companion_search_info = response.data;
      });
    }
  };

  // 문자열을 하나입력할 때마다 정보를 가져오는 행위
  $scope.searchTextChange = function (typed_text) {
    return companionSearch.getCompanion(typed_text, story_idx);
  };

  // 검색된 회원의 정보를 보는 행위
  $scope.do_member_info_action = function(event, member_nickname) {
    var url = "http://167.88.115.33/Plan/getClickMemberInfo";
    var data = {'member_nickname': member_nickname};

    $http.post(url, data).then(function(response) {
      console.log(response.data[0]);

      var text = "メールアドレス : " + response.data[0].MemberEmail + " ";
      text += "ニックネーム : " +response.data[0].MemberNickname;

      var confirm = $mdDialog.confirm()
          .title('Member Info')
          .textContent(text)
          .ariaLabel('Secondary click demo')
          .ok('close');

          $mdDialog.show(confirm).then(function() {
            $scope.userInfoDialog(null, item);
          });
          // , function() {
          //   $scope.userInfoDialog(null, item);
          // });
    });
  };

  // 동행자 추가 버튼을 누른 행위
  $scope.do_companion_request_action = function(event, member_nickname) {
    var url = "http://167.88.115.33/Plan/addRequestCompanion";
    var data = {'story_idx': story_idx, 'member_nickname': member_nickname};

    $http.post(url, data).then(function(response) {
      $scope.companions = response.data;
      $scope.companion_search_info = [];
    });
  };
}
