
<!--스토리 명-->
<h3 style="background-color:#263238; margin-bottom:0px; margin-top:0px; color:white;"><?php foreach ($Story as $data) { echo $data -> StoryName; } ?></h3>
<div style="padding-left:20px; background-color:#263238;">
    <div style="padding-top:10px; padding-bottom:10px;">
        <?php $data = 1; foreach($StoryPlace as $row){
            if($data == $row->StoryPlaceDateNumber){?>
                <a href="/Storydetail/storyDetail/<?=$row->StoryIdx ?>/<?=$row->StoryPlaceDateNumber ?>/<?=$row->StoryPlaceIdx?>"> <span class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent" >
                <b><?php echo $row->StoryPlaceDateNumber ?>일차</b>
            </span></a>
                <?php $data++; }} ?>
    </div>
</div>
<!--        --><?php
//            for($i=1;$i<=$StoryPlaceMax[0]->Max_dateNum;$i++){
//                echo $i."꺄악";
//            }
//        ?>
<!--    스토리 명 및 일차별 버튼-->


<div class="demo-layout mdl-layout mdl-js-layout mdl-layout--fixed-drawer mdl-layout--fixed-header" >
    <div class="demo-drawer mdl-layout__drawer mdl-color--blue-grey-900 mdl-color-text--blue-grey-50">
        <!-- 스토리 장소 명 -->
        <nav  class="demo-navigation mdl-navigation mdl-color--blue-grey-800" style="margin-top:0px; padding-top:0px; ">
            <?php foreach($StoryPlaceName as $place) {?>
                <a class="mdl-navigation__link" href="/Storydetail/storyDetail/<?php echo $row->StoryIdx; ?>/<?php echo $place->storyPlaceDateNumber; ?>/<?=$place->storyplaceIdx?>" style="color:white"><h5><b><?php echo $place ->placeName; ?></b></h5></a>
            <?php } ?>
        </nav>
        <!-- 스토리 장소 명 끝 -->
    </div>

    <main class="mdl-layout__content mdl-color--grey-100" style="background-color:#263238;">
        <div class="mdl-grid" style="background-color:#263238; padding-top:0px;">
            <div class="mdl-cell mdl-cell--4-col">
                <div class="location">
                    <!--장소 정보 시작-->
                    <div class="location_info">
                        <div class="demo-card-square mdl-card mdl-shadow--2dp" style="width:100%; height:100%;">
                                <div id="map" style="height:60%; width:100%:"></div>
                            <?php foreach($StoryPlaceInfo as $placeinfo){ ?>
                            <div style="width:100%; height:10%;" >
                                <h1 class="mdl-card__title-text" style="padding-left:15px;"><?php echo $placeinfo ->placeName; ?></h1>
                            </div>
                                    <a style="margin-left:18px; padding-top:0px;"><?php echo $placeinfo ->placeNation; ?><?php echo $placeinfo ->placeRegion; ?></a>
                                <div class="mdl-card__supporting-text">
                                <?php echo $placeinfo ->placeExplain; ?>
                            </div>
                            <div class="mdl-card__actions mdl-card--border"  style="margin-bottom:0.5%; margin-top:0%; padding-top:0%; ">
                                <div class="material-icons mdl-badge mdl-badge--overlap" data-badge="<?php echo $placeinfo ->placeFootprint; ?>"  style="margin-bottom:0%; margin-top:3%; padding-right:6%; padding-top:0%; float:right; width:28px; color:#3c90be; font-size:33px;">pets</div>
                              <?php }?>
                                <!-- 장소 좋아요 추가-->
                                <?php if($StoryPlaceGoodCount){ ?>
                                    <a><div class="material-icons mdl-badge mdl-badge--overlap" id="good_check" data-badge="<?php echo $StoryPlaceGoodCount[0]->StoryPlaceGoodCount; ?>" style="margin-bottom:0%; margin-top:3%; padding-top:0%; padding-right:6%; margin-left:1%; float:right; width:28px; color:hotpink; font-size:33px;">favorite</div></a>
                                <?php }else{ ?>
                                    <a><div class="material-icons mdl-badge mdl-badge--overlap" id="good_check" data-badge="<?php echo $StoryPlaceGoodCount[0]->StoryPlaceGoodCount; ?>" style="margin-bottom:0%; margin-top:3%; padding-top:0%; padding-right:6%; margin-left:1%; float:right; width:28px; color:gray; font-size:33px; ">favorite_border</div></a>
                                <?php } ?>
                                <script>
                                    var good = 0;
                                    // 장소별 좋아요
                                    $('#good_check').click(function () {
                                        if(good==1) {
                                            $.ajax({
                                                url: '/Storydetail/add_place_good',
                                                data: {
                                                    place_idx:"<?=$StoryPlaceInfo[0]->storyplaceIdx?>",
                                                    user_id:"1"
                                                },
                                                dataType: 'jsonp',
                                                success: function(data) {
                                                    $('#good_check').attr('data-badge',data.result[0].StoryPlaceGoodCount);
                                                    $('#good_check').attr('style',$('#good_check').attr('style').color('red').margin-bottom('0%').margin-top('3%').padding-top('0%').padding-right('6%').margin-left('1%').float('right').width('28px').font-size('33px'));
                                                },
                                                // 값이 넘어오지 않을 경우 실행될 메서드
                                                error: function() {
                                                    alert( "Sorry, there was a problem!" );
                                                }
                                            });
                                            good=0;
                                        }
                                        else{
                                            $.ajax({
                                                url: '/Storydetail/delete_place_good',
                                                data: {
                                                    place_idx:"<?=$StoryPlaceInfo[0]->storyplaceIdx?>",
                                                    user_id:"1"
                                                },
                                                dataType: 'jsonp',
                                                success: function(data) {
                                                    $('#good_check').attr('data-badge',data.result[0].StoryPlaceGoodCount);
                                                    $('#good_check').attr('style',$('#good_check').attr('style').color('gray').margin-bottom('0%').margin-top('3%').padding-top('0%').padding-right('6%').margin-left('1%').float('right').width('28px').font-size('33px'));

                                                    $('#bt').attr('style', $('#bt').attr('style').split('50px').join('100px'));
                                                },
                                                // 값이 넘어오지 않을 경우 실행될 메서드
                                                error: function() {
                                                    alert( "Sorry, there was a problem!" );
                                                }
                                            });
//                                            $('#bookmark_star').children('img').attr('src', '<?//=URL; ?>///images/bookmarkOff.png');
                                            good=1;
                                        }

                                    });
                                </script>

                                <!-- 장소 즐겨찾기 추가 -->
                                <?php if($StoryPlaceBookmarkCheck){ ?>
                                <a id="bookmark_star"><img src="<?=URL; ?>/images/bookmarkOn.png" style="margin-top:2.5%; float:right; padding-right:0.8%; "></a>
                                <?php }else{ ?>
                                <a id="bookmark_star"><img src="<?=URL; ?>/images/bookmarkOff.png" style="margin-top:2.5%; float:right; padding-right:0.8%; "></a>
                                <?php } ?>
                                <script>

                                    var star_check = 0;

                                        $('#bookmark_star').click(function () {
                                            if(star_check==0) {
                                                $.ajax({
                                                    url: '/Storydetail/add_my_place',
                                                    data: {
                                                        place_idx:"<?=$StoryPlaceInfo[0]->storyplaceIdx?>",
                                                        user_id:"1"
                                                    },
                                                    dataType: 'jsonp',
                                                    success: function(data) {

                                                    },
                                                    // 값이 넘어오지 않을 경우 실행될 메서드
                                                    error: function() {
                                                        alert( "Sorry, there was a problem!" );
                                                    }
                                                });
                                                $('#bookmark_star').children('img').attr('src', '<?=URL; ?>/images/bookmarkOn.png');
                                                star_check=1;
                                            }
                                            else{
                                                $.ajax({
                                                    url: '/Storydetail/delete_my_place',
                                                    data: {
                                                        place_idx:"<?=$StoryPlaceInfo[0]->storyplaceIdx?>",
                                                        user_id:"1"
                                                    },
                                                    dataType: 'jsonp',
                                                    success: function(data) {

                                                    },
                                                    // 값이 넘어오지 않을 경우 실행될 메서드
                                                    error: function() {
                                                        alert( "Sorry, there was a problem!" );
                                                    }
                                                });
                                                $('#bookmark_star').children('img').attr('src', '<?=URL; ?>/images/bookmarkOff.png');
                                                star_check=0;
                                            }

                                        });





                                </script>
                                <!-- 장소 즐겨찾기 추가 끝 -->
                            </div>
                        </div>

                    </div>
                    <!--  장소 정보 끝-->
                    <!--장소 댓글-->
                    <div class="location_comment" style="margin-top:15px;">
                            <div class="location_commentselect" style="width:100%; height:76.5%; background:white; overflow:auto">
                                <table id="placereply" class="mdl-data-table mdl-js-data-table mdl-data-table--selectable mdl-shadow--2dp" style="width:100%; height:100%;">



                                    <?php foreach($StoryReply as $placereply){ ?>
                                        <tr>
                                            <td style="margin-left:500px; padding-right:30%;"><?php echo $placereply ->StoryPlaceReplyContent; ?></td>
                                            <td><?php echo $placereply ->MemberNickname; ?></td>
                                            <td><?php echo $placereply ->StoryPlaceReplyRegistTime; ?></td>
                                        </tr>

                                    <?php } ?>
                                </table>
                            </div>


                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" id="reply_info" style=" margin-left:5%; width:90%; background:whitesmoke; ">
                                    <input type="text" onkeydown="placeOnKeyDown(<?php echo $StoryPlaceInfo[0] ->storyplaceIdx; ?>)" id="input_reply"  class="mdl-textfield__input">
                                <label class="mdl-textfield__label" for="sample3">Text...</label>
                            </div>
                        <script>

                        </script>


                    </div>
            <!--장소 댓글 끝-->
                </div>
            </div>

            <div class="mdl-cell mdl-cell--8-col" style="height: 720px; overflow-y: auto; overflow-x: hidden;">
                <!-- 특정 장소 별 사진 앨범 -->
                <div class="grid">
                    <?php foreach ($StoryPlaceImage as $PlaceImage){?>
                        <div class="grid-item place_photo_zone">
                            <div class="grid-item-content" >
                                <img  class="image-size-current" style="position: absolute;" src="<?=URL ?>/images/<?=$PlaceImage->StoryPlaceImageName ?>.<?=$PlaceImage->StoryPlaceImageExt ?>"
                                      onClick="specific_image_click(<?= $PlaceImage->StoryPlaceImageLatitude; ?>, <?= $PlaceImage->StoryPlaceImageLongitude; ?>, <?= $PlaceImage->StoryIdx; ?>, <?= $PlaceImage->StoryPlaceIdx; ?>, <?= $PlaceImage->StoryPlaceImageIdx; ?>, '<?= $PlaceImage->StoryPlaceImageName; ?>', '<?= $PlaceImage->StoryPlaceImageExt; ?>', this)"/>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>


        </div>
    </main>


<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" style="position: fixed; left: -1000px; height: -1000px;">
    <defs>
        <mask id="piemask" maskContentUnits="objectBoundingBox">
            <circle cx=0.5 cy=0.5 r=0.49 fill="white" />
            <circle cx=0.5 cy=0.5 r=0.40 fill="black" />
        </mask>
        <g id="piechart">
            <circle cx=0.5 cy=0.5 r=0.5 />
            <path d="M 0.5 0.5 0.5 0 A 0.5 0.5 0 0 1 0.95 0.28 z" stroke="none" fill="rgba(255, 255, 255, 0.75)" />
        </g>
    </defs>
</svg>
<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 500 250" style="position: fixed; left: -1000px; height: -1000px;">
    <defs>
        <g id="chart">
            <g id="Gridlines">
                <line fill="#888888" stroke="#888888" stroke-miterlimit="10" x1="0" y1="27.3" x2="468.3" y2="27.3" />
                <line fill="#888888" stroke="#888888" stroke-miterlimit="10" x1="0" y1="66.7" x2="468.3" y2="66.7" />
                <line fill="#888888" stroke="#888888" stroke-miterlimit="10" x1="0" y1="105.3" x2="468.3" y2="105.3" />
                <line fill="#888888" stroke="#888888" stroke-miterlimit="10" x1="0" y1="144.7" x2="468.3" y2="144.7" />
                <line fill="#888888" stroke="#888888" stroke-miterlimit="10" x1="0" y1="184.3" x2="468.3" y2="184.3" />
            </g>
            <g id="Numbers">
                <text transform="matrix(1 0 0 1 485 29.3333)" fill="#888888" font-family="'Roboto'" font-size="9">500</text>
                <text transform="matrix(1 0 0 1 485 69)" fill="#888888" font-family="'Roboto'" font-size="9">400</text>
                <text transform="matrix(1 0 0 1 485 109.3333)" fill="#888888" font-family="'Roboto'" font-size="9">300</text>
                <text transform="matrix(1 0 0 1 485 149)" fill="#888888" font-family="'Roboto'" font-size="9">200</text>
                <text transform="matrix(1 0 0 1 485 188.3333)" fill="#888888" font-family="'Roboto'" font-size="9">100</text>
                <text transform="matrix(1 0 0 1 0 249.0003)" fill="#888888" font-family="'Roboto'" font-size="9">1</text>
                <text transform="matrix(1 0 0 1 78 249.0003)" fill="#888888" font-family="'Roboto'" font-size="9">2</text>
                <text transform="matrix(1 0 0 1 154.6667 249.0003)" fill="#888888" font-family="'Roboto'" font-size="9">3</text>
                <text transform="matrix(1 0 0 1 232.1667 249.0003)" fill="#888888" font-family="'Roboto'" font-size="9">4</text>
                <text transform="matrix(1 0 0 1 309 249.0003)" fill="#888888" font-family="'Roboto'" font-size="9">5</text>
                <text transform="matrix(1 0 0 1 386.6667 249.0003)" fill="#888888" font-family="'Roboto'" font-size="9">6</text>
                <text transform="matrix(1 0 0 1 464.3333 249.0003)" fill="#888888" font-family="'Roboto'" font-size="9">7</text>
            </g>
            <g id="Layer_5">
                <polygon opacity="0.36" stroke-miterlimit="10" points="0,223.3 48,138.5 154.7,169 211,88.5
              294.5,80.5 380,165.2 437,75.5 469.5,223.3 	"/>
            </g>
            <g id="Layer_4">
                <polygon stroke-miterlimit="10" points="469.3,222.7 1,222.7 48.7,166.7 155.7,188.3 212,132.7
              296.7,128 380.7,184.3 436.7,125 	"/>
            </g>
        </g>
    </defs>
</svg>



<script>
    var $grid = $('.grid').masonry({
        itemSelector: '.grid-item',
        percentPosition: true,
        columnWidth: 10
    });

    $grid.on('click','.image-size-current', function() {
        $grid.masonry('layout');
    });

    function specific_image_click(latitude, longitude, story_idx, storyplace_idx, image_idx, image_name, image_ext, selected_image_element) {
        console.log(latitude, longitude, story_idx, storyplace_idx, image_idx, image_name, image_ext, selected_image_element);
        initMap(latitude, longitude);

        $(selected_image_element).parent().parent().toggleClass('gigante');
        $(selected_image_element).toggleClass('image-size-clicked');

        var image_full_name = image_name + '.' + image_ext;
        $(selected_image_element).toggleClass(image_full_name);

        if ( $(selected_image_element).parent().parent().hasClass('gigante') ) {
            // 이미지 클릭이 활성화 된 상태라면
                $(selected_image_element).after('<button class="mdl-button mdl-button--fab mdl-button--colored show_image_modify_dialog"><i class="material-icons">mode_edit</i></button>');
                console.log(selected_image_element);
        } else {
            // 이미지 클릭이 활성화 되지 않은 상태라면
            $(selected_image_element).siblings('button').remove();
        }

        var grid_element = $('.grid');
        var cnt = grid_element.children().length;

        for (var iCount = 0; iCount < cnt; iCount++) {
            if (grid_element.children().eq(iCount).find('img').hasClass('image-size-clicked')) {
                replyInfo(story_idx, image_idx);
                return;
            }
        }

        placeInfo(storyplace_idx);
    }

    function posicion(posicion) {
        var fontSize = $("#colorFuente").val();
        var fontSize = $('#tamanoFuente').val();
        var cuadro = document.getElementById("cuadro");
        cuadro.style.color = "#" + fontSize;
        cuadro.style.fontSize = fontSize + "px";
        cuadro.style.fontFamily = "Arial";
        cuadro.style.display = "table";
        cuadro.innerHTML = textoTextoPoner;
        cuadro.style.left = posicion.clientX + 1 + "px";
        cuadro.style.top = posicion.clientY - 25 + "px";

        this.onclick = function() {
            ctx.fillStyle = "#" + fontSize;
            ctx.font = "bold " + fontSize + " Arial";
            mostrar = "no";
            return;
        }
    }

    var cPushArray = new Array();
    var cStep = -1;

    $('.grid-item-content').on('click', '.show_image_modify_dialog', function() {
        console.log('cy');
        var temp_class = $(this).siblings('img').attr('class');
        var temp_class_arr = temp_class.split(' ');
        var imageUrl = "StoryDetailimages/" + temp_class_arr[2];

        var temp_element = '<table><tr><td></td><td>';
        temp_element += '<a id="test" href="" download><button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent">이미지 저장</button></a>';
        temp_element += '<button id="btnDelete" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent">전체지우기</button>';
        temp_element += '<button id="btnUndo" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent">지우기</button>';
        temp_element += '<button id="btnRedo" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent">되돌리기</button>';
        temp_element += '<div id="colorpicker"></div>';
        temp_element += '<input type="text" id="color" name="color" /><br />';
        temp_element += '<select id="brushThick" style="width: 60px"><option value="1">1</option><option value="3">3</option><option value="5">5</option>';
        temp_element += '<option value="7">7</option><option value="9" selected="selected">9</option><option value="11">11</option><option value="14">14</option>';
        temp_element += '<option value="20">20</option><option value="30">30</option><option value="45">45</option><option value="70">70</option></select>';
        temp_element += '</select>';
        temp_element += '<br /></td><td><canvas id="myCanvas" width="680" height="500" style="border:2px solid black"></canvas></td></tr></table>';

        showDialog({
            title: '이미지 수정',
            text: temp_element,
            contentStyle: {'max-width': '1000px', 'max-height': '800px'}
        });

        InitThis(imageUrl);
    });

    var ctx;
    var mousePressed = false;
    var lastX, lastY;
    var canvasWidth = 680;
    var canvasHeight = 500;
    var canvasLength = canvasWidth * canvasHeight;
    var text = "no";
    var toolTip = "no";
    var sizeSmall = 3;
    var sizeMedium = 8;
    var sizeBig = 12;
    var sizeHuge = 18;
    var curSize = sizeSmall;
    function InitThis(imageUrl) {
        var myImage = new Image(); // Create a new blank image.

        $('#colorpicker').farbtastic('#color');

        var div1 = document.getElementById('myCanvas');
        ctx = document.getElementById('myCanvas').getContext("2d");

        $('#myCanvas').mousedown(function(e) {
            mousePressed = true;
            Draw(e.pageX - $(this).offset().left, e.pageY - $(this).offset().top, false);
        });

        $('#myCanvas').mousemove(function(e) {
            if (mousePressed) {
                if (text == "no") {
                    Draw(e.pageX - $(this).offset().left, e.pageY - $(this).offset().top, true);
                }
                if (text == "si") {
                }
            }
        });

        $('#myCanvas').mouseup(function(e) {
            if (mousePressed) {
                mousePressed = false;
                cPush();
            }
        });

        $('#myCanvas').mouseleave(function(e) {
            if (mousePressed) {
                mousePressed = false;
                cPush();
            }
        });

        $("#btnUndo").on('click', function() {
            console.log('c');
            cUndo();
        });
        $("#btnRedo").on('click', function() {
            console.log('c');
            cRedo();
        });
        $("#btnDelete").on('click', function() {
            console.log('c');
            drawImage(imageUrl);
        });

        drawImage(imageUrl);
    }

    function drawImage(imageUrl) {
        var image = new Image();
        image.src = imageUrl;
        $(image).load(function() {
            ctx.drawImage(image, 0, 0, canvasWidth, canvasHeight);
            cPush();
        });
    }

    function Draw(x, y, isDown) {
        if (isDown) {
            ctx.beginPath();
            ctx.strokeStyle = curColor;
            ctx.lineWidth = $('#brushThick').val();
            ctx.lineJoin = "round";
            ctx.moveTo(lastX, lastY);
            ctx.lineTo(x, y);
            ctx.closePath();
            ctx.stroke();
        }
        lastX = x;
        lastY = y;
    }

    function cPush() {
        cStep++;
        if (cStep < cPushArray.length) {
            cPushArray.length = cStep;
        }
        var c = document.getElementById('myCanvas');
        cPushArray.push(c.toDataURL());
        var d = c.toDataURL("image/png", 1.0);
        $("#test").attr("href", d);

        document.title = cStep + ":" + cPushArray.length;
    }

    function cUndo() {
        if (cStep > 0) {
            cStep--;
            var canvasPic = new Image();
            canvasPic.src = cPushArray[cStep];
            canvasPic.onload = function() {
                ctx.drawImage(canvasPic, 0, 0);
            };
            document.title = cStep + ":" + cPushArray.length;
        }
    }

    function cRedo() {
        if (cStep < cPushArray.length - 1) {
            cStep++;
            var canvasPic = new Image();
            canvasPic.src = cPushArray[cStep];
            canvasPic.onload = function() {
                ctx.drawImage(canvasPic, 0, 0);
            };
            document.title = cStep + ":" + cPushArray.length;
        }
    }

    <!-- 구글 맵 -->
        function initMap(latitude, longitude) {
            if(!latitude){
                var myLatLng = {
                    lat:<?php echo $StoryPlaceInfo[0]->placeLatitude; ?>,
                    lng:<?php echo $StoryPlaceInfo[0]->placeLongtitude; ?>};
                var map = new google.maps.Map(document.getElementById('map'), {
                    zoom: 9,
                    center: myLatLng
                });

                var marker = new google.maps.Marker({
                    position: myLatLng,
                    map: map,
                    title: 'Hello World!'
                });
            }else{
                latitude = latitude * 1;
                longitude = longitude * 1;
                var myLatLng = {lat: latitude,lng: longitude};

                var map = new google.maps.Map(document.getElementById('map'), {
                    zoom: 14,
                    center: myLatLng
                });

                var marker = new google.maps.Marker({
                    position: myLatLng,
                    map: map,
                    title: 'Hello World!'
                });
            }
        }
    <!-- 구글 맵 끝 -->
    function imageOnKeyDown(image_id,story_id)
    {
        if(event.keyCode == 13)  {
            var input_reply = $('#input_reply').val();
            var input_reply = "'"+input_reply+"'";
            var image_id = "'"+image_id+"'";
            var story_id = "'"+story_id+"'";

            $.ajax({
                url: '/Storydetail/addImageReply',
                data: {user_id: "3", reply_content:input_reply, image_id: image_id, story_id: story_id },
                dataType: 'jsonp',
                success: function (data) {
                    console.log(data.result);
                    $('#placereply').empty();
                    for(var count = 0; count < data.result.length; count++) {
                        $('#placereply').append("<tr><td>"+data.result[count].StoryPlaceImageMemo + "</td><td>"+data.result[count].MemberNickname+"</td></tr>");
                    }
                },
                error: function() {
                    alert( "Sorry, there was a problem!" );
                }
            });
        }
    }

    function placeOnKeyDown(place_id)
    {
        if(event.keyCode == 13)  {
            var input_reply = $('#input_reply').val();
            var input_reply = "'"+input_reply+"'";
            var place_id = "'"+place_id+"'";
            $.ajax({
                url: '/Storydetail/addPlaceReply',
                data: {user_id: "3", reply_content:input_reply, place_id: place_id },
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


    function replyInfo(imageIdx,storyIdx){
        var imageIdx = "'"+imageIdx+"'";
        var storyIdx = "'"+storyIdx+"'";

        $('#reply_info').empty();
        $('#reply_info').append("<input type='text' onkeydown=imageOnKeyDown("+imageIdx+","+storyIdx+") id='input_reply'  class='mdl-textfield__input'>");
        $('#reply_info').append("<label class='mdl-textfield__label' for='sample3'>Text...</label>");
                $.ajax({
                    url: '/Storydetail/storyPlaceImageMemoSelect',
                    data: {
                        image_idx:imageIdx,
                        story_idx:storyIdx
                    },
                    dataType: 'jsonp',
                    success: function(data) {
                        if(data.result.length == 0) {
                            $('#placereply').empty();
                            $('#placereply').append('등록된 코멘트가 없습니다.');
                        } else {
                            $('#placereply').empty();
                            for(var count = 0; count < data.result.length; count++) {
                                $('#placereply').append("<tr><td>"+data.result[count].StoryPlaceImageMemo + "</td><td>"+data.result[count].MemberNickname+"</td></tr>");
                            }
                        }
                    },
                    // 값이 넘어오지 않을 경우 실행될 메서드
                    error: function() {
                    }
                });



    }
    function placeInfo(storyplaceidx){
        $.ajax({
            url: '/Storydetail/storyPlaceReplySelect',
            data: {
                storyplace_idx:storyplaceidx
            },
            dataType: 'jsonp',
            success: function(data) {
                $('#placereply').empty();
                for(var count = 0; count < data.result.length; count++) {
                    $('#placereply').append("<tr><td style='margin-left:500px; padding-right:30%;'>" + data.result[count].StoryPlaceReplyContent + "</td><td>" + data.result[count].MemberNickname + "</td><td>"+ data.result[count].StoryPlaceReplyRegistTime +"</td></tr>");
                }

                $('#reply_info').empty();
                $('#reply_info').append("<input type='text' onkeydown=placeOnKeyDown("+storyplaceidx+") id='input_reply'  class='mdl-textfield__input'>");
                $('#reply_info').append("<label class='mdl-textfield__label' for='sample3'>Text...</label>");

                initMap();
            },
            // 값이 넘어오지 않을 경우 실행될 메서드
            error: function() {
            }
        });
    }

</script>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDpN_JXSjsmYGfRTBmQLWkjz-F_XA3OrDo&callback=initMap"></script>
