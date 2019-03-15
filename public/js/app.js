function getToday(){
    var today = new Date();
    var intMonth = today.getMonth() + 1;
    var month = (intMonth < 10) ? "0" + intMonth : intMonth;
    var date = today.getFullYear() + "-" + month + "-" + today.getDate();
    return date;
}

function updateBookCount(){
    $.get(
        "../book/getMyBookCount",
        {},
        function (data) {
            var tmp = JSON.parse(data);
            $('#wish_menu').html(tmp.stats[0] + '/' + tmp.book_count);
            $('#do_menu').html(tmp.stats[1] + '/' + tmp.book_count);
            $('#collect_menu').html(tmp.stats[2] + '/' + tmp.book_count);
        });
}

function goPage(pageNum, totalPage) {
    if (/^\d+$/.test(pageNum) == false) {
        return;
    }
    if (pageNum < 1) {
        pageNum = 1;
    }
    if (pageNum > totalPage) {
        if (totalPage > 0) {
            pageNum = totalPage;
        } else {
            pageNum = 1;
        }
    }
    $("#p").val(pageNum);
    $("#pageForm").submit();
}

function showPageNumber(currentPage, totalPage) {
    var pageHtml = "";
    var maxNum_new = currentPage > 4 ? 6 : 7 - currentPage;//最大显示页码数
    var discnt = 1;
    for ( var i = 3; i > 0; i--) {
        var start = currentPage - i;
        if (currentPage > i) {
            pageHtml = pageHtml + '<li><a href="javascript:goPage('
            + (start) + ',' + totalPage + ')">' + (start)
            + '</a></li>';
            discnt++;
        }
    }
    pageHtml = pageHtml + '<li class="active"><a href="javascript:void(0)">'+ currentPage + '</a></li>';
    for ( var i = 1; i < maxNum_new; i++) {
        var start = currentPage + i;
        if (start <= totalPage && discnt < 7) {
            pageHtml = pageHtml + '<li><a href="javascript:goPage('
            + (start) + ','+ totalPage + ')">' + (start)
            + '</a></li>';
            discnt++;
        } else {
            break;
        }
    }
    $(pageHtml).insertBefore("#last_page");
}

/**
 * 书目类别，二级联动b
 * @param bid
 */
function showTids(bid){
    var sid = $("#sid_" + bid).val();
    var tid = $("#tid_val_" + bid).val();
    console.log("tid_val#" + tid);
    if(sid == ''){
        return;
    }

    $.get(
        "../api/book/action/getTidBySidInJson",
        {
            sid: sid
        },
        function (data) {
            var tmp = JSON.parse(data);
            if (tmp.data.length > 0) {
                var inputs = '';
                $.each(tmp.data, function (idx, obj) {

                    if(obj.id == tid){
                        inputs += '<option value="' + obj.id + '" selected="selected">' + obj.name + '</option>';
                    } else {
                        inputs += '<option value="' + obj.id + '">' + obj.name + '</option>';
                    }

                });
                $("#tid_" + bid).html(inputs);
            } else {
                console.log("error tids");
            }
        });
}