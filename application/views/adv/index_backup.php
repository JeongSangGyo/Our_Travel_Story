<h2>　Adv Setting Page</h2>

<script>
    $(document).ready(function() {
        var newlatlng, circle;
        var radiusValue = null;
        var latlng = new google.maps.LatLng(37.5640, 126.9751);
        var myOptions = {
            zoom : 15,
            center : latlng,
            mapTypeId : google.maps.MapTypeId.ROADMAP
        };
        var map = new google.maps.Map(document.getElementById("map"), myOptions);
        var marker = new google.maps.Marker({
            position : latlng,
            map : map
        });

        var geocoder = new google.maps.Geocoder();

        // 반경 설정
        $('input[name=radius]').click(function() {
            radiusValue = parseInt($('input[name=radius]:checked').val());
        });

        // 지도상에 위치를 클릭 시 위치정보와 마커, 반경표시
        google.maps.event.addListener(map, 'click', function(event) {
            if(radiusValue == null) {
                alert('반경을 설정해주세요!');
            }
            var location = event.latLng;
            geocoder.geocode({
                'latLng' : location
            }, function(results, status){
                if( status == google.maps.GeocoderStatus.OK ) {
                    $('#address').val(results[0].formatted_address);
                    $('#lat').val(results[0].geometry.location.lat());
                    $('#lng').val(results[0].geometry.location.lng());

                    newlatlng = new google.maps.LatLng(results[0].geometry.location.lat(), results[0].geometry.location.lng());

                    // 반경 표시
                    if( !circle ) {
                        circle = new google.maps.Circle({
                            center: newlatlng,
                            radius: radiusValue,
                            strokeColor: "#0000FF",
                            strokeOpacity: 0.8,
                            strokeWeight: 2,
                            fillColor: "#0000FF",
                            fillOpacity: 0.2
                        });
                    } else {
                        circle.setMap(null);
                        circle = new google.maps.Circle({
                            center: newlatlng,
                            radius: radiusValue,
                            strokeColor: "#0000FF",
                            strokeOpacity: 0.8,
                            strokeWeight: 2,
                            fillColor: "#0000FF",
                            fillOpacity: 0.2
                        });
                    }

                    circle.setMap(map);
                }
                else {
                    alert("Geocoder failed due to: " + status);
                }
            });
            if( !marker ) {
                marker = new google.maps.Marker({
                    position : location,
                    map : map
                });
            }
            else {
                marker.setMap(null);
                marker = new google.maps.Marker({
                    position : location,
                    map : map
                });
            }
            /*map.setCenter(location);*/
        });

        // 주소 text창에 주소 입력 후 focusout이 되면 입력된 주소로 위치변경
        $("#address").focusout(function(){
            var address = $(this).val();
            if( address != '') {
                geocoder.geocode({
                    'address' : address
                }, function(results, status){
                    if( status == google.maps.GeocoderStatus.OK ) {
                        $('#lat').html(results[0].geometry.location.lat());
                        $('#lng').html(results[0].geometry.location.lng());
                        map.setCenter(results[0].geometry.location);
                        if( !marker ) {
                            marker = new google.maps.Marker({
                                position : results[0].geometry.location,
                                map : map
                            });
                        }
                        else {
                            marker.setMap(null);
                            marker = new google.maps.Marker({
                                position :  results[0].geometry.location,
                                map : map
                            });
                        }
                    }
                    else {
                        alert("Geocoder failed due to: " + status);
                    }
                });
            }
        });


        //google.maps.event.addDomListener(window, 'load', initialize);
    });

</script>


<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js">
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBDjE8Q53z6sD0dqbuy5sLeEVKzipdaJD4"></script>
<div id="map" style="width:90%; min-height:50%; margin-left:5%"></div><p></p>
<div style="margin-left:5%">
    <form action="/adv/add_adv" method="post" enctype="multipart/form-data">
        <table>
            <tr>
                <td width="10%">緯度 : </td><td><input type="text" name="adv_lat" id="lat" value="" readonly /></td>
            </tr>
            <tr>
                <td>軽度 : </td><td><input type="text" name="adv_lng" id="lng" value="" readonly /></td>
            </tr>
            <tr>
                <td>住所 : </td><td><input type="text" name="adv_address" id="address" value="" size="50"/></td>
            </tr>
        </table>
        <h3>* 半径設定</h3>
        <input type="radio" name="radius" value="100">100m<br>
        <input type="radio" name="radius" value="200">200m<br>
        <input type="radio" name="radius" value="300">300m<p></p>
        <input type="hidden" name="memberidx" value="<?=$_SESSION['MemberIdx']?>"><br>
        <h3>* イメージ設定</h3>
        <img src="" id="adv_image">
        <input type="file" name="adv_image"><p></p>
        <h4>* コメント</h4>
        <textarea name="adv_comment" style="min-width:50%; min-height:20%; resize:none;"></textarea><p></p>
        <!--<input type="date" name="adv_date" id="datePicker"><p></p>-->
        <input type="submit" value="ADD_adv" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent">
        <!--<button onclick="ADD_adv()">New Adv</button>-->
    </form>
</div>

<!-- <script>
    document.getElementById('datePicker').valueAsDate = new Date();
</script> -->
<!--<div class="adv" style="margin-top:10px; margin-left:5%">-->


<!--<script>
    function ADD_adv() {
        $.ajax({
            url:'/admin/add_adv',
            data: {

            },
            dataType: 'json',
            success: function(data) {

            }
        });
    }
</script>-->
