<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$check = isset($_SESSION['MemberIdx']) ? $_SESSION['MemberIdx'] : null;
//var_dump($MyStoryList);
?>

<main class="mdl-layout__content">
    <section class="mdl-layout__tab-panel is-active">
        <div class="page-content">
            <?php if($MyStoryList == null) { ?>
                <h1>My Story</h1>
                <div>여행 기록이 없습니다.</div>
            <?php } else {
                if($MyStoryList[0]->MemberIdx == $check) { ?>
                    <h1>My Story <img src="https://chart.googleapis.com/chart?cht=qr&chs=100x100&chl=http://localhost/Story/MyStory/<?=$MyStoryList[0]->MemberIdx ?>" align="right"></h1>
                <?php } else { ?>
                    <h1><?=$MyStoryList[0]->MemberNickname?>'s Story</h1>
                <?php } ?>
                <div class="mdl-grid" align="center">
                    <?php foreach($MyStoryList as $row) { ?>
                        <div class="mdl-cell mdl-cell--4-col">
                            <figure class="blurfilter blurrotate">
                                <div class="demo-card-square mdl-card mdl-shadow--6dp">
                                  <?php if($row->StoryRepresentImage != null) { ?>
                                    <img src="<?=URL?>/images/story_represent_image/<?=$row->StoryRepresentImage.'.'.$row->StoryRepresentImageExt ?>" style="width:100%; min-height:80%;">
                                  <?php } else { ?>
                                    <img src="<?=URL?>/images/story_represent_image/basic.jpg" style="width:100%; min-height:80%;">
                                  <?php } ?>
                                    <div class="mdl-card__title mdl-card--expand">
                                        <div class="mdl-card__supporting-text">
                                            <h2 class="mdl-card__title-text"><?=$row->StoryName ?></h2>
                                        </div>
                                    </div>
                                    <figcaption>
                                      <?php
                                      if( strtotime($row->StoryStartDate) > time()) { ?>
                                              <!-- 현재 날짜가 여행시작일의 이전 날짜라면 plan 페이지로 -->
                                              <h4><a href="/plan/<?=$row->StoryIdx ?>"><?=$row->StoryName ?></a></h4>
                                      <?php } else { ?>
                                              <!-- 현재 날짜가 여행시작일의 이후 날짜라면 storydetail 페이지로-->
                                              <h4><a href="/Storydetail/storyDetail/<?=$row->StoryIdx ?>/1/0"><?=$row->StoryName ?></a></h4>
                                      <?php }?>
                                        <p>동행자 : <?php foreach($Companion as $companion) { if($row->StoryIdx == $companion->StoryIdx) echo $companion->MemberNickname.' '; }?> </p>
                                        <p>여행시작일 : <?=$row->StoryStartDate?></p>
                                        <p><i class="material-icons">favorite</i>50</p>
                                        <div class="mdl-card__menu">
                                            <button id="demo-menu-lower-right<?=$row->StoryIdx?>" class="mdl-button mdl-js-button mdl-button--icon"><i class="material-icons">more_vert</i></button>
                                            <ul class="mdl-menu mdl-menu--bottom-right mdl-js-menu mdl-js-ripple-effect" for="demo-menu-lower-right<?=$row->StoryIdx?>" style="margin-top:5px;">
                                                <!--<li class="mdl-menu__item">Some Action</li>-->
                                                <a name="fb_share" type="button" href="http://www.facebook.com/sharer.php"></a><p></p>
                                                <script src="http://static.ak.fbcdn.net/connect.php/js/FB.Share" type="text/javascript"></script>
                                                <a href="https://twitter.com/share" class="twitter-share-button" data-url="http://localhost/Story/MyStory/<?=$row->MemberIdx ?>" data-hashtags="OurTravelStory"></a><p></p>
                                                <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
                                                <a class="g-plusone" data-count="false"></a>
                                                <script src="https://apis.google.com/js/platform.js" async defer>{lang: 'ko'}</script>
                                                <?php if($row->MemberIdx == $check) {
                                                    if($row->StoryPublicCheck == '1') { ?>
                                                    <a href="/Story/PublicCheck/<?=$row->StoryIdx ?>/<?=$row->StoryPublicCheck ?>"><li class="mdl-menu__item">스토리 공유취소</li></a>
                                                <?php } else { ?>
                                                    <a href="/Story/PublicCheck/<?=$row->StoryIdx ?>/<?=$row->StoryPublicCheck ?>"><li class="mdl-menu__item">스토리 공유하기</li></a>
                                                <?php } ?>
                                                    <li class="mdl-menu__item" id="QR">QR 명함 만들기</li>
                                                <?php }?>
                                                <!--<li disabled class="mdl-menu__item">Disabled Action</li>-->
                                            </ul>
                                            <script>
                                                $('#QR').click(function() {
                                                   window.open('/story/mystory/1');
                                                });
                                            </script>
                                        </div>
                                        <div>
                                            <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent"onclick="location.href='/plan/<?=$row->StoryIdx?>'">계획페이지로</button>
                                        </div>
                                    </figcaption>
                                    <div class="mdl-card__menu_qr">
                                        <img src="https://chart.googleapis.com/chart?cht=qr&chs=50x50&chl=http://localhost/StoryDetail/StoryDetail/<?=$row->StoryIdx ?>/1">
                                    </div>
                                    <div class="mdl-card__menu">
                                        <?php if($row->MemberIdx == $check) { ?>
                                            <?php if($row->StoryPublicCheck == '1') { ?>
                                                <i class="material-icons">share</i>
                                            <?php } else { ?>
                                                <i class="material-icons">clear</i>
                                            <?php } ?>
                                    <?php } ?>
                                    </div>
                                </div>
                            </figure>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>
    </section>
</main>
