
<style>
    a{
        color:black;
    }
</style>




<div style="max-width: 100%; max-height:100%;background:darkgray">
    <div class="mdl-grid">
        <div class="mdl-cell mdl-cell--5-col" style="height:89.8%; background:white">

            <div class="mdl-cell mdl-cell--12-col" style="height:50%; background:white">
                <div class="mdl-cell mdl-cell--7-col" style="height:96.3%; float:left; background:beige">
                    <div style="height:75%; width:100%; background-image: url('<?=URL?>/images/story_represent_image/<?=$StoryInfo[0]->storyRepresentImage.'.'.$StoryInfo[0]->storyRepresentImageExt?>'); background-size: 100% 100%;">
                    </div>
                    <div style="height:25%; width:100%; background:whitesmoke">
                        <div style="width:100%; height:50%; background:deepskyblue; ">
                        <a style="font-size:40px; line-height:40px;"><?=$StoryInfo[0]->storyName?></a>
                        </div>
                        <div style="width:100%; height:50%; background:whitesmoke;">
                            <div style="width:75%; height:100%; float:left;"></div>
                            <div style="width:25%; height:100%; float:right;">
                                <a style="font-size:20px;"><?php
                                    if($StoryInfo[0]->storyPublicCheck == 1){?>
                                        <a><img src="<?=URL?>/images/etc_icon/lock-off.png" style="width:40px; height:40px; padding-top:3%; padding-left:20%;"></a>
                                    <?php }elseif($StoryInfo[0]->storyPublicCheck == 0){ ?>
                                        <a><img src="<?=URL?>/images/etc_icon/lock-on.png" style="width:70px; height:70px; padding-top:3%; padding-left:20%;"></a>
                                   <?php }?>
                                </a>
                                <div class="material-icons mdl-badge mdl-badge--overlap" id="footprint" data-badge="<?=$StoryGood[0]->storyGoodCount?>"  style="margin-bottom:0%; margin-top:11%; padding-top:3%; padding-left:5%; margin-right:11%; margin-left:0%; float:right; width:28px; color:hotpink; font-size:33px;">favorite</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mdl-cell mdl-cell--5-col" style="height:96.3%; float:right; background:whitesmoke">
                    <div class="mdl-cell mdl-cell--12-col" style="height:33.5%; background:whitesmoke">
                        <div style="width:39%; height:32%; background:salmon; float:left; border-bottom:solid beige 1px;">
                            <a style="float:left; margin-left:8px; font-size:23px; line-height:40px;">여행일자</a>
                        </div>
                        <div style="width:61%; height:32%; background:whitesmoke; float:right; border-bottom:solid beige 1px;">
                            <a style="float:left; margin-left:8px; font-size:23px; line-height:40px;"><?=$StoryInfo[0]->storyStartDate?></a>
                        </div>
                        <div style="width:39%; height:32%; background:salmon; float:left; border-bottom:solid beige 1px;">
                            <a style="float:left; margin-left:8px; font-size:23px; line-height:40px;">여행테마</a>
                        </div>
                        <div style="width:61%; height:32%; background:whitesmoke; float:right; border-bottom:solid beige 1px;">
                            <a style="float:left; margin-left:8px; font-size:23px; line-height:40px;">
                                <?php
                                if($StoryInfo[0]->storyformation == 1){
                                    echo "나홀로 여행";
                                }else if($StoryInfo[0]->storyformation == 2){
                                    echo "친구와 함께";
                                }else if($StoryInfo[0]->storyformation == 3){
                                    echo "커플끼리";
                                }else if($StoryInfo[0]->storyformation == 4){
                                    echo "가족들과";
                                }
                                ?>
                            </a>
                        </div>
                        <div style="width:39%; height:32%; line-height:40px; background:salmon; float:left;">
                        <a style="float:left; margin-left:8px; font-size:23px; height:32%;line-height:40px; ">여행리더</a>
                        </div>
                        <div style="width:61%; height:33%; background:whitesmoke; float:right;">
                            <a style="float:left; margin-left:8px; font-size:23px; line-height:40px;"><?=$StoryInfo[0]->memberNickname?></a>
                        </div>
                    </div>
                    <div class="mdl-cell mdl-cell--12-col" style=" height:60%; background:whitesmoke">

                        <div style="width:100%; height:10%; background:salmon;"><a style="margin-right:35.5%; float:right; font-size:20px; line-height: 25px;">사진 보기</a></div>
<!--                        <div id="topControl" class="control center" style="width:49.7%; height:40%; background:whitesmoke; float:left; border-right:solid 1px salmon;" >-->
                        <div style="width:49.8%; height:40%; background:whitesmoke; float:left; border-right:solid salmon 1px;" >
                            <button  type="button" class="mdl-button" style="width:141px; height:99px; "><a href="/Storydetail/storyDetail/<?php echo $storyIdx ?>/1/0"><img src="<?=URL?>/images/etc_icon/album.png" style="width:70px; height:70px; padding:10% 0% 10% 0%"></a></button>
                        </div>
                        <div style="width:49.8%; height:40%; background:whitesmoke; float:right" >
                             <button id="show-story_slider" type="button" class="mdl-button" style="width:100%; height:100%; "><img src="<?=URL?>/images/etc_icon/slider.png" style="width:70px; height:70px;"></button>
                            <dialog id="story_slider" class="mdl-dialog" style="width:1000px; height:850px; background:darkgrey;">
                                <div class="cntr mt20" style="width:100%; height:70%;">
                                    <div class="mdl-dialog__actions">
                                        <button type="button" class="mdl-button close">X</button>
                                    </div>
                                    <ul class="pgwSlideshow">
                                        <?php foreach($StorySliderImage as $SliderImage){ ?>
                                        <li><a href="http://en.wikipedia.org/wiki/Monaco" target="_blank"><img src="<?=URL?>/images/<?=$SliderImage->storyPlaceImageName.'.'.$SliderImage->storyPlaceImageExt?>" data-description="Golden Gate Bridge"></a></li>
                                        <?php } ?>
                                    </ul>
                                </div>
                            </dialog>
                        </div>


                        <div style="width:100%; height:10%; background:salmon; float:right;"><a style="margin-right:43%; float:right; font-size:20px; line-height: 25px;">공유</a></div>
                        <div style="width:100%; height:40%; background:whitesmoke; float:right;">
                            <div style="width:24%; height:100%; float:left; margin-top:7%; margin-left:3%;">
                                <a href="#" onclick="javascript:window.open('https://twitter.com/intent/tweet?text=[%EA%B3%B5%EC%9C%A0]%20'
                     +encodeURIComponent(document.URL)+'%20-%20'+encodeURIComponent(document.title), 'twittersharedialog',
                     'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');return false;" target="_blank"
                                   alt="Share on Twitter" ><img src="<?=URL?>/images/sns_icon/twitter_icon.png" style="width:60px; height:60px; "></a>
                            </div>
                            <div style="width:24%; height:100%; float:left; margin-top:7%;">
                                <a href="#" onclick="javascript:window.open('https://www.facebook.com/sharer/sharer.php?u='
                     +encodeURIComponent(document.URL)+'&t='+encodeURIComponent(document.title), 'facebooksharedialog',
                     'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');return false;" target="_blank"
                                   alt="Share on Facebook" ><img src="<?=URL?>/images/sns_icon/facebook_icon.png" style="width:60px; height:60px;"></a>
                            </div>
                                <div style="width:24%; height:100%; float:left; margin-top:7%;">
                                <a href="#" onclick="javascript:window.open('https://plus.google.com/share?url='
                     +encodeURIComponent(document.URL), 'googleplussharedialog','menubar=no,toolbar=no,resizable=yes,
                     scrollbars=yes,height=350,width=600');return false;" target="_blank" alt="Share on Google+">
                                    <img src="<?=URL?>/images/sns_icon/googleplus_icon.png" style="width:60px; height:60px;"></a>
                                </div>
                                    <div style="width:24%; height:100%; float:left; margin-top:7%;">
                                <a href="#" onclick="javascript:window.open('https://story.kakao.com/s/share?url='
                     +encodeURIComponent(document.URL), 'kakaostorysharedialog', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,
                     height=400,width=600');return false;" target="_blank" alt="Share on kakaostory">
                                    <img src="<?=URL?>/images/sns_icon/kakaostory_icon.png" style="width:60px; height:60px;"></a>
                                    </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mdl-cell mdl-cell--6-col" style="height:46.3%; background:whitesmoke; float:left; overflow-y: auto; overflow-x: hidden;">
                <div style="width:100%; height:10%; background:#d1c4e9"><a style="font-size:23px; margin-left:41%; line-height:40px; float:left;">동행자</a></div>
                <?php foreach($StoryCompanion as $Companion){ ?>

                    <div style="width:100%; height:20%; background:#b2ebf2; border-bottom:solid black 1px; " >
                        <button id="show-member_profile" type="button" class="mdl-button" style="width:100%; height:100%;">
                        <img src="<?=URL?>/images/user_profile_image/<?=$Companion->memberProfile.'.'.$Companion->memberProfileExt?>" style="width:60px; height:60px;">
                        <a><?=$Companion->memberNickname?></a>
                        </button>
                    </div>
                    <dialog id="member_profile" class="mdl-dialog" style="width:400px; height:270px; background:white;">
                        <div class="mdl-dialog__actions">
                            <button type="button" class="mdl-button close">X</button>
                        </div>
                        <div style="width:100%; height:77%; background:whitesmoke">
                            <div class="mdl-cell mdl-cell--5-col" style="height:90%; background:white; float:left;">
                                <img src="<?=URL?>/images/user_profile_image/<?=$Companion->memberProfile.'.'.$Companion->memberProfileExt?>" style="width:100%; height:100%;">
                            </div>
                            <div class="mdl-cell mdl-cell--7-col" style="height:90%; background:white; float:right;">
                                <div style="width:100%; height:30%; background:skyblue">
                                    <a style="font-size:30px; line-height:55px; margin-left:26%; "><?=$Companion->memberNickname?></a>
                                </div>
                                <div style="width:100%; height:70%; margin-top:15px;">
                                <a style="font-size:22px; margin-left:35%; line-height:30px;">E-mail</a><br/>
                                <a style="font-size:17px; margin-left:13%;"><?=$Companion->memberEmail?></a><br/>
                                <a style="font-size:22px; margin-left:29.5%; line-height:30px;">JoinDate</a><br/>
                                <a style="font-size:16px; margin-left:30%;"><?=$Companion->memberJoindate?></a>
                                </div>
                            </div>

                        </div>
                    </dialog>
                    <script>
                        var memberProfile = document.querySelector('#member_profile');
                        var showDialogButton2 = document.querySelector('#show-member_profile');
                        if (! memberProfile.showModal) {
                            dialogPolyfill.registerDialog(memberProfile);
                        }
                        showDialogButton2.addEventListener('click', function() {
                            memberProfile.showModal();
                        });
                        memberProfile.querySelector('.close').addEventListener('click', function() {
                            memberProfile.close();
                        });
                    </script>

                <?php } ?>
            </div>
            <div class="mdl-cell mdl-cell--6-col" style="height:46.3%; background:whitesmoke; float:right; ">
                <div style="width:100%; height:10%; background:#d1c4e9";><a style="font-size:23px; margin-left:33%; line-height:40px; float:left;">여행 코멘트</a></div>
                <div id="storyreply" style="width:100%; height:75%; background:white; overflow-y: auto; overflow-x: hidden;" >
                    <?php foreach($StoryReply as $Reply){ ?>
                    <div style="width:100%; height:19.2%; background:whitesmoke; border:solid black 1px; border-left:none;">

                        <div style="width:100%; height:70%; background:whitesmoke; float:right;">
                            <a style="font-size:20px; line-height:40px;"><?=$Reply->storyReplyContent?></a>
                        </div>
                        <div style="width:40%; height:30%; background:whitesmoke; float:left;">
                        </div>
                        <div style="width:60%; height:30%; background:antiquewhite; float:left;">
                            <div style="width:50%; height:100%; background:antiquewhite; float:left;">
                                <a><?=$Reply->memberNickname?></a>
                            </div>
                            <div style="width:50%; height:100%; background:antiquewhite; float:left;">
                                <a><?=$Reply->storyReplyRegistTime?></a>
                            </div>
                        </div>
                    </div>
                <?php } ?>
                </div>
                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" id="reply_info" style="width:100%; height:15%; background:whitesmoke ">
                    <input type="text" onkeydown="placeOnKeyDown(<?php echo $storyIdx ?>)" id="input_reply"  class="mdl-textfield__input">
                    <label class="mdl-textfield__label" for="sample3">Text...</label>
                </div>
            </div>
        </div>


        <div class="mdl-cell mdl-cell--7-col" style="height:89.8%;  background:white">

            <div class="mdl-cell mdl-cell--12-col" style="height:4%; background:white">
                <?php for($date =1; $date <= $StoryDate[0]->storyDate; $date++){ ?>
                <a href="/Storysummary/storySummary/<?=$StoryDate[0]->storyIdx ?>/<?=$date ?>/0" onclick="initMap(<?php $StoryPlaceInfo ?>)">
                <span class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent" >
                <b><?php echo $date ?>일차</b>
                </span></a>
               <?php } ?>
            </div>

            <div class="mdl-cell mdl-cell--12-col" style="height:50%;  background:whitesmoke">
                <div id="map_canvas" style="height:100%;"></div>
            </div>
            <div class="mdl-cell mdl-cell--5-col" style="height:41.3%; float:left; background:dodgerblue">
                <div style="width:100%; height:65%; background:whitesmoke;">
                    <img src="<?=URL?>/images/place_image/<?=$StoryPlaceInfo[$StoryPlaceIdx]->PlaceImageName.'.'.$StoryPlaceInfo[$StoryPlaceIdx]->PlaceImageExt?>" style="width:100%; height:100%;">
                </div>
                <div style="width:100%; height:35%; background:whitesmoke;">
                    <div style="width:100%; height:30%; background:skyblue;">
                        <a style="font-size:30px; line-height:30px;"><?=$StoryPlaceInfo[$StoryPlaceIdx]->placeName?></a>
                        <a><?=$StoryPlaceInfo[$StoryPlaceIdx]->placeNation?></a>
                    </div>
                    <div style="width:100%; height:55%; background:whitesmoke;">
                        <a style="font-size:20px;"><?=$StoryPlaceInfo[$StoryPlaceIdx]->placeExplain?></a>
                    </div>
                    <div style="width:50%; height:15%; background:whitesmoke; float:left;"></div>
                    <?php if($StoryPlaceInfo[$StoryPlaceIdx]->placeTel){ ?>
                    <div style="width:50%; height:15%; background:skyblue; float:right;">
                        <a style="font-size:18px;">Tel : <?=$StoryPlaceInfo[$StoryPlaceIdx]->placeTel?></a>
                    </div>
                    <?php } ?>
                </div>
            </div>
            <div class="mdl-cell mdl-cell--7-col" style="height:41.3%; float:right; background:whitesmoke; overflow-y: auto; overflow-x: hidden;">
                <?php $count = 0; ?>
                <?php foreach($StoryPlaceInfo as $placeInfo){?>
                <div style="width:100%; height:25%; background:whitesmoke; border:solid 1px black;">
                    <button  type="button" class="mdl-button" style="width:100%; height:100%; ">
                        <a href="/Storysummary/storySummary/<?=$StoryDate[0]->storyIdx ?>/<?=$StoryNowDate ?>/<?=$count ?>" style="font-size:30px; line-height:80px; text-decoration:none;">
                    <div style="width:100%; height:70%;">
                        <?=$placeInfo->placeName ?>
                    </div>
                    <div style="width:100%; height:30%;">
                        <a style="float:right;"><?=$placeInfo->storyPlaceEndTime?></a>
                        <a style="float:right;"><?=$placeInfo->storyPlaceStartTime?>~</a>
                    </div>
                        </a>
                    </button>
                </div>
                    <?php $count++; ?>
                <?php } ?>
            </div>
        </div>

    </div>

</div>


</div>




    <script>
        var slider = document.querySelector('#story_slider');
        var showDialogButton1 = document.querySelector('#show-story_slider');
        if (! slider.showModal) {
        dialogPolyfill.registerDialog(slider);
        }
        showDialogButton1.addEventListener('click', function() {
            slider.showModal();
        });
        slider.querySelector('.close').addEventListener('click', function() {
            slider.close();
        });
    </script>



<script>
    //        다중 지도 제어 함수
    function initmap()
    {
        var locations_summary = [
            <?php foreach ($StoryPlaceInfo as $PlaceInfo){?>
            ['<?=$PlaceInfo->placeName?>',<?=$PlaceInfo->placeLatitude; ?>, <?=$PlaceInfo->placeLongtitude?>],
            <?php } ?>
        ];
        var map = new google.maps.Map(document.getElementById('map_canvas'), {
            zoom: 13,
            center: new google.maps.LatLng(<?=$StoryPlaceInfo[$StoryPlaceIdx]->placeLatitude?>,<?=$StoryPlaceInfo[$StoryPlaceIdx]->placeLongtitude?>),
            mapTypeId: google.maps.MapTypeId.ROADMAP
        });

        var i, marker;

        for (i = 0; i < locations_summary.length; i++) {
            marker = new google.maps.Marker({
                position: new google.maps.LatLng(locations_summary[i][1], locations_summary[i][2]),
                map: map
            });

        }

    }
    //        장소 댓글 입력 ajax
    function placeOnKeyDown(place_id)
    {
        if(event.keyCode == 13)  {
            var input_reply = $('#input_reply').val();
            var input_reply = "'"+input_reply+"'";
            var place_id = "'"+place_id+"'";

            $.ajax({
                url: '/StorySummary/addPlaceReply',
                data: {user_id:1, reply_content:input_reply, place_id: place_id },
                dataType: 'jsonp',
                success: function (data) {
                    $('#placereply').empty();
                    for(var count = 0; count < data.result.length; count++) {
                        $('#placereply').append("<tr><td style='margin-left:500px; padding-right:30%;'>"+data.result[count].StoryPlaceReplyContent + "</td><td>"+data.result[count].MemberNickname+"</td><td>"+data.result[count].StoryPlaceReplyRegistTime+"</td></tr>");
                    }
                },
                error: function() {
                    alert( "Sorry, there was a problem!" );
                }
            });
        }
    }















</script>



<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDpN_JXSjsmYGfRTBmQLWkjz-F_XA3OrDo&callback=initmap"
        async defer></script>
