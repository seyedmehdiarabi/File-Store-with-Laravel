<template>
    <div>

        <div v-if="send_request" class="progress-box">
            <v-progress-circular
                indeterminate
                color="red"
            ></v-progress-circular>
        </div>

        <div v-else>

            <highcharts :options="chartOptions"></highcharts>

        </div>

    </div>
</template>

<script>
import {Chart} from 'highcharts-vue';

const replaceNumber = function (n) {
    if (n != undefined) {
        n = n.toString();
        const find = ["0", "1", "2", "3", "4", "5", "6", "7", "8", "9"];
        const replace = ["۰", "۱", "۲", "۳", "۴", "۵", "۶", "۷", "۸", "۹"];
        for (let i = 0; i < find.length; i++) {
            n = n.replace(new RegExp(find[i], 'g'), replace[i]);
        }
        return n;
    }
};

const number_format = function (num) {
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

export default {
    name: "IndexSaleChart",
    data() {
        return {
            send_request: true,
            chartOptions: {
                title: {
                    text: ''
                },
                chart: {
                    type: 'line',
                    style: {
                        fontFamily: 'inherit'
                    }
                },
                subtitle: {
                    text: ''
                },
                yAxis: {
                    title: {
                        text: ''
                    },
                    labels: {
                        useHTML: true,
                        formatter: function () {
                            let value = this.value;
                            if (value > 1000) {
                                value = number_format(value);
                            }
                            value = replaceNumber(value);
                            return '<div style="direction:ltr">'
                                + '<span>' + value + '</span>'
                                + '</div>';
                        },
                        style: {
                            fontSize: '15px'
                        }
                    }
                },
                xAxis: {
                    categories: []
                },
                legend: {
                    verticalAlign: 'top',
                    y: 0
                },
                series: [{
                    name: 'میزان فروش',
                    data: [],
                    color: 'red'
                }, {
                    name: 'تعداد تراکنش',
                    data: [],
                    marker: {
                        symbol: 'circle'
                    }
                }],
                tooltip: {
                    useHTML: true,
                    formatter: function () {
                        if (this.series.name === 'میزان فروش') {
                            return this.x + '<br>' + '<div style="padding:5px">' + this.series.name + ' : ' + replaceNumber(number_format(this.y)) + ' تومان' + '</div>';
                        } else {
                            return this.x + '<br>' + '<div style="padding:5px">' + this.series.name + ' : ' + replaceNumber(number_format(this.y)) + ' بار' + '</div>';

                        }
                    },
                    style: {
                        fontSize: '15px'
                    }
                },
                responsive: {
                    rules: [{
                        condition: {
                            maxWidth: 500
                        },
                        chartOptions: {
                            legend: {
                                layout: 'horizontal',
                                align: 'center',
                                verticalAlign: 'bottom'
                            }
                        }
                    }]
                }
            }
        }
    },
    components: {
        highcharts: Chart
    },
    mounted() {
        this.getChartData();
    },
    methods: {
        getChartData: function () {
            const url = this.$siteUrl + "/admin/order/index-chart-data";
            this.axios.get(url).then(response => {
                this.send_request = false;
                this.chartOptions['xAxis']['categories'] = response.data['date_list'];
                this.chartOptions.series[0]['data'] = response.data['price_array'];
                this.chartOptions.series[1]['data'] = response.data['count_array'];
            }).catch(error => {
                this.send_request = false;
            })
        },
    }
}
</script>

