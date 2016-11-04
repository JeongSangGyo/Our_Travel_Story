<?php
defined('BASEPATH') OR exit('No direct script access allowed');
// 로그인 유무 상태 확인
$member_idx = isset($_SESSION['MemberIdx']) ? $_SESSION['MemberIdx'] : null;
$member_nickname = isset($_SESSION['MemberNickname']) ? $_SESSION['MemberNickname'] : null;
$now_tab_menu = isset($_SESSION['nowTab']) ? $_SESSION['nowTab'] : null;
$create_story = isset($_SESSION['create_story']) ? $_SESSION['create_story'] : null;
?>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Cyka">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
    <title>Our travel Story</title>

    <!-- Material Design Lite  -->
    <link rel="stylesheet" href="<?php echo URL; ?>/assets/css/public/material.min.css" type="text/css" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:regular,bold,italic,thin,light,bolditalic,black,medium&amp;lang=en">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">

    <!-- Whole CSS -->
    <link rel="stylesheet" href="<?php echo URL; ?>/assets/css/public/styles.css">
    <link rel="stylesheet" href="<?php echo URL; ?>/assets/css/public/index.css" type="text/css" />
    <link rel="stylesheet" href="<?php echo URL; ?>/assets/css/public/page_transition.css" type="text/css" />

    <!-- Story CSS -->
    <link rel="stylesheet" href="<?php echo URL; ?>/assets/css/story/index_isy.css" type="text/css" />

    <!-- Login & Join CSS -->
    <link rel="stylesheet" href="<?php echo URL; ?>/assets/css/public/index_JSG.css" type="text/css" />

    <!-- Fullcalendar CSS -->
    <link rel="stylesheet" href="<?php echo URL; ?>/assets/css/public/fullcalendar.css" />

    <!-- Angular Carousel CSS -->
    <link rel="stylesheet" href="<?php echo URL; ?>/lib/angular/angular-carousel.css" type="text/css" />

    <!-- 메터리얼 라이트 모달 CSS -->
    <link rel="stylesheet" href="<?php echo URL; ?>/assets/css/public/mdl-jquery-modal-dialog.css" />

    <!-- jquery -->
    <script src="<?php echo URL; ?>/lib/js/jquery-2.2.3.js"></script>
    <script src="<?php echo URL; ?>/lib/js/farbtastic.js"></script>
    <script src="<?php echo URL; ?>/lib/js/masonry.pkgd.min.js"></script>
    <!-- 매터리얼 라이트 모달 JS -->
    <script src="<?php echo URL; ?>/assets/js/public/mdl-jquery-modal-dialog.js"></script>
    <script>
      var login_user_idx = '<?= $member_idx; ?>';
      var login_user_nickname = '<?= $member_nickname; ?>';
    </script>

    <!-- main slide show -->
    <script src="<?=URL?>/assets/js/public/responsiveslides.min.js"></script>
  </head>
  <body>
    <div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">
      <div class="android-header mdl-layout__header mdl-layout__header--waterfall">
        <div class="mdl-layout__header-row" style="height: 61px;">
          <span class="android-title mdl-layout-title" style="font-weight: bold; color: black; position: absolute; left: 20px; top: 14px;">
           <a href="<?php echo URL; ?>"><img src="<?php echo URL; ?>/assets/img/logo.png" style="height: 36px; width: 300px;"/></a>
          </span>
          <!-- Add spacer, to align navigation to the right in desktop -->
          <div class="android-header-spacer mdl-layout-spacer"></div>
          <!-- <div class="android-search-box mdl-textfield mdl-js-textfield mdl-textfield--expandable mdl-textfield--floating-label mdl-textfield--align-right mdl-textfield--full-width">
            <label class="mdl-button mdl-js-button mdl-button--icon" for="search-field">
              <i class="material-icons">search</i>
            </label>
            <div class="mdl-textfield__expandable-holder">
              <input class="mdl-textfield__input" type="text" id="search-field">
            </div>
          </div> -->
          <!-- Navigation -->
          <div class="android-navigation-container">
            <nav class="android-navigation mdl-navigation">
              <a href="/main" class="mdl-navigation__link mdl-typography--text-uppercase" href="">メイン</a>
              <?php if (!$member_idx) { ?>
              <a href="/member/loginpage" class="mdl-navigation__link mdl-typography--text-uppercase" href="">ログイン</a>
              <?php } else { ?>
              <a href="/mystory/<?=$member_idx?>" class="mdl-navigation__link mdl-typography--text-uppercase" href="">私のストーリー</a>
              <a href="/Member/Logout" class="mdl-navigation__link mdl-typography--text-uppercase" href="">ログアウト</a>
              <?php } ?>
            </nav>
          </div>
          <span class="android-mobile-title mdl-layout-title">
            <img class="android-logo-image" src="<?php echo URL; ?>/assets/img/logo.png" />
          </span>
          <!-- <button class="android-more-button mdl-button mdl-js-button mdl-button--icon mdl-js-ripple-effect" id="more-button">
            <i class="material-icons">more_vert</i>
          </button>
          <ul class="mdl-menu mdl-js-menu mdl-menu--bottom-right mdl-js-ripple-effect" for="more-button">
            <li class="mdl-menu__item">5.0 Lollipop</li>
            <li class="mdl-menu__item">4.4 KitKat</li>
            <li disabled class="mdl-menu__item">4.3 Jelly Bean</li>
            <li class="mdl-menu__item">Android History</li>
          </ul> -->
        </div>
      </div>

      <div class="android-drawer mdl-layout__drawer">
        <span class="mdl-layout-title">
          <img class="android-logo-image" src="<?php echo URL; ?>/assets/img/logo.png" style="position: absolute; left: 12px; top: 32px; height: 90px; width: 210px;"/>
        </span>
        <nav class="mdl-navigation">
          <span class="mdl-navigation__link" href="" style="font-size: 30px;">Menu</span>
          <a href="/main"class="mdl-navigation__link" href="" style="font-size: 24px;">Main</a>
          <div class="android-drawer-separator" style="font-size: 24px;"></div>
          <span class="mdl-navigation__link" href="" style="font-size: 30px;">Support</span>
          <?php if (!$member_idx) { ?>
          <a href="/member/loginpage"  class="mdl-navigation__link" href="" style="font-size: 24px;">Login</a>
          <a href="/member/joinpage" class="mdl-navigation__link" href="" style="font-size: 24px;">Join</a>
          <?php } else { ?>
          <a href="/member/logout"  class="mdl-navigation__link" href="" style="font-size: 24px;">Login</a>
          <?php } ?>
          <div class="android-drawer-separator"></div>
        </nav>
      </div>

        <?php if ($member_idx && $create_story) { ?>
        <!-- Create Story Button -->
        <button type="button" id="create-story" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-color--light-blue mdl-color-text--accent-contrast show-modal_createstory">Create Story</button>
          <dialog class="mdl-dialog CreateStory" style="width: 350px;">
            <div class="md1-dialog__title">
              Create Story
            </div>
            <div class="mdl-dialog__content">
              <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                <input class="mdl-textfield__input" type="text" pattern="[^\<|^\>|^\']*$" maxlength="50" id="createStoryName" />
                <label class="mdl-textfield__label" for="createStoryName">Story Name</label>
                <span class="mdl-textfield__error" id="createStoryError"><, >, ', | is forbidden entry.</span>
              </div>
              <div id="create_story_type">
                <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent StoryTypeCheck" id="create_story_alone">혼자</button>
                <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent StoryTypeCheck" id="create_story_friend">친구</button>
                <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent StoryTypeCheck" id="create_story_couple">애인</button>
                <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent StoryTypeCheck" id="create_story_family">가족</button>
              </div>
            </div>
            <div class="mdl-dialog__actions">
              <button type="button" class="mdl-button create_story_close">Close</button>
              <button type="button" class="mdl-button create_story_create">Create</button>
            </div>
          </dialog>
          <script>
            var dialog_create_story = document.querySelector('dialog.CreateStory');
            var showModalButton = document.querySelector('.show-modal_createstory');
            var story_type_check_btn = document.querySelectorAll('.StoryTypeCheck');
            var story_type = '';

            if (!dialog_create_story.showModal) {
              dialogPolyfill.registerDialog(dialog);
            }

            showModalButton.addEventListener('click', function() {
              dialog_create_story.showModal();
            });

            for (var iCount = 0; iCount < story_type_check_btn.length; iCount++) {
              story_type_check_btn[iCount].addEventListener('click', function() {
                var create_story_type = document.getElementById("create_story_type");
                var create_story_type_btn = create_story_type.getElementsByTagName("Button");
                var create_story_type_btn_cnt = create_story_type_btn.length;

                var create_story_type_btn_token = false;
                for (var iCount = 0; iCount < create_story_type_btn_cnt; iCount++) {
                  if (create_story_type_btn[iCount].classList.contains('mdl-button--primary')) {
                    // 이전에 활성화된 버튼이라면
                    create_story_type_btn_token = true;
                    if (create_story_type_btn[iCount] == $(this)[0]) {
                      // 이전에 활성화된 버튼과 현재 클릭한 버튼이 일치한다면
                      create_story_type_btn[iCount].classList.remove('mdl-button--primary');
                      create_story_type_btn[iCount].classList.add('mdl-button--accent');
                      story_type = '';
                      break;
                    } else {
                      // 이전에 활성화된 버튼과 현재 클릭한 버튼이 일치하지 않는다면
                      create_story_type_btn[iCount].classList.remove('mdl-button--primary');
                      create_story_type_btn[iCount].classList.add('mdl-button--accent');

                      $(this)[0].classList.remove('mdl-button--accent');
                      $(this)[0].classList.add('mdl-button--primary');
                      story_type = $(this).context.innerText;
                      break;
                    }
                  } else {
                    // 이전에 활성화된 버튼이 아니라면
                    create_story_type_btn_token = false;
                  }
                }

                if (!create_story_type_btn_token) {
                  // 이전에 활성화된 버튼이 하나도 없다면 현재 클릭한 버튼을 활성화 시켜준다.
                  $(this)[0].classList.remove('mdl-button--accent');
                  $(this)[0].classList.add('mdl-button--primary');
                  story_type = $(this).context.innerText;
                }
              });
            }

            dialog_create_story.querySelector('.create_story_create').addEventListener('click', function() {
              var create_story_name = document.querySelector('#createStoryName');
              var create_story_type = document.querySelector('.mdl-button--primary');

              if (!create_story_name.value || !create_story_type) {
                return;
              } else {
                switch (story_type) {
                  case '혼자': story_type = 1; break;
                  case '친구': story_type = 2; break;
                  case '애인': story_type = 3; break;
                  case '가족': story_type = 4; break;
                }

                var path = "/Plan/createStory";
                var params = {'story_name': create_story_name.value, 'story_type': story_type};
                var method = "post";

                var form = document.createElement("form");
                form.setAttribute("method", method);
                form.setAttribute("action", path);

                for(var key in params) {
                  var hiddenField = document.createElement("input");
                  hiddenField.setAttribute("type", "hidden");
                  hiddenField.setAttribute("name", key);
                  hiddenField.setAttribute("value", params[key]);
                  form.appendChild(hiddenField);
                }
                document.body.appendChild(form);
                form.submit();
              }
            });

            dialog_create_story.querySelector('.create_story_close').addEventListener('click', function() {
              document.querySelector('#createStoryName').value = '';
              document.querySelector('#createStoryError').value = '';
              dialog_create_story.close();
            });
          </script>
        <?php } ?>
