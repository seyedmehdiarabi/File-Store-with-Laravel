const app_url = document.querySelector('meta[name="app_url"]').getAttribute('content');
const site_url = app_url + '/';
let Toggle = false;
$("#sidebar_menu li").click(function () {

    if (!$(this).hasClass('active')) {
        $("#sidebar_menu li").removeClass('active');
        $(this).addClass('active');
        $('.child_menu').slideUp(500);
        $('#sidebar_menu .fa-angle-down').removeClass('fa-angle-down');
        $('.fa-angle-left', this).addClass('fa-angle-down');
        if (!Toggle) {
            $('.child_menu', this).slideDown(500);
        } else {
            $('.child_menu', this).show();
        }
    } else if (Toggle) {
        $('.child_menu').slideUp(500);
        $('.child_menu', this).show();
    }
});
$("#sidebarToggle").click(function () {
    if ($('.page_sidebar').hasClass('toggled')) {
        Toggle = false;
        $('.page_sidebar').removeClass('toggled');
        $("#sidebar_menu").find('.active .child_menu').css('display', 'block');
        $('.page_content').css('margin-right', '240px');
    } else {
        Toggle = true;
        $('.page_sidebar').addClass('toggled');
        $(".child_menu").hide();
        $('.page_content').css('margin-right', '50px');
    }
});

$(window).resize(function () {
    set_sidebar_width();
});

$(document).ready(function () {
    set_sidebar_width();
    const url = window.location.href.split('?')[0];
    if ($('#sidebar_menu a[href="' + url + '"]').parent().attr('class') != "child_menu") {
        $('#sidebar_menu a[href="' + url + '"]').parent().addClass('active');
    } else if (!$('#sidebar_menu a[href="' + url + '"]').parent().parent().hasClass('active')) {
        $('#sidebar_menu a[href="' + url + '"]').parent().parent().addClass('active');
        $('#sidebar_menu a[href="' + url + '"]').parent().parent().find('.child_menu').show();
    }
    $('#sidebar_menu a[href="' + url + '"]').parent().parent().find('a .fa-angle-left').addClass('fa-angle-down');

    $('#description').on('keyup', function () {
        var max_length = 150;
        const Description = $('#description');
        var characterCount = $('#character-count');
        var character_entered = Description.val().length;
        var character_remaining = max_length - character_entered;
        characterCount.html(character_remaining);
        if (max_length < character_entered) {
            characterCount.css('color', '#FF0000');
        } else {
            characterCount.css('color', '#A0A0A0');
        }
    })
});

set_sidebar_width = function () {
    const width = document.body.offsetWidth;
    if (width < 1920) {
        $('.page_sidebar').addClass('toggled');
        $('.page_content').css('margin-right', '50px');
        $(".child_menu").hide();
    } else {
        if (Toggle == false) {
            $('.page_sidebar').removeClass('toggled');
            $('.page_content').css('margin-right', '240px');
        }
    }
};

$('.check_box').click(function () {
    send_array_data = false;
    const $checkboxes = $('.panel_content input[type="checkbox"]');
    const count = $checkboxes.filter(':checked').length;
    if (count > 0) {
        $("#remove_items").removeClass('off');
        $("#restore_items").removeClass('off');
    } else {
        $("#remove_items").addClass('off');
        $("#restore_items").addClass('off');
    }
});
$('.item_form').click(function () {
    send_array_data = true;
    const $checkboxes = $('.panel_content input[type="checkbox"]');
    const count = $checkboxes.filter(':checked').length;
    if (count > 0) {
        const href = window.location.href.split('?');
        let action = href[0] + "/" + this.id;
        if (href.length == 2) {
            action = action + "?" + href[1];
        }
        $("#data_form").attr('action', action);
        $("#msg").text($(this).attr('msg'));
        $('.message_div').show();
    }
});
$("span[data-toggle='tooltip']").tooltip();
restore_row = function (url, t, message_text) {
    _method = 'post';
    delete_url = url;
    token = t;
    $("#msg").text(message_text);
    $('.message_div').show();
};
add_tag = function () {
    const tag_list = document.getElementById('tag_list').value;
    const t = tag_list.split('،');
    const keywords = document.getElementById('keywords').value;
    let count = document.getElementsByClassName('tag_div').length + 1;
    let string = keywords;
    for (let i = 0; i < t.length; i++) {
        if (t[i].trim() != '') {

            const check = check_has_tag(keywords, t[i]);
            if (check) {
                const r = "'" + t[i] + "'";
                string = string + "," + t[i];
                var tag = '<div class="tag_div" id="tag_div_' + count + '">' +
                    '<span  class="fa fa-remove" onclick="remove_tag(' + count + ',' + r + ')"></span>' + t[i] +
                    '</div>';
                count++;
                $("#tag_box").append(tag);
            }
        }
    }
    document.getElementById('keywords').value = string;
    document.getElementById('tag_list').value = '';
};
check_has_tag = function (tag_list, tag) {
    let result = true;
    tag_list = tag_list.toString().split(',');
    for (let i = 0; i < tag_list.length; i++) {
        if (tag_list[i].trim() == tag.toString().trim()) {
            result = false;
        }
    }
    return result;
}
remove_tag = function (id, text) {
    $("#tag_div_" + id).hide();
    const keywords = document.getElementById('keywords').value;
    const t1 = text + ",";
    const t2 = "," + text;
    let a = keywords.replace(t1, '');
    let b = a.replace(t2, '');
    document.getElementById('keywords').value = b;
};
add_price_variation_input = function () {
    const id = document.getElementsByClassName('item_input').length + 1;
    const html = '<div class="form-group item_groups" id="price_variation_-' + id + '">' +
        '<input type="text" class="form-control item_input" name="price_variation[-' + id + ']" placeholder="مقدار"> ' +
        '</div>';
    $("#price_variation_box").append(html);
}

number_format = function (num) {
    num = num.toString();
    let format = '';
    let counter = 0;
    for (let i = num.length - 1; i >= 0; i--) {
        format += num[i];
        counter++;
        if (counter == 3) {
            format += ",";
            counter = 0;
        }
    }
    return format.split('').reverse().join('');
};
replaceNumber = function (n) {
    n = n.toString();
    const find = ["0", "1", "2", "3", "4", "5", "6", "7", "8", "9"];
    const replace = ["۰", "۱", "۲", "۳", "۴", "۵", "۶", "۷", "۸", "۹"];
    for (let i = 0; i < find.length; i++) {
        n = n.replace(new RegExp(find[i], 'g'), replace[i]);
    }
    return n;
};
const month = [
    'فروردین',
    'اردیبهشت',
    'خرداد',
    'تیر',
    'مرداد',
    'شهریور',
    'مهر',
    'آبان',
    'آذر',
    'دی',
    'بهمن',
    'اسفند'
];
getMonthName = function (key) {
    if (month[key] != undefined) {
        return month[key];
    } else {
        return '';
    }
}
logout = function () {
    $("#logout_form").submit();
}
setActiveAdminMenu = function (route) {
    const url = site_url + 'admin/' + route;
    if ($('#sidebar_menu a[href="' + url + '"]').parent().attr('class') != "child_menu") {
        $('#sidebar_menu a[href="' + url + '"]').parent().addClass('active');
    } else if (!$('#sidebar_menu a[href="' + url + '"]').parent().parent().hasClass('active')) {
        $('#sidebar_menu a[href="' + url + '"]').parent().parent().addClass('active');
        if (Toggle == false) {
            $('#sidebar_menu a[href="' + url + '"]').parent().parent().find('.child_menu').show();
        }

    }
    $('#sidebar_menu a[href="' + url + '"]').parent().parent().find('a .fa-angle-left').addClass('fa-angle-down')

}
