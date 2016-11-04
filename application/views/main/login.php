<div class="body">
    <div class="android-content mdl-layout__content">
        <a name="top"></a>
      
        <div class="login_box">
            <h4 style="margin-left:10px;"><i class="material-icons">input</i> Login</h4><h4 style="position:fixed; margin-left:34%; margin-top:-4%;"><i class="material-icons">person_add</i> Join</h4><hr>
            <div>
                <h6 style="margin-left:3%;">Enter your email and password</h6><h6 style="position:fixed; margin-left:34%; margin-top:-3.25%;">Don' have an account? Sign Up!</h6>
                <form action="/member/login" method="post" style="margin-top:5%; margin-left:2%;">
                    <table>
                        <tr>
                            <td><input class="mdl-textfield__input" type="email" name="MemberEmail" maxlength="50" placeholder="Email" autofocus style="width:300px; height:30px;"></td>
                        </tr>
                        <tr>
                            <td><input class="mdl-textfield__input" type="password" name="MemberPassword" maxlength="20" placeholder="Password" style="width:300px; height:30px;"></td>
                        </tr>
                        <tr>
                            <td><input class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--accent" type="submit" value="Login" style="width:100%; height:50px;"></td>
                        </tr>
                    </table>
                </form>
            </div>
            <div class="join_box">
                <form action="/member/join" method="post">
                    <table>
                        <tr>
                            <td><input class="mdl-textfield__input" type="email" name="MemberJoinEmail" maxlength="50" placeholder="Email" style="width:300px; height:30px;"></td>
                        </tr>
                        <tr>
                            <td><input class="mdl-textfield__input" type="password" name="MemberJoinPassword" placeholder="Password"></td>
                        </tr>
                        <tr>
                            <td><input class="mdl-textfield__input" type="password" name="MemberJoinPasswordConfirm" placeholder="Password Confirm"></td>
                        </tr>
                        <tr>
                            <td><input class="mdl-textfield__input" type="nickname" name="MemberJoinNickname" placeholder="Nickname"></td>
                        <tr>
                            <td><input class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--accent" type="submit" value="Sign Up" style="width:100%;"></td>
                        </tr>
                    </table>
                </form>
            </div>
            <img src="/assets/img/login/border.png" class="or">
            <br>
            <div align="center">
                <?php
                    $image = array("travel1.jpg","travel2.jpg","travel3.jpg","travel4.jpg","travel5.jpg","travel6.jpg");
                    $random = time()%count($image);
                ?>
                <img src="/assets/img/login/<?=$image[$random]?>" style="width:98%; margin-left: 5px; margin-right:5px; margin-bottom:10px;"/>
            </div>
        </div>
    </div>
    <script>
        $('#joinpage').click(function() {
            $.ajax({
                url: '/member/joinpage',
                dataType: 'html',
                success: function(data){
                    $('.login_join_box').empty();
                    $('.login_join_box').document.html(data.result);
                }
            })
        });

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
            nextText: "Next",       // String: Text for the "next" button
            maxwidth: "",           // Integer: Max-width of the slideshow, in pixels
            navContainer: "",       // Selector: Where controls should be appended to, default is after the 'ul'
            manualControls: "",     // Selector: Declare custom pager navigation
            namespace: "rslides",   // String: Change the default namespace used
            before: function(){},   // Function: Before callback
            after: function(){}     // Function: After callback
        });
    </script>
</div>
