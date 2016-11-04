<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$check = isset($_SESSION['MemberIdx']) ? $_SESSION['MemberIdx'] : null;

$background_number = mt_rand(1, 10);
?>

<main class="mdl-layout__content">
    <section class="mdl-layout__tab-panel is-active">
        <div class="page-content">
            <?php if($MyStoryList == null) { ?>
                
            <?php } else {
                if($MyStoryList[0]->MemberIdx == $check) { ?>
                    <div class="mdl-grid" style="magin: 0; padding: 0;">
                        <div class="mdl-cell mdl-cell--12-col" style="width: 100%; height: 40%; padding: 0; margin: 0; background-image: url('http://167.88.115.33/images/mystory_background/<?= $background_number; ?>.jpg'); background-position: center; background-size: cover; color: grey;"> 
                            <div style="text-align: center; padding-top: 50px;">
                                <img src="http://167.88.115.33/images/user_profile_image/<?= $MemberInfo[0]->MemberProfile; ?>.<?= $MemberInfo[0]->MemberProfileExt; ?>" style="width: 250px; height: 250px; text-align: left; margin: 0 auto; border-radius: 50%;" />
                                <ul class="mdl-list">
                                    <li class="mld-list__item">
                                        <span style="padding-top: 25px; font-weight: 900; font-size: 48px; width: 250px; height: 60px; display: inline-block; background-color: white; border-radius: 50px 50px 0px 0px;"><?= $MemberInfo[0]->MemberNickname; ?></span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!-- <img src="https://chart.googleapis.com/chart?cht=qr&chs=100x100&chl=http://167.88.115.33/mystory/<?=$MyStoryList[0]->MemberIdx ?>" align="right">-->
                <?php } else { ?>
                    <h1><?=$MyStoryList[0]->MemberNickname?>'s Story</h1>
                <?php } ?>
                <div class="mdl-grid" align="center" style="width: 50%;">
                    <?php foreach($MyStoryList as $row) { ?>
                        <div class="mdl-cell mdl-cell--4-col mdl-card mdl-shadow--6dp">
                            <figure class="blurfilter blurrotate">
                                <div class="demo-card-square">
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
                                              <h4><a href="/Storysummary/storySummary/<?= $row->StoryIdx; ?>/1/0"><?=$row->StoryName ?></a></h4>
                                      <?php }?>
                                        <p>同行者 : <?php foreach($Companion as $companion) { if($row->StoryIdx == $companion->StoryIdx) echo $companion->MemberNickname.' '; }?> </p>
                                        <p>旅行開始日 : <?=$row->StoryStartDate?></p>
                                        <p><i class="material-icons">favorite</i>5</p>
                                        <div class="mdl-card__menu">
                                            <button id="demo-menu-lower-right<?=$row->StoryIdx?>" class="mdl-button mdl-js-button mdl-button--icon"><i class="material-icons">more_vert</i></button>
                                            <ul class="mdl-menu mdl-menu--bottom-right mdl-js-menu mdl-js-ripple-effect" for="demo-menu-lower-right<?=$row->StoryIdx?>" style="margin-top:5px;">
                                                <!--<li class="mdl-menu__item">Some Action</li>-->
                                                <div class="fb-share-button" data-href="https://developers.facebook.com/docs/plugins/" data-layout="button" data-mobile-iframe="true"><a class="fb-xfbml-parse-ignore" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=https%3A%2F%2Fdevelopers.facebook.com%2Fdocs%2Fplugins%2F&amp;src=sdkpreparse">share</a></div><p></p>
                                                <script src="http://static.ak.fbcdn.net/connect.php/js/FB.Share" type="text/javascript"></script>
                                                <a href="https://twitter.com/share" class="twitter-share-button" data-url="http://localhost/Story/MyStory/<?=$row->MemberIdx ?>" data-hashtags="OurTravelStory"></a><p></p>
                                                <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
                                                <a class="g-plusone" data-count="false"></a>
                                                <script src="https://apis.google.com/js/platform.js" async defer>{lang: 'ko'}</script>
                                                <?php if($row->MemberIdx == $check) {
                                                    if($row->StoryPublicCheck == '1') { ?>
                                                    <a href="/Story/PublicCheck/<?=$row->StoryIdx ?>/<?=$row->StoryPublicCheck ?>"><li class="mdl-menu__item">共有取り消し</li></a>
                                                <?php } else { ?>
                                                    <a href="/Story/PublicCheck/<?=$row->StoryIdx ?>/<?=$row->StoryPublicCheck ?>"><li class="mdl-menu__item">共有する</li></a>
                                                <?php }} ?>
                                                <!--<li disabled class="mdl-menu__item">Disabled Action</li>-->
                                            </ul>
                                        </div>
                                        <div>
                                            <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent"onclick="location.href='/plan/<?=$row->StoryIdx?>'">計画ページへ</button>
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
