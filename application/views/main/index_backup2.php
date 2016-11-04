<?php

defined('BASEPATH') OR exit('No direct script access allowed');
$member_idx = isset($_SESSION['MemberIdx']) ? $_SESSION['MemberIdx'] : null;
?>
<div class="android-content mdl-layout__content">
  <a name="top"></a>
  <!-- <div class="android-be-together-section mdl-typography--text-center"> -->
    <!-- <div class="logo-font android-slogan"></div>
    <div class="logo-font android-sub-slogan"></div>
    <div class="logo-font android-create-character">
      <a href=""></a>
    </div> -->

    <!-- <a href="#screens">
      <button class="android-fab mdl-button mdl-button--colored mdl-js-button mdl-button--fab mdl-js-ripple-effect">
        <i class="material-icons">expand_more</i>
      </button>
    </a> -->
  <!-- </div> -->
  <main class="mdl-layout__content">
    <section class="mdl-layout__tab-panel is-active">
        <div class="page-content">
          <ul class="rslides">
              <li><img src="/images/sample1.jpg" alt=""></li>
              <li><img src="/images/sample2.jpg" alt=""></li>
              <li><img src="/images/sample3.jpg" alt=""></li>
              <li><img src="/images/sample4.jpg" alt=""></li>
          </ul>

          <script>
              $(function() {
                  $(".rslides").responsiveSlides();
              });

              $(".rslides").responsiveSlides({
                  auto: true,             // Boolean: Animate automatically, true or false
                  speed: 500,            // Integer: Speed of the transition, in milliseconds
                  timeout: 4000,          // Integer: Time between slide transitions, in milliseconds
                  pager: false,           // Boolean: Show pager, true or false
                  nav: false,             // Boolean: Show navigation, true or false
                  random: false,          // Boolean: Randomize the order of the slides, true or false
                  pause: false,           // Boolean: Pause on hover, true or false
                  pauseControls: true,    // Boolean: Pause when hovering controls, true or false
                  prevText: "Previous",   // String: Text for the "previous" button
                  nextText: "Next",       // String: Text for the "next" buttonf
                  maxwidth: "",           // Integer: Max-width of the slideshow, in pixels
                  navContainer: "",       // Selector: Where controls should be appended to, default is after the 'ul'
                  manualControls: "",     // Selector: Declare custom pager navigation
                  namespace: "rslides",   // String: Change the default namespace used
                  before: function(){},   // Function: Before callback
                  after: function(){}     // Function: After callback
              });
          </script>
          <p></p>
          <h2>Our Story</h2>

          <div class="button_layout">
              <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--colored CategoryCheck" id="All" onclick="getIdValue(this.id)">전체</button>
              <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent CategoryCheck" id="Latest" onclick="getIdValue(this.id)">시작일</button> <!--시간순-->
              <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent CategoryCheck" id="Late" onclick="getIdValue(this.id)">등록일</button> <!--시간순-->
              <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent CategoryCheck" id="Best" onclick="getIdValue(this.id)">좋아요</button>  <!--추천-->
              <div class="dropdown">
                  <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent CategoryCheck dropbtn" id="Season" style="min-width:74px;">월별</button>    <!--계절-->
                  <div class="dropdown-content">
                      <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent CategoryCheck dropbtn Month" id="January" onclick="getIdValue(this.id)">1월</button>
                      <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent CategoryCheck dropbtn Month" id="February" onclick="getIdValue(this.id)">2월</button>
                      <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent CategoryCheck dropbtn Month" id="March" onclick="getIdValue(this.id)">3월</button>
                      <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent CategoryCheck dropbtn Month" id="April" onclick="getIdValue(this.id)">4월</button>
                      <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent CategoryCheck dropbtn Month" id="May" onclick="getIdValue(this.id)">5월</button>
                      <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent CategoryCheck dropbtn Month" id="June" onclick="getIdValue(this.id)">6월</button>
                      <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent CategoryCheck dropbtn Month" id="July" onclick="getIdValue(this.id)">7월</button>
                      <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent CategoryCheck dropbtn Month" id="August" onclick="getIdValue(this.id)">8월</button>
                      <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent CategoryCheck dropbtn Month" id="September" onclick="getIdValue(this.id)">9월</button>
                      <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent CategoryCheck dropbtn Month" id="October" onclick="getIdValue(this.id)">10월</button>
                      <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent CategoryCheck dropbtn Month" id="November" onclick="getIdValue(this.id)">11월</button>
                      <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent CategoryCheck dropbtn Month" id="December" onclick="getIdValue(this.id)">12월</button>
                  </div>
              </div>
              <div class="dropdown">
                  <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent CategoryCheck dropbtn" id="Formation">인원수</button>    <!--인원-->
                  <div class="dropdown-content">
                      <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent CategoryCheck dropbtn" id="Solo" onclick="getIdValue(this.id)">혼자</button>
                      <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent CategoryCheck dropbtn" id="Friend" onclick="getIdValue(this.id)">친구</button>
                      <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent CategoryCheck dropbtn" id="Couple" onclick="getIdValue(this.id)">커플</button>
                      <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent CategoryCheck dropbtn" id="Family" onclick="getIdValue(this.id)">가족</button>
                  </div>
              </div>
          </div>

            <div id="fb-root"></div>
            <script>(function(d, s, id) {
                    var js, fjs = d.getElementsByTagName(s)[0];
                    if (d.getElementById(id)) return;
                    js = d.createElement(s); js.id = id;
                    js.src = "//connect.facebook.net/ko_KR/sdk.js#xfbml=1&version=v2.6";
                    fjs.parentNode.insertBefore(js, fjs);
                }(document, 'script', 'facebook-jssdk'));</script>
            <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
            <script src="https://apis.google.com/js/platform.js" async defer>{lang: 'ko'}</script>


            <div class="mdl-grid" id="replace">
                <?php foreach($StoryList as $row) { ?>
                    <?php if($row->MemberIdx != $member_idx) {?>
                      <div class="mdl-cell mdl-cell--3-col mdl-cell--2-col-tablet mdl-cell--2-col-phone mdl-card mdl-shadow--3dp">
                            <figure class="blurfilter blurrotate">
                                <div class="demo-card-square">
                                    <img src="<?=URL?>/images/story_represent_image/<?=$row->StoryRepresentImage.'.'.$row->StoryRepresentImageExt?>" style="width:100%; min-height:80%;">
                                    <div class="mdl-card__title mdl-card--expand"><!--style="background: url('<?/*=URL*/?>/images/storyrepresentimage/<?/*=$row->StoryRepresentImage.'.'.$row->StoryRepresentImageExt*/?>') bottom right 15% no-repeat #46B6AC;"-->
                                        <div class="mdl-card__supporting-text">
                                            <h2 class="mdl-card__title-text"> <!--style="margin-left:80px;"--><?=$row->StoryName ?></h2>
                                        </div>
                                    </div>
                                    <figcaption>
                                        <h4><a href="/Storydetail/storyDetail/<?= $row->StoryIdx; ?>/1/0"><?= $row->StoryName; ?></a></h4>
                                        <p>동행자 : <?php foreach($Companion as $companion) { if($row->StoryIdx == $companion->StoryIdx) echo $companion->MemberNickname.' '; }?> </p>
                                        <p>여행시작일 : <?=$row->StoryStartDate?></p>
                                        <p><i class="material-icons">favorite</i><?php foreach($StoryGood as $sg){if($row->StoryIdx == $sg->StoryIdx){echo $sg->countGood;}}?></p>
                                        <div class="mdl-card__menu">
                                            <button id="demo-menu-lower-right<?=$row->StoryIdx?>" class="mdl-button mdl-js-button mdl-button--icon"><i class="material-icons">more_vert</i></button>
                                            <ul class="mdl-menu mdl-menu--bottom-right mdl-js-menu mdl-js-ripple-effect" for="demo-menu-lower-right<?=$row->StoryIdx?>" style="text-align:center; margin-top:5px;">
                                                <!--<li class="mdl-menu__item">Some Action</li>-->
                                                <div class="fb-share-button" data-href="https://developers.facebook.com/docs/plugins/" data-layout="button" data-mobile-iframe="true"><a class="fb-xfbml-parse-ignore" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=https%3A%2F%2Fdevelopers.facebook.com%2Fdocs%2Fplugins%2F&amp;src=sdkpreparse">공유하기</a></div><p></p>

                                                <a href="https://twitter.com/share" class="twitter-share-button" data-url="http://167.88.115.33/MyStory/<?=$row->MemberIdx ?>" data-hashtags="OurTravelStory">Tweet</a><p></p>

                                                <a class="g-plusone" data-count="false" align="center"></a>

                                                <a href="/mystory/<?=$row->MemberIdx ?>"><li class="mdl-menu__item">모든 앨범 보기</li></a>
                                                <!--<li disabled class="mdl-menu__item">Disabled Action</li>-->
                                            </ul>
                                        </div>
                                    </figcaption>
                                    <div class="mdl-card__menu_qr">
                                        <img src="https://chart.googleapis.com/chart?cht=qr&chs=50x50&chl=http://167.88.115.33/StoryDetail/StoryDetail/<?=$row->StoryIdx ?>/1">
                                    </div>
                                </div>
                            </figure>
                          </div>
                    <?php } } ?>
            </div>
            <script>
                var StatusAll = 1;
                var StatusLatest = 0;
                var StatusLate = 0;
                var StatusBest = 0;
                var StatusMonth = 0;
                var StatusFormation = 0;

                // Button Class Change
                function getIdValue(getValue) {
                    var classValueA = 'mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent CategoryCheck';
                    var classValueB = 'mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--colored CategoryCheck';
                    var classValueC = 'mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent CategoryCheck dropbtn';
                    var classValueD = 'mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--colored CategoryCheck dropbtn';


                    if (getValue == "Latest") {
                        if (StatusLatest == 1) {
                            $('#Latest').attr('class', classValueA);
                            $('#Late').attr('class', classValueA);
                            $('#All').attr('class', classValueA);
                            StatusAll = 0;
                            StatusLatest = 0;
                            StatusLate = 0;
                        } else {
                            $('#Latest').attr('class', classValueB);
                            $('#Late').attr('class', classValueA);
                            $('#All').attr('class', classValueA);
                            StatusAll = 0;
                            StatusLatest = 1;
                            StatusLate = 0;
                        }
                    } else if (getValue == "Late") {
                        if (StatusLate == 1) {
                            $('#Late').attr('class', classValueA);
                            $('#Latest').attr('class', classValueA);
                            $('#All').attr('class', classValueA);
                            StatusAll = 0;
                            StatusLatest = 0;
                            StatusLate = 0;
                        } else {
                            $('#Late').attr('class', classValueB);
                            $('#Latest').attr('class', classValueA);
                            $('#All').attr('class', classValueA);
                            StatusAll = 0;
                            StatusLatest = 0;
                            StatusLate = 1;
                        }
                    }

                    if (getValue == "Best") {
                        if (StatusBest == 1) {
                            $('#Best').attr('class', classValueA);
                            $('#All').attr('class', classValueA);
                            StatusAll = 0;
                            StatusBest = 0;
                        } else {
                            $('#Best').attr('class', classValueB);
                            $('#All').attr('class', classValueA);
                            StatusAll = 0;
                            StatusBest = 1;
                        }
                    }

                    if (getValue == "January") {
                        if (StatusMonth == 1) {
                            $('#Season').attr('class', classValueC);
                            $('#January').attr('class', classValueC+' Month');
                            $('#All').attr('class', classValueA);
                            StatusAll = 0;
                            StatusMonth = 0;
                        } else {
                            $('#Season').attr('class', classValueD);
                            $('.Month').attr('class',classValueC+' Month');
                            $('#January').attr('class', classValueD+' Month');
                            $('#All').attr('class', classValueA);
                            StatusAll = 0;
                            StatusMonth = 1;
                        }
                    } else if(getValue == "February") {
                        if (StatusMonth == 2) {
                            $('#Season').attr('class', classValueC);
                            $('#February').attr('class', classValueC+' Month');
                            $('#All').attr('class', classValueA);
                            StatusAll = 0;
                            StatusMonth = 0;
                        } else {
                            $('#Season').attr('class', classValueD);
                            $('.Month').attr('class', classValueC+' Month');
                            $('#February').attr('class', classValueD+' Month');
                            $('#All').attr('class', classValueA);
                            StatusAll = 0;
                            StatusMonth = 2;
                        }
                    } else if(getValue == "March") {
                        if (StatusMonth == 3) {
                            $('#Season').attr('class', classValueC);
                            $('#March').attr('class', classValueC+' Month');
                            $('#All').attr('class', classValueA);
                            StatusAll = 0;
                            StatusMonth = 0;
                        } else {
                            $('#Season').attr('class', classValueD);
                            $('.Month').attr('class', classValueC+' Month');
                            $('#March').attr('class', classValueD+' Month');
                            $('#All').attr('class', classValueA);
                            StatusAll = 0;
                            StatusMonth = 3;
                        }
                    } else if(getValue == "April") {
                        if (StatusMonth == 4) {
                            $('#Season').attr('class', classValueC);
                            $('#April').attr('class', classValueC+' Month');
                            $('#All').attr('class', classValueA);
                            StatusAll = 0;
                            StatusMonth = 0;
                        } else {
                            $('#Season').attr('class', classValueD);
                            $('.Month').attr('class', classValueC+' Month');
                            $('#April').attr('class', classValueD+' Month');
                            $('#All').attr('class', classValueA);
                            StatusAll = 0;
                            StatusMonth = 4;
                        }
                    } else if(getValue == "May") {
                        if (StatusMonth == 5) {
                            $('#Season').attr('class', classValueC);
                            $('#May').attr('class', classValueC+' Month');
                            $('#All').attr('class', classValueA);
                            StatusAll = 0;
                            StatusMonth = 0;
                        } else {
                            $('#Season').attr('class', classValueD);
                            $('.Month').attr('class', classValueC+' Month');
                            $('#May').attr('class', classValueD+' Month');
                            $('#All').attr('class', classValueA);
                            StatusAll = 0;
                            StatusMonth = 5;
                        }
                    } else if(getValue == "June") {
                        if (StatusMonth == 6) {
                            $('#Season').attr('class', classValueC);
                            $('#June').attr('class', classValueC+' Month');
                            $('#All').attr('class', classValueA);
                            StatusAll = 0;
                            StatusMonth = 0;
                        } else {
                            $('#Season').attr('class', classValueD);
                            $('.Month').attr('class', classValueC+' Month');
                            $('#June').attr('class', classValueD+' Month');
                            $('#All').attr('class', classValueA);
                            StatusAll = 0;
                            StatusMonth = 6;
                        }
                    } else if(getValue == "July") {
                        if (StatusMonth == 7) {
                            $('#Season').attr('class', classValueC);
                            $('#July').attr('class', classValueC+' Month');
                            $('#All').attr('class', classValueA);
                            StatusAll = 0;
                            StatusMonth = 0;
                        } else {
                            $('#Season').attr('class', classValueD);
                            $('.Month').attr('class', classValueC+' Month');
                            $('#July').attr('class', classValueD+' Month');
                            $('#All').attr('class', classValueA);
                            StatusAll = 0;
                            StatusMonth = 7;
                        }
                    } else if(getValue == "August") {
                        if (StatusMonth == 8) {
                            $('#Season').attr('class', classValueC);
                            $('#August').attr('class', classValueC+' Month');
                            $('#All').attr('class', classValueA);
                            StatusAll = 0;
                            StatusMonth = 0;
                        } else {
                            $('#Season').attr('class', classValueD);
                            $('.Month').attr('class', classValueC+' Month');
                            $('#August').attr('class', classValueD+' Month');
                            $('#All').attr('class', classValueA);
                            StatusAll = 0;
                            StatusMonth = 8;
                        }
                    } else if(getValue == "September") {
                        if (StatusMonth == 9) {
                            $('#Season').attr('class', classValueC);
                            $('#September').attr('class', classValueC+' Month');
                            $('#All').attr('class', classValueA);
                            StatusAll = 0;
                            StatusMonth = 0;
                        } else {
                            $('#Season').attr('class', classValueD);
                            $('.Month').attr('class', classValueC+' Month');
                            $('#September').attr('class', classValueD+' Month');
                            $('#All').attr('class', classValueA);
                            StatusAll = 0;
                            StatusMonth = 9;
                        }
                    } else if(getValue == "October") {
                        if (StatusMonth == 10) {
                            $('#Season').attr('class', classValueC);
                            $('#October').attr('class', classValueC+' Month');
                            $('#All').attr('class', classValueA);
                            StatusAll = 0;
                            StatusMonth = 0;
                        } else {
                            $('#Season').attr('class', classValueD);
                            $('.Month').attr('class', classValueC+' Month');
                            $('#October').attr('class', classValueD+' Month');
                            $('#All').attr('class', classValueA);
                            StatusAll = 0;
                            StatusMonth = 10;
                        }
                    } else if(getValue == "November") {
                        if (StatusMonth == 11) {
                            $('#Season').attr('class', classValueC);
                            $('#November').attr('class', classValueC+' Month');
                            $('#All').attr('class', classValueA);
                            StatusAll = 0;
                            StatusMonth = 0;
                        } else {
                            $('#Season').attr('class', classValueD);
                            $('.Month').attr('class', classValueC+' Month');
                            $('#November').attr('class', classValueD+' Month');
                            $('#All').attr('class', classValueA);
                            StatusAll = 0;
                            StatusMonth = 11;
                        }
                    } else if(getValue == "December") {
                        if (StatusMonth == 12) {
                            $('#Season').attr('class', classValueC);
                            $('#December').attr('class', classValueC+' Month');
                            $('#All').attr('class', classValueA);
                            StatusAll = 0;
                            StatusMonth = 0;
                        } else {
                            $('#Season').attr('class', classValueD);
                            $('.Month').attr('class', classValueC+' Month');
                            $('#December').attr('class', classValueD+' Month');
                            $('#All').attr('class', classValueA);
                            StatusAll = 0;
                            StatusMonth = 12;
                        }
                    }

                    if (getValue == "Solo") {
                        if (StatusFormation == 1) {
                            $('#Formation').attr('class', classValueC);
                            $('#Solo').attr('class', classValueC);
                            $('#All').attr('class', classValueA);
                            StatusAll = 0;
                            StatusFormation = 0;
                        } else {
                            $('#Formation').attr('class', classValueD);
                            $('#Solo').attr('class', classValueD);
                            $('#Friend').attr('class', classValueC);
                            $('#Couple').attr('class', classValueC);
                            $('#Family').attr('class', classValueC);
                            $('#All').attr('class', classValueA);
                            StatusAll = 0;
                            StatusFormation = 1;
                        }
                    } else if(getValue == "Friend") {
                        if (StatusFormation == 2) {
                            $('#Formation').attr('class', classValueC);
                            $('#Friend').attr('class', classValueC);
                            $('#All').attr('class', classValueA);
                            StatusAll = 0;
                            StatusFormation = 0;
                        } else {
                            $('#Formation').attr('class', classValueD);
                            $('#Solo').attr('class', classValueC);
                            $('#Friend').attr('class', classValueD);
                            $('#Couple').attr('class', classValueC);
                            $('#Family').attr('class', classValueC);
                            $('#All').attr('class', classValueA);
                            StatusAll = 0;
                            StatusFormation = 2;
                        }
                    } else if(getValue == "Couple") {
                        if (StatusFormation == 3) {
                            $('#Formation').attr('class', classValueC);
                            $('#Couple').attr('class', classValueC);
                            $('#All').attr('class', classValueA);
                            StatusAll = 0;
                            StatusFormation = 0;
                        } else {
                            $('#Formation').attr('class', classValueD);
                            $('#Solo').attr('class', classValueC);
                            $('#Friend').attr('class', classValueC);
                            $('#Couple').attr('class', classValueD);
                            $('#Family').attr('class', classValueC);
                            $('#All').attr('class', classValueA);
                            StatusAll = 0;
                            StatusFormation = 3;
                        }
                    } else if(getValue == "Family") {
                        if (StatusFormation == 4) {
                            $('#Formation').attr('class', classValueC);
                            $('#Family').attr('class', classValueC);
                            $('#All').attr('class', classValueA);
                            StatusAll = 0;
                            StatusFormation = 0;
                        } else {
                            $('#Formation').attr('class', classValueD);
                            $('#Solo').attr('class', classValueC);
                            $('#Friend').attr('class', classValueC);
                            $('#Couple').attr('class', classValueC);
                            $('#Family').attr('class', classValueD);
                            $('#All').attr('class', classValueA);
                            StatusAll = 0;
                            StatusFormation = 4;
                        }
                    }

                    if (getValue == "All") {
                        if (StatusAll == 1) {
                            if(StatusLatest != 0 && StatusLate != 0 && StatusBest != 0 && StatusMonth != 0 && StatusFormation != 0){
                                $('#All').attr('class', classValueA);
                                StatusAll = 0;
                            }
                        } else if (StatusAll == 0) {
                            $('#All').attr('class', classValueB);
                            $('#Latest').attr('class', classValueA);
                            $('#Late').attr('class', classValueA);
                            $('#Best').attr('class', classValueA);
                            $('#Season').attr('class', classValueC);
                            $('#Formation').attr('class', classValueC);
                            $('.Month').attr('class', classValueC);
                            $('#Solo').attr('class', classValueC);
                            $('#Couple').attr('class', classValueC);
                            $('#Family').attr('class', classValueC);
                            $('#Friend').attr('class', classValueC);
                            StatusAll = 1;
                            StatusLatest = 0;
                            StatusLate = 0;
                            StatusBest = 0;
                            StatusMonth = 0;
                            StatusFormation = 0;
                        }
                    }

                    if(StatusLatest == 0 && StatusLate == 0 && StatusBest == 0 && StatusMonth == 0 && StatusFormation == 0) {
                        $('#All').attr('class', classValueB);
                        StatusAll = 1;
                    }
                }

                /* Button click Ajax */
                var result;
                $('.CategoryCheck').click(function() {
                    $.ajax({
                        url: '/main/Category',
                        data: {
                            latest: StatusLatest,
                            late: StatusLate,
                            best: StatusBest,
                            month: StatusMonth,
                            formation: StatusFormation
                        },
                        dataType: 'json',
                        success: function (data) {
                            console.log(data);
                            result = data;
                            $('#replace').empty();
                        },
                        complete: function () {
                            data_change(result);
                        }
                    });
                });

                function data_change( data ) {
                    var output = "";
                    for(var countA = 0; countA < data.StoryList.length; countA++){
                        output = "<div class='mdl-cell mdl-cell--3-col mdl-cell--4-col-tablet mdl-cell--4-col-phone mdl-card mdl-shadow--3dp'>" +
                            "<figure class='blurfilter blurrotate'>" +
                            "<div class='demo-card-square'>" +
                            "<img src='/images/story_represent_image/"+data.StoryList[countA].StoryRepresentImage+"."+data.StoryList[countA].StoryRepresentImageExt+"' style='width:100%; min-height:80%'>" +
                            "<div class='mdl-card__title mdl-card--expand'>" +
                            "<div class='mdl-card__supporting-text'>" +
                            "<h2 class='mdl-card__title-text'>"+data.StoryList[countA].StoryName+"</h2>" +
                            "</div>" +
                            "</div>" +
                            "<figcaption>" +
                            "<h4><a href='/StoryDetail/StoryDetail/"+data.StoryList[countA].StoryIdx+"/1'>"+data.StoryList[countA].StoryName+"</a></h4>" +
                            "<p>동행자 : ";
                        for(var countB = 0; countB < data.Companion.length; countB++) {
                            if(data.StoryList[countA].StoryIdx == data.Companion[countB].StoryIdx)
                                output += data.Companion[countB].MemberNickname+" ";
                        }
                        output += "</p>" +
                            "<p>여행시작일 : "+data.StoryList[countA].StoryStartDate+"</p>" +
                            "<p><i class='material-icons'>favorite</i>";
                            for(var countC = 0; countC < data.StoryGood.length; countC++) {
                              if(data.StoryList[countA].StoryIdx == data.StoryGood[countC].StoryIdx)
                                output += data.StoryGood[countC].countGood;
                            }

                            output += "</p>" +
                            "<div class='mdl-card__menu'>" +
                            "<button id='demo-menu-lower-right"+data.StoryList[countA].StoryIdx+"' class='mdl-button mdl-js-button mdl-button--icon'><i class='material-icons'>more_vert</i></button>" +
                            "<ul class='mdl-menu mdl-menu--bottom-right mdl-js-menu mdl-js-ripple-effect' for='demo-menu-lower-right"+data.StoryList[countA].StoryIdx+"' style='text-align:center; margin-top:5px;'>" +
                            "<a name='fb_share' type='button' href='http://www.facebook.com/sharer.php'></a><p></p>" +
                            "<a href='https://twitter.com/share' class='twitter-share-button' data-url='http://167.88.115.33/Story/MyStory/"+data.StoryList[countA].MemberIdx+"' data-hashtags='OurTravelStory'>Tweet</a><p></p>" +
                            "<a class='g-plusone' align='center'></a>" +
                            "<a href='/Story/MyStory/"+data.StoryList[countA].MemberIdx+"'>" +
                            "<li class='mdl-menu__item'>모든 앨범 보기</li></a>" +
                            "</ul>" +
                            "</div>" +
                            "</figcaption>" +
                            "<div class='mdl-card__menu_qr'>" +
                            "<img src='https://chart.googleapis.com/chart?cht=qr&chs=50x50&chl=http://167.88.115.33/StoryDetail/StoryDetail/"+data.StoryList[countA].StoryIdx+"/1'>" +
                            "</div>" +
                            "</div>" +
                            "</figure>" +
                            "</div>";
                        $('#replace').append(output);
                    }
                }
            </script>
        </div>

    </section>
  </main>
</div>
