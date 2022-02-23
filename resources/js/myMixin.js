export default {
    methods: {
        replaceNumber: function (n) {
            if (n != undefined) {
                n = n.toString();
                const find = ["0", "1", "2", "3", "4", "5", "6", "7", "8", "9"];
                const replace = ["۰", "۱", "۲", "۳", "۴", "۵", "۶", "۷", "۸", "۹"];
                for (let i = 0; i < find.length; i++) {
                    n = n.replace(new RegExp(find[i], 'g'), replace[i]);
                }
                return n;
            }
        },
        check_mobile_number() {
            if (isNaN(this.mobile)) {
                return true;
            } else {
                if (this.mobile.toString().trim().length == 11) {
                    if (this.mobile.toString().charAt(0) == '0' && this.mobile.toString().charAt(1) == '9') {
                        return false;
                    } else {
                        return true;
                    }
                } else if (mthis.obile.toString().trim().length == 10) {
                    if (this.mobile.toString().charAt(0) == '9') {
                        return false;
                    } else {
                        return true;
                    }
                } else {
                    return true;
                }
            }
        },
        number_format: function (num) {
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
        },
        gregorian_to_jalali: function (gy, gm, gd) {
            var g_d_m, jy, jm, jd, gy2, days;
            g_d_m = [0, 31, 59, 90, 120, 151, 181, 212, 243, 273, 304, 334];
            if (gy > 1600) {
                jy = 979;
                gy -= 1600;
            } else {
                jy = 0;
                gy -= 621;
            }
            gy2 = (gm > 2) ? (gy + 1) : gy;
            days = (365 * gy) + (parseInt((gy2 + 3) / 4)) - (parseInt((gy2 + 99) / 100)) + (parseInt((gy2 + 399) / 400)) - 80 + gd + g_d_m[gm - 1];
            jy += 33 * (parseInt(days / 12053));
            days %= 12053;
            jy += 4 * (parseInt(days / 1461));
            days %= 1461;
            if (days > 365) {
                jy += parseInt((days - 1) / 365);
                days = (days - 1) % 365;
            }
            jm = (days < 186) ? 1 + parseInt(days / 31) : 7 + parseInt((days - 186) / 30);
            jd = 1 + ((days < 186) ? (days % 31) : ((days - 186) % 30));
            return [jy, jm, jd];
        },
        show_mobile_box: function () {
            this.$nextTick(function () {
                $('body').css('overflow-y', 'hidden');
                const width = $(window).width();
                const right = "-" + width + "px";
                $('.mobile_data_box').css({'right': right});
                setTimeout(function () {
                    $('.mobile_data_box').css('right', '0px');
                }, 50);
            });
        },
        check_has_off: function (product) {
            if (product.get_first_product_price != null) {
                const last_time = product.get_first_product_price.offers_last_time;
                const time = Math.floor(Date.now() / 1000);
                if (product.get_first_product_price.offers == 1 && (last_time - time) > 0) {
                    return (last_time - time);
                } else {
                    return false;
                }
            } else {
                return false;
            }
        },
        set_filter_event: function (el, page_url) {
            let data = $(el).attr('data');
            data = data.split('_');
            if ($('.check_box', el).hasClass('active')) {
                $('.check_box', el).removeClass('active');
                this.remove_url_query_string(data[0], data[2], page_url);
                this.remove_filter_tag(data[0], data[2], page_url);
            } else {
                $('.check_box', el).addClass('active');
                this.add_url_query_string(data[0], data[2], page_url);
                this.add_filter_tag(data, data[0], data[2], page_url);
            }
        },
        setPageUrl: function (url) {
            window.history.pushState('data', 'title', url);
        },
        remove_url_query_string: function (key, value, page_url) {
            let url = page_url == undefined ? window.location.href : page_url;
            let check = url.split(key);
            const params = url.split('?');
            let h = 0;
            if (params[1] != undefined) {
                if (params[1].indexOf('&') > -1) {
                    let vars = params[1].split('&');
                    for (let i in vars) {
                        let k = vars[i].split('=')[0];
                        let v = vars[i].split('=')[1];
                        let n = k.indexOf(key);
                        if (n > -1 && v != value) {
                            k = k.replace(key, '');
                            k = k.replace('[', '');
                            k = k.replace(']', '');
                            const new_string = key + "[" + h + "]=" + v;
                            const old_string = key + "[" + k + "]=" + v;
                            url = url.replace(old_string, new_string);
                            h++;
                        } else if (n > -1) {
                            url = url.replace('&' + k + "=" + v, '');
                            url = url.replace('?' + k + "=" + v, '');
                        }
                    }
                } else {
                    url = url.replace('?' + key + "[0]" + "=" + value, '');
                }
            }

            const url_params = url.split('?');
            if (url_params[1] == undefined) {
                url = url.replace('&', '?');
            }
            this.changed_url(url);
        },

        add_active_filter: function (k, v) {
            if (k.length > 1) {
                let data = "";
                let filter_key = k[0];
                if (k.length == 3) {
                    data = k[0] + "[" + k[1] + "_param_" + v;
                    data = "'" + data + "'";
                    filter_key = k[0] + "[" + k[1];
                } else {
                    data = k[0] + "_param_" + v;
                }
                $('li[data=' + data + '] .check_box').addClass('active');
                $('li[data=' + data + ']').parent().parent().slideDown();
                if ($('li[data=' + data + ']').parent().parent().parent().parent().find('.title_box').find('span').hasClass('fa-plus-circle')) {
                    $('li[data=' + data + ']').parent().parent().parent().parent().find('.title_box').find('span').removeClass('fa-plus-circle').addClass('fa-minus-circle')
                }
                if ($('li[data=' + data + ']').length == 1) {
                    this.add_filter_tag(data, filter_key, v);
                }
            } else {
                if (k == "has_product") {
                    this.set_enable_product_status_toggle();
                } else if (k == "has_ready_to_shipment") {
                    this.set_enable_send_status_toggle();
                }
            }
        },
        remove_all_filter: function (page_url) {
            let url = page_url == undefined ? window.location.href : page_url;
            url = url.split('?')[0];
            $('.selected_filter_item').remove();
            $("#filter_div").hide();
            $('.filter_box .list-inline li').find('.check_box').removeClass('active');
            if ($('#product_status .toggle-slide .toggle-off').hasClass('active')) {
                $("#product_status").click();
            }
            if ($('#send_status .toggle-slide .toggle-off').hasClass('active')) {
                $("#send_status").click();
            }
            if (this.noUiSlider) {
                this.noUiSlider.reset();
            }
            if (page_url == undefined) {
                this.setPageUrl(url);
                this.getProduct(1);
            } else {
                this.search_url = url;
            }
        },
        remove_url_params: function (key, value, page_url) {
            let params = new window.URLSearchParams(window.location.search);
            if (page_url != undefined) {
                let search_url_params = this.search_url.split('?');
                if (search_url_params[1] != undefined) {
                    search_url_params = '?' + search_url_params[1];
                    params = new window.URLSearchParams(search_url_params);
                }
            }
            let url = page_url == undefined ? window.location.href : page_url;
            if (params.get(key) != null) {
                value = encodeURIComponent(value);
                url = url.replace('&' + key + "=" + value, '');
                url = url.replace('?' + key + "=" + value, '');
                this.remove_filter_tag(key, value);

                const url_params = url.split('?');
                if (url_params[1] == undefined) {
                    url = url.replace('&', '?');
                }

                if (page_url == undefined) {
                    this.setPageUrl(url);
                    this.getProduct(1);
                } else {
                    this.search_url = url;
                }
            }
        },
        set_enable_send_status_toggle: function () {
            if (!$("#selected_filter_box").find('div').hasClass('send_status_filter')) {
                $("#filter_div").show();
                const html = '<div class="selected_filter_item send_status_filter">' +
                    '<span>فقط کالاهای آماده ارسال</span> <span class="fa fa-close"></span>' +
                    '</div>';
                $("#selected_filter_box").append(html);
            }
        },
        set_enable_product_status_toggle: function () {
            if (!$("#selected_filter_box").find('div').hasClass('product_status_filter')) {
                $("#filter_div").show();
                const html = '<div class="selected_filter_item product_status_filter">' +
                    '<span>فقط کالاهای موجود</span> <span class="fa fa-close"></span>' +
                    '</div>';
                $("#selected_filter_box").append(html);
            }
        },
        like: function (element, row_id, table_name, redirect) {
            if (this.send) {
                $("#loading_box").show();
                this.send = false;
                const url = this.$siteUrl + "/user/like";
                const formData = new FormData();
                formData.append('row_id', row_id);
                formData.append('table_name', table_name);
                this.axios.post(url, formData).then(response => {
                    this.send = true;
                    $("#loading_box").hide();
                    if (response.data == "add") {
                        element.like = element.like + 1;
                    } else if (response.data == "remove") {
                        element.like = element.like - 1;
                    }
                }).catch(error => {
                    this.send = true;
                    $("#loading_box").hide();
                    if (error.response.status == 401) {
                        if (redirect != undefined) {
                            this.$refs.loginBox.show_box();
                        } else {
                            $("#login_box").modal('show');
                        }
                    }
                });
            }
        },
        dislike: function (element, row_id, table_name, redirect) {
            if (this.send) {
                $("#loading_box").show();
                this.send = false;
                const url = this.$siteUrl + "/user/dislike";
                const formData = new FormData();
                formData.append('row_id', row_id);
                formData.append('table_name', table_name);
                this.axios.post(url, formData).then(response => {
                    this.send = true;
                    $("#loading_box").hide();
                    if (response.data == "add") {
                        element.dislike = element.dislike + 1;
                    } else if (response.data == "remove") {
                        element.dislike = element.dislike - 1;
                    }
                }).catch(error => {
                    this.send = true;
                    $("#loading_box").hide();
                    if (error.response.status == 401) {
                        if (redirect != undefined) {
                            this.$refs.loginBox.show_box();
                        } else {
                            $("#login_box").modal('show');
                        }
                    }
                });
            }
        },
        hide_transition_box: function () {
            this.show_box = false;
            $('body').css('overflow-y', 'auto');
        },
        get_send_price: function (send_price_type, price, type) {
            if (send_price_type == 0) {
                return this.getPrice(price);
            } else {
                return "متغیر (پس کرایه)،حداقل هزینه ارسال :" + this.getPrice(price);
            }
        },
        getPrice: function (price) {
            if (price == 0) {
                return "رایگان";
            } else {
                return this.replaceNumber(this.number_format(price)) + " تومان";
            }
        },
        get_send_order_amount: function () {
            let result = [];
            let normal_send_order_amount = this.CartProduct.first.normal_send_order_amount;
            let send_price_key = Object.keys(this.CartProduct.first.normal_send_order_amount);
            for (let i = 0; i < send_price_key.length; i++) {
                const key = send_price_key[i];
                const label = this.CartProduct.first.submission_type[key].label;
                const icon = this.CartProduct.first.submission_type[key].icon;
                let price = 'رایگان';
                if (!normal_send_order_amount[key] == 0) {
                    price = this.replaceNumber(this.number_format(normal_send_order_amount[key])) + " تومان";
                }
                let object = {icon: icon, label: label, price: price};
                result.push(object)
            }
            this.send_price_data = result;
        }
    }
}
