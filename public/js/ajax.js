var ajax = {};

ajax.result = {};
ajax.timer = [];

ajax.clearTimer = function (timerNum) {
    'use strict';

    window.clearInterval(ajax.timer[timerNum]);
};

ajax.setTimer = function (timerNum, interval, fn) {
    'use strict';

    ajax.timer[timerNum] = window.setInterval(fn, interval);
};

ajax.execute = function (postData, url, resultID, callback) {
    'use strict';

    var pauseResult;
    ajax.result[resultID] = {};

    // Perform ajax call using previously gathered data
    $.ajax({
        type: "POST",
        url: url,
        data: postData,
        dataType: "json"
    })
        .done(function (result) {
            // Get result and process the data to be viewed by user, pass id for validation
            ajax.result[resultID] = result;

            if (Object.keys(result).length !== 0) {
                pauseResult = window.setTimeout(callback, 500);
            }
        });

};
