window.chart = {};
chart.append = function (data) {
    var data = JSON.parse(data)
    if (data.type == 0) {
        $(".chatBox-content-demo").append("<div class=\"clearfloat\">" +
            "<div class=\"author-name\"><small class=\"chat-date\">" + data.time + "</small> </div> " +
            "<div class=\"right\"> <div class=\"chat-message\"> " + data.textContent + " </div> " +
            "<div class=\"chat-avatars\"><img src=\"img/icon01.png\" alt=\"头像\" /></div> </div> </div>");
    }else {
        $(".chatBox-content-demo").append("<div class=\"clearfloat\">" +
            "<div class=\"author-name\"><small class=\"chat-date\">" + data.time + "</small>\</div>" +
            " <div class=\"left\"><div class=\"chat-avatars\"><img src=\"img/icon01.png\" alt=\"头像\"/></div>" +
            "<div class=\"chat-message\">" + data.textContent + "</div></div></div>");
    }

    //发送后清空输入框
    $(".div-textarea").html("");
    //聊天框默认最底部
    $(document).ready(function () {
        $("#chatBox-content-demo").scrollTop($("#chatBox-content-demo")[0].scrollHeight);
    });
};

//  点击发送按钮
$("#chat-fasong").click(function () {
    var time = new Date();
    var textContent = $(".div-textarea").html().replace(/[\n\r]/g, '<br>');
    var data = {
        'textContent' : textContent,
        'time' : time.getFullYear() + '-' + time.getMonth() + '-' + time.getDate() + ' ' + time.getHours() + ':'
            + time.getMinutes() + ':' + time.getSeconds(),
    };
    //  发送消息
    webSocket.send(JSON.stringify(data));
});

