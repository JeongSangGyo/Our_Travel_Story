<h2>Login Page</h2>
<div class="login_join_box">
    <h4>로그인</h4><hr>
    <form action="/member/login" method="post" style="margin-top:20px; margin-left:10px;">
        <table>
            <tr>
                <td><input class="mdl-textfield__input" type="text" name="MemberEmail" maxlength="50" placeholder="Email" autofocus style="width:300px; height:30px;"></td>
            </tr>
            <tr>
                <td><input class="mdl-textfield__input" type="password" name="MemberPassword" maxlength="20" placeholder="Password" style="width:300px; height:30px;"></td>
            </tr>
            <tr>
                <td><input class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--accent" type="submit" value="Login" style="width:48%; height:40px;"> | <button class="mdl-button mdl-button--accent" onclick="joinpage()" style="width:48%; height:40px;">Join</button></td>
            </tr>
        </table>
    </form>
    <br>
    <div align="center">
        <ul class="rslides" style="width:98%; margin-left: 5px; margin-right:5px; margin-bottom:10px;">
            <li><img src="/images/1.jpg" alt=""></li>
            <li><img src="/images/2.jpg" alt=""></li>
            <li><img src="/images/3.jpg" alt=""></li>
            <li><img src="/images/4.jpg" alt=""></li>
        </ul>
    </div>
</div>
<script>
    function joinpage() {
        $.ajax({
            url: '/member/JoinPage',
            dataType: 'html',
            success: function(data) {

            }
        });
    }

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
