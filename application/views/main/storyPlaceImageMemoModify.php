<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
        <script type="text/javascript" src="<?=URL; ?>/assets/js/plan/farbtastic.js"></script>

        <link rel="stylesheet" href="<?=URL; ?>/assets/css/public/farbtastic.css" type="text/css" />


        <script>
            //www.Ajax101.com

            $(document).ready(function() {
                var imageUrl = "http://167.88.115.33/images/<?php echo $StoryPlaceSelectImage[0]->StoryPlaceImageName; ?>.<?php echo $StoryPlaceSelectImage[0]->StoryPlaceImageExt; ?>";
                InitThis(imageUrl);
                var mousePressed = false;
                var lastX, lastY;
                var ctx;
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

                var myImage = new Image(); // Create a new blank image.


                $('#colorpicker').farbtastic('#color');

                var div1 = document.getElementById('myCanvas');

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

                $("#btnDark").click(function() {
                    cDark(imageUrl);
                });
                $("#btnUndo").click(function() {
                    cUndo();
                });
                $("#btnRedo").click(function() {
                    cRedo();
                });
                $("#btnDelete").click(function() {
                    drawImage(imageUrl);
                });

                function InitThis(imageUrl) {
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

                var cPushArray = new Array();
                var cStep = -1;

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
                        }
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
                        }
                        document.title = cStep + ":" + cPushArray.length;
                    }
                }
                function cDark(imageUrl){

                        // Get the canvas element.
                        canvas = document.getElementById("myCanvas");

                        // Make sure you got it.
                        if (canvas.getContext) {

                            // Specify 2d canvas type.
                            ctx = canvas.getContext("2d");

                            // When the image is loaded, draw it.
                            myImage.onload = function() {
                                // Load the image into the context.
                                ctx.drawImage(myImage, 0, 0,680,500);

                                // Get and modify the image data.
                                getColorData();

                                // Put the modified image back on the canvas.
                                putColorData();
                            }

                            // Define the source of the image.
                            // This file must be on your machine in the same folder as this web page.
                            myImage.src = imageUrl;
                        }

                    function getColorData() {

                        myImage = ctx.getImageData(0, 0, 680, 500);

                        // Loop through data.
                        for (var i = 0; i < canvasLength * 4; i += 4) {

                            // First bytes are red bytes.
                            // Remove all red.
                            myImage.data[i] = 0;

                            // Second bytes are green bytes.
                            // Third bytes are blue bytes.
                            // Fourth bytes are alpha bytes
                        }
                    }

                    function putColorData() {

                        ctx.putImageData(myImage, 0, 0);
                    }


                }

            });

        </script>
        <!-- Style -->

        <style>

            .farbtastic .wheel {
                background: url(<?=URL; ?>/images/sketch/wheel.png) no-repeat;
                width: 195px;
                height: 195px;
            }
            .farbtastic .overlay {
                background: url(<?=URL; ?>/images/sketch/mask.png) no-repeat;
            }
            .farbtastic .marker {
                width: 17px;
                height: 17px;
                margin: -8px 0 0 -8px;
                overflow: hidden;
                background: url(<?=URL; ?>/images/sketch/marker.png) no-repeat;
            }
        </style>


</head>
<body>

<br>
<table>
    <tr>
        <td></td><td>
            <a id="test" href="" download>
            <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect">
                이미지 저장
            </button>
                </a>
            <button id="btnDelete" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect">
                전체 지우기
            </button>
            </a>
            <button id="btnUndo" class="btn btn-info">
                지우기
            </button>
            <button id="btnRedo" class="btn btn-info">
                되돌리기
            </button>
            <button id="btnDark" class="btn btn-info">
                흑백
            </button>
        </td>

    </tr>
    <tr>
        <td>
            <center>
                <div id="colorpicker"></div>
            </center>
            <center>
                <input type="text" id="color" name="color" />
            </center>
            <br/>
            <center>Thickness:
                <select id="brushThick" style="width: 60px">
                    <option value="1">1</option>
                    <option value="3">3</option>
                    <option value="5">5</option>
                    <option value="7">7</option>
                    <option value="9" selected="selected">9</option>
                    <option value="11">11</option>
                    <option value="14">14</option>
                    <option value="20">20</option>
                    <option value="30">30</option>
                    <option value="45">45</option>
                    <option value="70">70</option>
                </select></center>
            <br/>
        </td>
        <td><canvas id="myCanvas" width="680" height="500" style="border:2px solid black"></canvas></td>
    </tr>
</table>
</body>
</html>
