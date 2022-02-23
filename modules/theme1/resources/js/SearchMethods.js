export default {
    methods: {
        check_search_params: function (page_url) {
            let url = page_url === undefined ? window.location.href : page_url;
            const params = url.split('?');
            this.selected_filter = [];
            this.checkbox = {};
            if (params[1] !== undefined) {
                if (params[1].indexOf('&') > -1) {
                    let vars = params[1].split('&');
                    for (let i in vars) {
                        let k = vars[i].split('=')[0];
                        let v = vars[i].split('=')[1];
                        k = k.split('[');
                        this.add_active_filter(k, v);
                    }
                } else {
                    let k = params[1].split('=')[0];
                    let v = params[1].split('=')[1];
                    k = k.split('[');
                    this.add_active_filter(k, v);
                }
            }
        },
        set_product_sort: function () {
            let params = new window.URLSearchParams(window.location.search);
            let url = window.location.href;
            if (params.get("sortby") != null) {
                const sortby = parseInt(params.get("sortby"));
                if (sortby >= 21 && sortby <= 25) {
                    this.sort = sortby;
                }
            }
        },
        get_request_url: function (url, page) {
            const url_params = url.split('?');
            if (url_params[1] == undefined) {
                url = url + "?page=" + page;
            } else {
                url = url + "&page=" + page;
            }
            return url
        },
        setRangSlider: function (price) {
            const app = this;
            var slider = document.querySelector('.price_rang_slider');
            if (this.noUiSlider == null) {
                if (parseInt(price) > 0) {
                    this.noUiSlider = noUiSlider.create(slider, {
                        start: [0, price],
                        connect: true,
                        direction: 'rtl',
                        range: {
                            'min': 0,
                            'max': price
                        },
                        format: {
                            from: function (value) {
                                return parseInt(value);
                            },
                            to: function (value) {
                                return parseInt(value);
                            },
                        }
                    });
                }
            }

            if (slider.noUiSlider != undefined) {
                slider.noUiSlider.on('update', function (values, handle) {
                    app.min_price = values[0];
                    app.max_price = values[1];
                    $("#min_price").text(app.replaceNumber(app.number_format(values[0])));
                    $("#max_price").text(app.replaceNumber(app.number_format(values[1])));
                });

                let search = new window.URLSearchParams(window.location.search);
                const min = search.get('price[min]') != null ? parseInt(search.get('price[min]')) : 0;
                if (search.get('price[max]') != null) {
                    slider.noUiSlider.updateOptions({
                        start: [min, parseInt(search.get('price[max]'))]
                    })
                }

                if (search.get('price[min]') != null && search.get('price[max]') == null) {
                    slider.noUiSlider.updateOptions({
                        start: [parseInt(search.get('price[min]')), slider.noUiSlider.get()[1]]
                    })
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
        getDiscountValue: function (price1, price2) {
            let a = (price2 / price1) * 100;
            a = 100 - a;
            a = Math.round(a);
            return a;
        },
        setPageUrl: function (url, sendRequest) {
            if (this.search_url !== undefined) {
                this.search_url = url;
                if (sendRequest) {
                    window.history.pushState('data', 'title', url);
                    this.getProduct(url);
                }
            } else {
                window.history.pushState('data', 'title', url);
                if (sendRequest === undefined) {
                    this.getProduct(url);
                }
            }
        },
        getProduct: function (url) {
            const self = this;
            this.sendProductRequest = true;
            this.clientRequest = true;
            if (this.mobileApp === undefined) {
                this.$vuetify.goTo(0);
            }
            this.check_search_params();

            const request_url = window.location.href.replace(this.$siteUrl, this.$siteUrl + '/getProduct');
            this.axios.get(request_url).then(response => {
                this.sendProductRequest = false;
                if (response.data['products'] !== undefined) {
                    if (this.mobileApp !== undefined) {
                        response.data['products'].data.forEach(function (item) {
                            self.products.data.push(item);
                        });
                        if (response.data['products'].data === 0) {
                            self.getServerData = 'no';
                        }
                    } else {
                        this.products = response.data['products'];
                    }
                }
            });
        },
        paginate: function (page) {
            this.add_url_param('page', page);
        },
        add_url_param: function (key, value, sendRequest) {
            let params = new window.URLSearchParams(window.location.search);
            let url = window.location.href;
            if (params.get(key) != null) {
                let old_param = key + "=" + encodeURIComponent(params.get(key));
                let new_param = key + "=" + value;
                url = url.replace(old_param, new_param);
            } else {
                const url_params = url.split('?');
                if (url_params[1] === undefined) {
                    url = url + "?" + key + "=" + value;
                } else {
                    url = url + "&" + key + "=" + value;
                }
            }

            this.setPageUrl(url, sendRequest);
        },
        getFilterList: function () {
            const url = this.$siteUrl + '/api/shop/category/' + this.cat_id + '/filters';
            this.axios.get(url).then(response => {
                this.sendRequest = false;
                this.filterList = response.data.original;
                const self = this;
                this.$nextTick(function () {
                    setTimeout(function () {
                        self.check_search_params();
                    }, 100);
                });
            }).catch(error => {
                this.sendRequest = false;
            });
        },
        changeStatus: function (id) {
            if (this.boxStatus[id] === undefined) {
                this.boxStatus[id] = true;
            } else {
                this.boxStatus[id] = !this.boxStatus[id];
            }
            this.$forceUpdate();
        },
        addFilter: function (paramName, id, keyparam) {
            if (this.checkbox[keyparam + id] === true) {
                this.add_url_query_string(paramName, id);
            } else {
                this.remove_url_query_string(paramName, id);
            }
        },
        add_url_query_string: function add_url_query_string(key, value) {
            let url = window.location.href;
            let check = url.split(key);
            const n = check.length - 1;
            const url_params = url.split('?');

            if (url_params[1] === undefined) {
                url = url + "?" + key + "[" + n + "]=" + value;
            } else {
                url = url + "&" + key + "[" + n + "]=" + value;
            }

            this.setPageUrl(url);
        },
        remove_url_query_string: function (key, value, page_url, sendRequest) {
            let url = page_url === undefined ? window.location.href : page_url;
            let check = url.split(key);
            const params = url.split('?');
            let h = 0;
            if (params[1] !== undefined) {
                if (params[1].indexOf('&') > -1) {
                    let vars = params[1].split('&');
                    for (let i in vars) {
                        let k = vars[i].split('=')[0];
                        let v = vars[i].split('=')[1];
                        let n = k.indexOf(key);
                        if (n > -1 && v !== value) {
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
            if (url_params[1] === undefined) {
                url = url.replace('&', '?');
            }
            this.setPageUrl(url, sendRequest);
        },
        set_sort: function (num) {
            this.sort = num;
            this.add_url_param('sortby', num);
        },
        priceFilter: function () {
            this.add_url_param('price[min]', this.priceRange[0], false);
            this.add_url_param('price[max]', this.priceRange[1]);
        },
        setHasProduct: function () {
            if (this.has_product) {
                this.add_url_param('has_product', 1);
            } else {
                this.remove_url_params('has_product', 1);
            }
        },
        setHasReadyToShipment: function () {
            if (this.has_ready_to_shipment) {
                this.add_url_param('has_ready_to_shipment', 1);
            } else {
                this.remove_url_params('has_ready_to_shipment', 1);
            }
        },
        setRangeSlider: function (value) {
            this.priceRange = value;
        },
        add_active_filter: function (key, value) {
            if (key.length > 1) {
                if (key.length === 3) {
                    this.checkbox[key[0] + '[' + key[1] + '_' + value] = true;
                    const id = key[1].toString().replace(']', '');
                    this.boxStatus[key[0] + '[' + key[1]] = true;
                    this.add_filter_tag(key[0], id, value);
                } else {
                    this.checkbox[key[0] + '_' + value] = true;
                    this.add_filter_tag(key[0], null, value);
                }
            }
            this.$forceUpdate();
        },
        add_filter_tag: function (param, key, value) {
            let label2 = null;
            if (this.filterList['customItems'] !== undefined) {
                for (let i = 0; i < this.filterList['customItems'].length; i++) {
                    const row = this.filterList['customItems'][i];
                    if (row.param === param || row.param === param + '[' + key + ']') {
                        for (let j = 0; j < row.items.length; j++) {
                            if (row.items[j].id == value) {
                                label2 = row.items[j].title;
                            }
                        }
                        if (label2 != null) {
                            const event_key = key !== null ? param + '[' + key + ']' : param;
                            this.selected_filter.push({
                                label1: row.id,
                                label2: label2,
                                event_key: event_key,
                                event_value: value,
                                event_key2: param + '_'
                            });
                        }
                    }
                }
            }
        },
        remove_selected_filter: function (item, sendRequest) {
            if (this.checkbox[item['event_key'] + '_' + item['event_value']] === true) {
                this.remove_url_query_string(item['event_key'], item['event_value'], undefined, sendRequest);
            }
        },
        filterProduct: function () {
            this.drawer = false;
            let params = new window.URLSearchParams(window.location.search);
            if (params.get('page') !== null) {
                this.search_url = this.search_url.replace('page=' + params.get('page'), 'page=1');
            }
            this.setPageUrl(this.search_url, true);
            this.hasFilter = true;
            this.search_url = '';
        },
        setRangePrice: function (values) {
            this.priceRange = values;
        },
        goToProductPage: function (product) {

        }
    }
}
