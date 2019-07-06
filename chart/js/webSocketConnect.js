//  建立 webSocket 连接
$(function () {
    var url = "ws://47.102.154.146:8811";
    window.webSocket = new WebSocket(url);
    console.log(webSocket);

    //  连接成功的回调函数
    webSocket.onopen = function (evt) {
        console.log('connect success');
    };

    //  收到服务器数据后的回调函数
    webSocket.onmessage = function (evt) {
        console.log(evt.data);
        //  todo
        chart.append(evt.data);
    };

    //  连接关闭后的回调函数
    webSocket.onclose = function (evt) {
        console.log('connect closed');
    };

    //  报错时的回调函数
    webSocket.onerror = function (evt) {
        console.log('error:' + evt);
    };
});
