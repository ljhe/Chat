window.chart = {};
chart.append = function (data) {
    var data = JSON.parse(data);
    if (data.type === 1) {
        chart.addMessage(data.status, data.time, data.textContent, data.type);
        //发送后清空输入框
        $(".div-textarea").html("");
    }else if (data.type === 2) {
        chart.addMessage(data.status, data.time, data.textContent, data.type);
    }else if (data.type === 3) {
        chart.addMessage(data.status, data.time, data.textContent, data.type);
    }

    //聊天框默认最底部
    $(document).ready(function () {
        $("#chatBox-content-demo").scrollTop($("#chatBox-content-demo")[0].scrollHeight);
    });
};

//  聊天框添加新的消息
chart.addMessage = function (status, time, textContent, type) {
    if (status === 0) {
        if (type === 3) {
            textContent = "<img src=" + textContent + ">";
        }
        $(".chatBox-content-demo").append("<div class=\"clearfloat\">" +
            "<div class=\"author-name\"><small class=\"chat-date\">" + time + "</small> </div> " +
            "<div class=\"right\"> <div class=\"chat-message\"> " + textContent + " </div> " +
            "<div class=\"chat-avatars\"><img src=\"img/icon01.png\" alt=\"头像\" /></div> </div> </div>");
    }else {
        if (type === 3) {
            textContent = "<img src=" + textContent + ">";
        }
        $(".chatBox-content-demo").append("<div class=\"clearfloat\">" +
            "<div class=\"author-name\"><small class=\"chat-date\">" + time + "</small>\</div>" +
            " <div class=\"left\"><div class=\"chat-avatars\"><img src=\"img/icon01.png\" alt=\"头像\"/></div>" +
            "<div class=\"chat-message\">" + textContent + "</div></div></div>");
    }

};

//  点击发送按钮
$("#chat-fasong").click(function () {
    var textContent = $(".div-textarea").html().replace(/[\n\r]/g, '<br>');
    if (textContent !== '') {
        var data = {
            'textContent' : textContent,
            'time' : '',
            'type' : 1,   //  发送文本消息
        };
        //  发送消息
        webSocket.send(JSON.stringify(data));
    }
});

//      发送表情
$("#chat-biaoqing").click(function () {
    $(".biaoqing-photo").toggle();
});
$(document).click(function () {
    $(".biaoqing-photo").css("display", "none");
});
$("#chat-biaoqing").click(function (event) {
    event.stopPropagation();//阻止事件
});

$(".emoji-picker-image").each(function () {
    $(this).click(function () {
        var bq = $(this).parent().html();
        console.log(bq);
        var data = {
            'textContent' : bq,
            'time' : '',
            'type' : 2,   //  发送表情
        };
        //  发送消息
        webSocket.send(JSON.stringify(data));
    })
});


//      发送图片
function selectImg(pic) {
    if (!pic.files || !pic.files[0]) {
        return;
    }
    var reader = new FileReader();
    reader.onload = function (evt) {
        console.log(evt);
        var images = evt.target.result;
        var data = {
            'textContent' : images,
            'time' : '',
            'type' : 3,   //  发送图片
        };
        //  发送消息
        webSocket.send(JSON.stringify(data));
    };
    reader.readAsDataURL(pic.files[0]);
}
