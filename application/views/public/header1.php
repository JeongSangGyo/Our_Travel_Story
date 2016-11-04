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

    <!-- Angular Material -->
    <link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/angular_material/1.1.0-rc4/angular-material.min.css">

    <!-- Album slider CSS-->
    <link rel="stylesheet" href="<?php echo URL; ?>/assets/css/public/pmgslider.min.css" type="text/css" />

    <!-- Album Scrolling CSS -->
    <link rel="stylesheet" href="<?php echo URL; ?>/assets/css/public/storydetail-scroll.css" type="text/css" />

    <!-- Whole CSS -->
    <link rel="stylesheet" href="<?php echo URL; ?>/assets/css/public/styles.css">
    <link rel="stylesheet" href="<?php echo URL; ?>/assets/css/public/index.css" type="text/css" />
    <link rel="stylesheet" href="<?php echo URL; ?>/assets/css/public/page_transition.css" type="text/css" />

    <!-- Story CSS -->
    <link rel="stylesheet" href="<?php echo URL; ?>/assets/css/story/index_lsy.css" type="text/css" />
    <!-- Main & Mystory CSS -->
    <link rel="stylesheet" href="<?php echo URL; ?>/assets/css/public/index_JSG.css" type="text/css" />

    <!-- Fullcalendar CSS -->
    <link rel="stylesheet" href="<?php echo URL; ?>/assets/css/public/fullcalendar.css" />
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
      <div class="android-header mdl-layout__header mdl-layout__header--waterfall">
        <div class="mdl-layout__header-row" style="height: 60px;">
          <span class="android-title mdl-layout-title" style="font-weight: bold; color: black; position: absolute; left: 20px; top: 14px;">
            <a href="<?= URL; ?>">
              <img src="<?php echo URL; ?>/assets/img/logo.png" style="height: 36px; width: 300px;"/>
            </a>
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
              <a href="/main" class="mdl-navigation__link mdl-typography--text-uppercase" href="">Main</a>
              <?php if (!$member_idx) { ?>
              <a href="/member/loginpage" class="mdl-navigation__link mdl-typography--text-uppercase" href="">Login</a>
              <a href="/member/joinpage" class="mdl-navigation__link mdl-typography--text-uppercase" href="">Join</a>
              <?php } else { ?>
              <a href="/mystory/<?=$member_idx?>" class="mdl-navigation__link mdl-typography--text-uppercase" href="">My Story</a>
              <a href="/Member/Logout" class="mdl-navigation__link mdl-typography--text-uppercase" href="">Logout</a>
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
