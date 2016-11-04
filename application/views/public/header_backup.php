<?php
defined('BASEPATH') OR exit('No direct script access allowed');
// 로그인 유무 상태 확인
$member_idx = isset($_SESSION['MemberIdx']) ? $_SESSION['MemberIdx'] : null;
$member_nickname = isset($_SESSION['MemberNickname']) ? $_SESSION['MemberNickname'] : null;
$now_tab_menu = isset($_SESSION['nowTab']) ? $_SESSION['nowTab'] : null;
$create_story = isset($_SESSION['create_story']) ? $_SESSION['create_story'] : null;
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Our travel Story</title>

  <!-- Material Design Lite  -->
  <link rel="stylesheet" href="<?php echo URL; ?>/assets/css/public/material.min.css" type="text/css" />
  <link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/angular_material/1.1.0-rc4/angular-material.min.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">

  <!-- Whole CSS -->
  <link rel="stylesheet" href="<?php echo URL; ?>/assets/css/public/index.css" type="text/css" />
  <link rel="stylesheet" href="<?php echo URL; ?>/assets/css/public/page_transition.css" type="text/css" />

  <!-- Story CSS -->
  <link rel="stylesheet" href="<?php echo URL; ?>/assets/css/story/index_lsy.css" type="text/css" />
  <link rel="stylesheet" href="<?php echo URL; ?>/assets/css/public/farbtastic.css" type="text/css" />

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
</head>
<body>
  <!-- Simple header with scrollable tabs. mdl-js-layout -->
  <div class="mdl-layout mdl-layout--fixed-header">
    <header class="mdl-layout__header" style="height:120px">
      <div class="mdl-layout__header-row">
        <!-- Title -->
        <span class="mdl-layout-title">Our Travel Story</span>
        <?php if(!$member_idx) { ?>
          <div class="UserStatus">
          <button id="loginBtn" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent show-modal_login">
            Login
          </button>
          <dialog class="mdl-dialog Login">
            <div class="mdl-dialog__title">
              Login
            </div>
            <form action="/Member/Login" method="post">
              <div class="mdl-dialog__content">
                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                  <input class="mdl-textfield__input" type="text" name="MemberEmail" pattern="[^\<|^\>|^\']*$" maxlength="50" id="MemberEmail" />
                  <label class="mdl-textfield__label" for="MemberEmail">Email</label>
                  <span class="mdl-textfield__error"><, >, ', | is forbidden entry.</span>
                </div>
                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                  <input class="mdl-textfield__input" type="password" name="MemberPassword" pattern="[^\<|^\>|^\']*$" maxlength="50" id="MemberPassword" />
                  <label class="mdl-textfield__label" for="MemberPassword">Password</label>
                  <span class="mdl-textfield__error"><, >, ', | is forbidden entry.</span>
                </div>
              </div>
              <div class="mdl-dialog__actions">
                <button type="button" class="mdl-button close">Close</button>
                <input type="submit" class="mdl-button" value="Login" />
              </div>
            </form>
          </dialog>

          <button id="joinBtn" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent show-modal_join">
            Join
          </button>
          <dialog class="mdl-dialog Join">
            <form action="/Member/Join" method="post">
              <div class="mdl-dialog__title">
                Join
              </div>
              <div class="mdl-dialog__content">
                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                  <input class="mdl-textfield__input" type="text" name="MemberJoinEmail" pattern="[^\<|^\>|^\']*$" maxlength="50" id="MemberJoinEmail" />
                  <label class="mdl-textfield__label" for="MemberJoinEmail">Email</label>
                  <span class="mdl-textfield__error"><, >, ', | is forbidden entry.</span>
                </div>
                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                  <input class="mdl-textfield__input" type="text" name="MemberJoinNickname" pattern="[^\<|^\>|^\']*$" maxlength="50" id="MemberJoinNickname" />
                  <label class="mdl-textfield__label" for="MemberJoinNickname">Nickname</label>
                  <span class="mdl-textfield__error"><, >, ', | is forbidden entry.</span>
                </div>
                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                  <input class="mdl-textfield__input" type="password" name="MemberJoinPassword" pattern="[^\<|^\>|^\']*$" maxlength="50" id="MemberJoinPassword" />
                  <label class="mdl-textfield__label" for="MemberJoinPassword">Password</label>
                  <span class="mdl-textfield__error"><, >, ', | is forbidden entry.</span>
                </div>
              </div>
              <div class="mdl-dialog__actions">
                <button type="button" class="mdl-button close">Close</button>
                <input type="submit" class="mdl-button" value="Join" />
              </div>
            </form>
          </dialog>
        </div>
        <?php } else { ?>
          <div class="UserStatus">
               <?php if($_SESSION['MemberProfile'] == null) { ?>
                   <i class="material-icons" style="max-width:100%;">account_circle</i>
               <?php }  else { ?>
                   <img src="<?=URL;?>/images/user_profile_image/<?=$_SESSION['MemberProfile'].'.'.$_SESSION['MemberProfileExt'] ?>" style="max-width:100%; width:40px;">
               <?php } ?>
               <a class="show-modal_userinfo"><?=$_SESSION['MemberNickname'] ?></a>
               <dialog class="mdl-dialog UserInfo">
                   <div class="mdl-dialog__title">
                       Profile
                   </div>
                   <div class="mdl-dialog__content" align="center">
                       <img src="<?=URL?>/images/user_profile_image/<?=$_SESSION['MemberProfile'].'.'.$_SESSION['MemberProfileExt']?>" style="width:150px; height:auto;">
                   </div>
                   <div class="mdl-dialog__content">
                       Email : <input class="mdl-textfield__input" type="text" pattern="[^\<|^\>|^\']*$" maxlength="50" value="<?=$_SESSION['MemberEmail']?>" readonly />
                   </div>
                   <div class="mdl-dialog__content">
                       Nickname : <input class="mdl-textfield__input" type="text" pattern="[^\<|^\>|^\']*$" maxlength="50" value="<?=$_SESSION['MemberNickname']?>" readonly />
                   </div>
                   <div class="mdl-dialog__content">
                       JoinDate : <input class="mdl-textfield__input" type="text" pattern="[^\<|^\>|^\']*$" maxlength="50" value="<?=$_SESSION['MemberJoindate']?>" readonly />
                   </div>
                   <div class="mdl-dialog__actions">
                       <button class="mdl-button close">Close</button>
                   </div>
               </dialog>
               <!--Member Information Update-->
               <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent show-modal_userupdate">Update</button>
               <dialog class="mdl-dialog UserUpdate">
                   <form action="/Member/Update" method="post" enctype="multipart/form-data">
                       <div class="mdl-dialog__title">
                           Update
                       </div>
                       <div class="mdl-dialog__content">
                           <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                               <input class="mdl-textfield__input" type="text" name="MemberUpdateEmail" pattern="[^\<|^\>|^\']*$" maxlength="50" id="MemberUpdateEmail" value="<?=$_SESSION['MemberEmail']?>" readonly />
                               <label class="mdl-textfield__label" for="MemberUpdateEmail">Email</label>
                               <span class="mdl-textfield__error"><, >, ', | is forbidden entry.</span>
                           </div>
                           <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                               <input class="mdl-textfield__input" type="text" name="MemberUpdateNickname" pattern="[^\<|^\>|^\']*$" maxlength="50" id="MemberUpdateNickname" value="<?=$_SESSION['MemberNickname']?>" readonly />
                               <label class="mdl-textfield__label" for="MemberUpdateNickname">Nickname</label>
                               <span class="mdl-textfield__error"><, >, ', | is forbidden entry.</span>
                           </div>
                           <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                               <input class="mdl-textfield__input" type="password" name="MemberUpdatePassword" pattern="[^\<|^\>|^\']*$" maxlength="50" id="MemberUpdatePassword" autofocus />
                               <label class="mdl-textfield__label" for="MemberUpdatePassword">To Change Password</label>
                               <span class="mdl-textfield__error"><, >, ', | is forbidden entry.</span>
                           </div>
                           <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" align="center">
                               <input type="file" name="MemberProfileImage" id="MemberProfileImage">
                               <img src="<?=URL?>/images/user_profile_image/<?=$_SESSION['MemberProfile'].'.'.$_SESSION['MemberProfileExt']?>" style="height: 230px; width: 230px;">
                           </div>
                       </div>
                       <div class="mdl-dialog__actions">
                           <button type="button" class="mdl-button close">Close</button>
                           <input type="submit" class="mdl-button" value="Update" />
                       </div>
                   </form>
               </dialog>
               <a href="/Member/Logout" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent">Logout</a>

           </div>
           <!-- Create Story Button -->
           <script>
               var dialogUserUpdate = document.querySelector('dialog.UserUpdate');
               var showModalUserUpdateButton = document.querySelector('.show-modal_userupdate');
               var dialogUserInfo = document.querySelector('dialog.UserInfo');
               var showModalUserInfo = document.querySelector('.show-modal_userinfo');

               if (! dialogUserUpdate.showModal) {
                   dialogPolyfill.registerDialog(dialogUserUpdate);
               } else if(! dialogUserInfo.showModal) {
                   dialogPolyfill.registerDialog(dialogUserInfo);
               }

               showModalUserUpdateButton.addEventListener('click', function() {
                   dialogUserUpdate.showModal();
               });

               showModalUserInfo.addEventListener('click', function() {
                   dialogUserInfo.showModal();
               });

               dialogUserUpdate.querySelector('.close').addEventListener('click', function() {
                   dialogUserUpdate.close();
               });

               dialogUserInfo.querySelector('.close').addEventListener('click', function() {
                   dialogUserInfo.close();
               });
           </script>
       <?php } ?>
     </div>

      <!-- Tabs -->
      <div class="mdl-layout__tab-bar mdl-js-ripple-effect">
        <a href="/main" class="mdl-layout__tab" id="main" style="color: white;">Main</a>
        <a href="/search" class="mdl-layout__tab" id="search" style="color: white;">Place Search</a>
        <?php if($member_idx) { ?>
          <a href="/mystory/<?= $_SESSION['MemberIdx'] ?>" class="mdl-layout__tab" id="mystory" style="color: white;">My Story</a>
        <?php } ?>
      </div>

      <script>
        var nowTab = '<?= $now_tab_menu; ?>';
        console.log(nowTab);
        switch (nowTab) {
          case 'main': document.querySelector('#main').className += " is-active"; break;
          case 'search': document.querySelector('#search').className += " is-active"; break;
          case 'mystory': document.querySelector('#mystory').className += " is-active"; break;
          default: break;
        }
      </script>
    </header>
    <div class="mdl-layout__drawer">
      <span class="mdl-layout-title">Title</span>
    </div>

    <?php if(!$member_idx) { ?>
      <script>
        var dialogLogin = document.querySelector('dialog.Login');
        var showModalLoginButton = document.querySelector('.show-modal_login');
        var dialogJoin = document.querySelector('dialog.Join');
        var showModalJoinButton = document.querySelector('.show-modal_join');

        if (dialogLogin && !dialogLogin.showModal) {
          dialogPolyfill.registerDialog(dialogLogin);
        } else if(dialogJoin && !dialogJoin.showModal) {
          dailogPolyfill.registerDialog(dialogJoin);
        }

        showModalLoginButton.addEventListener('click', function() {
          dialogLogin.showModal();
        });

        dialogLogin.querySelector('.close').addEventListener('click', function() {
          dialogLogin.close();
        });

        showModalJoinButton.addEventListener('click', function() {
          dialogJoin.showModal();
        });

        dialogJoin.querySelector('.close').addEventListener('click', function() {
          dialogJoin.close();
        });
      </script>
    <?php } ?>

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

      <!-- 시영 전역변수(storydetail) -->
      <script>
        var screensizecheck =0;
        var selectors = "";
      </script>
      <!-- 시영 전역변수(storyde  tail) -->


    <?php
  }
  ?>
