var express = require("express");
var http = require('http');
var bodyParser = require("body-parser");

var fs = require('fs');
var path = require('path');

var app = express();
var server = http.createServer(app);
app.use(express.static(__dirname + "/public"));
app.use(bodyParser.urlencoded({ extended: true }));
app.use(bodyParser.json());

var io = require('socket.io')(server);

var mysql = require('mysql');
var connection = mysql.createConnection({
    host: '127.0.0.1',
    port: 3306,
    user: 'root',
    password: '',
    database: 'ourtravelstory'
});

connection.connect(function(err) {
    if (err) {
        console.log('mysql connection error');
        console.log(err);
        throw err;
    }
});

var port = process.env.PORT || 3000;

var router = express.Router();

router.use(function(req, res, next) {
    console.log("Something is happening");
    next();
});

router.get('/', function(req, res) {
    res.send("Hello World");
});

router.route('/story')
    .get(function(req, res, next) {
        var query = connection.query("SELECT * FROM story", function(err, rows) {
            if (err) console.log(err);

            res.json(rows);
        });
    });

// 특정 회원의 스토리들
router.route('/myStory/:MemberIdx')
    .get(function(req, res, next) {
        var MemberIdx = req.params.MemberIdx;
        var query = connection.query(
            "SELECT *" +
            "FROM story s, companion c " +
            "WHERE s.StoryIdx = c.StoryIdx " +
            "AND c.MemberIdx = ? ", [MemberIdx], function(err, rows) {
                if (err) console.log(err);
                if (rows.length < 1) return res.send("Story Not found");

                res.json(rows);
            });

    });

// 특정 스토리
router.route('/story/:StoryIdx')
    .get(function(req, res, next) {
        var data = [req.params.StoryIdx];

        var query = connection.query("SELECT * FROM story WHERE StoryIdx = ? ", data, function(err, rows) {
            if (err) console.log(err);
            if (rows.length < 1) return res.send("Story Not found");

            res.json(rows);
        });
    });

// 특정 스토리의 일차수에 해당하는 사진들
router.route('/storyPlaceImages/:StoryIdx/:StoryPlaceDateNumber')
    .get(function(req, res, next) {
        var data = [req.params.StoryIdx, req.params.StoryPlaceDateNumber];

        var query = connection.query(
            "SELECT * " +
            "FROM storyplaceimage " +
            "WHERE StoryPlaceIdx in (SELECT StoryPlaceIdx FROM storyplace WHERE StoryIdx = ? AND StoryPlaceDateNumber = ?)", data, function(err, rows) {
                if (err) console.log(err);
                if (rows.length < 1) return res.send("Story Image Not found");

                res.json(rows);
            });
    });

// 특정 스토리에 계획된 장소 안에 해당하는 사진
router.route('/storyPlaceImage/:StoryPlaceImageIdx')
    .get(function(req, res, next) {

    })
    .delete(function(req, res, next) {

    });

// 특정 스토리에 계획된 장소 안에 해당하는 사진의 댓글
router.route('/storyPlaceImageMemo/:StoryPlaceImageIdx')
    .get(function(req, res, next) {

    })
    .delete(function(req, res, next) {

    });

app.use('/', router);

server.listen(port);
console.log("listen on port " + port);

function include( file_ ) {
    with( global ) {
        eval(fs.readFileSync(file_) + '');
    };
};

include(__dirname + '/service/plan_chat.js');
