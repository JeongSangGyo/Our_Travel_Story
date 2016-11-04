<script>
    var message = '<?=$alert ?>';
    switch(message) {
        case "login":
            window.alert('ログインできました！');
            location.replace('/Main');
            break;
        case "join":
            window.alert('会員登録が完了されました！');
            window.alert('登録したメールに認証を送りました！');
            history.back();
            break;
        case "logout":
            window.alert('ログアウトされました！');
            history.back();
            break;
        case "logout_init":
            window.alert('ログアウトされました！');
            location.replace('/Main');
            break;
        case "confirm":
            window.alert('인증되었습니다!!');
            window.close();
            break;
        case "update":
            window.alert('성공적으로 변경되었습니다!');
            history.back();
            break;
        case "share_on":
            window.alert('공유설정 되었습니다.');
            history.back();
            break;
        case "share_off":
            window.alert('공유설정이 취소되었습니다.');
            history.back();
            break;
        case "success":
            window.alert('완료되었습니다.');
            location.replace('/main');
            break;
        case "error":
            window.alert('エラーが発生しました！');
            history.back();
            break;
        default:
            window.alert('디폴트가 발생하였습니다.');
            history.back();
            break;
    }
</script>
