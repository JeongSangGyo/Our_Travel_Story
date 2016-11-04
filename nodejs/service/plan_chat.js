var socket_ids = [];
var count = 0;

function loginUser(socket, nickname){
    socket_ids[nickname] = socket.id;
    count++;
}

io.sockets.on('connection', function( socket ) {
    //------------------------------ 채팅 이벤트 ------------------------------//

    socket.on('enter:plan', function( data ) {
        socket.join(data.story_num);
        socket.roomnum = data.roomname;
        console.log(io.sockets.adapter.rooms);
        io.sockets.in(data.story_num).emit('enter:plan', data);
    });

    //------------------------------ 채팅 이벤트 끝 ------------------------------//

    //------------------------------ 지도 공유 이벤트 ------------------------------//

    // 맵을 주도하는 리더 버튼 클릭 시
    socket.on('leader:map', function( data ) {
        socket.broadcast.to(data.story_num).emit('leader:map', data);
    });

    // 장소를 검색했을 때
    socket.on('search:map', function( data ) {
        socket.broadcast.to(data.story_idx).emit('search:map', data);
    });

    // 검색된 장소 중 특정 장소를 눌렀을 때
    socket.on('click:marker', function( data ) {
        console.log(data);
        socket.broadcast.to(data.story_idx).emit('click:marker', data);
    });

    // 지도 특정 부분 클릭 시
    socket.on('click:map', function( data ) {
        io.sockets.to(data.story_num).emit('click:map', data);
        console.log(io.sockets.adapter.rooms);
        console.log(data);
    });

    // 지도 드래그 한 후, 끝났을 때
    socket.on('dragend:map', function( data ) {
        io.sockets.to(data.story_num).emit('dragend:map', data);
    });

    // 지도의 줌을 변경시켰을 때
    socket.on('zoom_changed:map', function( data ) {
        io.sockets.in(data.story_num).emit('zoom_changed:map', data);
    });

    // 특정 메시지를 날렸을 때
    socket.on('send:message', function( data ) {
        io.sockets.to(data.story_num).emit('receive:message', data);
    });

    // 주도자가 Exit를 눌렀을 때
    socket.on('del_leader:map', function( data ) {
        io.sockets.to(data.story_num).emit('del_leader:map', data);
    });

    //------------------------------ 지도 공유 이벤트 끝 ------------------------------//

    //------------------------------ 여행 계획 공유 이벤트 ------------------------------//

    socket.on('reset:plan', function( data ) {
        console.log(data);
        socket.broadcast.to(data.story_idx).emit('reset:plan', data);
    });

    socket.on('drag:plan', function( data ) {
        socket.broadcast.to(data.story_idx).emit('drag:plan', data);
    });

    socket.on('resize:plan', function ( data ) {
        socket.broadcast.to(data.story_idx).emit('resize:plan', data);
    });

    //------------------------------ 여행 계획 공유 이벤트 끝 ------------------------------//

    //------------------------------ 장소 공유 이벤트 ------------------------------//

    socket.on('drop:place', function( data ) {
        socket.broadcast.to(data.story_idx).emit('drop:place', data);
    });

    socket.on('remove:place', function( data ) {
        socket.broadcast.to(data.story_idx).emit('remove:place', data);
    });

    //------------------------------ 장소 공유 이벤트 끝 ------------------------------//

    socket.on('message', function( data ) {
        io.sockets.to(data.roomname).emit('message_send', {'msg':data.msg, 'from':data.nickname});
    });

    socket.on('roomleave', function( data ) {
        io.sockets.to(data.roomname).emit('message_send_disconnect', {'msg':'', 'from':data.nickname});
        io.sockets.to(data.roomname).emit('room_research', null);
        socket.leave(data.roomname);
        console.log(io.sockets.adapter.rooms);
    });

    socket.on('disconnect', function() {
        io.sockets.emit('room_research', null);
    });

    // 특정 방 입장

    // 입장한 방 나가기
});