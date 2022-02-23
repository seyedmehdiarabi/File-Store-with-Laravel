<template>
    <div>
        <div v-if="send_request" class="progress_box">
            <v-progress-circular indeterminate color="red"></v-progress-circular>
        </div>

        <div v-else>
            <div style="display: flex; width: 320px">
                <v-select
                    label="سال"
                    v-model="year"
                    :items="years"
                    outlined dense
                    style="width:150px;margin-left:20px;"
                    item-color="white"
                    @change="setYear"
                >
                </v-select>

                <v-select
                    label="ماه"
                    v-model="month_select"
                    :items="months"
                    outlined dense
                    style="width:150px;margin-left:20px;"
                    item-color="white"
                    @change="changeMonth"
                >

                </v-select>
            </div>

            <highcharts :options="chartOptions"></highcharts>
        </div>
    </div>
</template>

<script>
import {Chart} from "highcharts-vue";
import methods from "../methods";

export default {
    name: "MonthVisit",
    mixins: [methods],
    props:['product_id'],
    data() {
        const self = this;
        return {
            month: '',
            year: '',
            month_select: '',
            send_request: false,
            years: [],
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
                                value = self.number_format(value);
                            }
                            value = self.replaceNumber(value);
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
                series: [
                    {
                        name: 'بازدید یکتا',
                        data: [],
                        color: 'red'
                    },
                    {
                        name: 'بازدید کل',
                        data: [],
                        marker: {
                            symbol: 'circle'
                        }
                    },
                    {
                        name: 'ورودی گوگل',
                        data: [],
                    },
                ],
                tooltip: {
                    useHTML: true,
                    formatter: function () {
                        return self.replaceNumber(this.x) + '<br>' + '<div style="padding:5px">' + this.series.name + ' : ' + self.replaceNumber(self.number_format(this.y)) + '</div>';
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
            },
            months: [
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
            ],
        }
    },
    components: {
        highcharts: Chart
    },
    mounted() {
        this.getData();
    },
    methods: {
        getData: function () {
            this.send_request = true;
            let url = this.$siteUrl + '/statistics/product/' + this.product_id + '/visit';
            if (this.month !== '') {
                url = url + '?month=' + this.month;
            }
            if (this.year !== '') {
                url = url + '&year=' + this.year;
            }
            this.axios.get(url).then(response => {
                this.send_request = false;
                const categories = [];
                for (let i = 1; i <= response.data['unique_visit'].length; i++) {
                    categories.push(i);
                }
                this.chartOptions['xAxis']['categories'] = categories;
                this.chartOptions['series'][0]['data'] = response.data['unique_visit'];
                this.chartOptions['series'][1]['data'] = response.data['view'];
                this.chartOptions['series'][2]['data'] = response.data['google_visit'];
                this.years = response.data.years;
                this.month = response.data.month;
                this.year = response.data.year;
                for (let i = 0; i < this.months.length; i++) {
                    if (i === (response.data.month - 1)) {
                        this.month_select = this.months[i];
                    }
                }
            }).catch((error) => {
                this.send_request = false;
            });
        },
        changeMonth: function (value) {
            for (let i = 0; i < this.months.length; i++) {
                if (value === this.months[i]) {
                    this.month = (i + 1);
                    this.getData();
                }
            }
        },
        setYear: function (value) {
            this.year = value;
            this.getData();
        }
    }
}
</script>
<style>
@import "../style.css";
</style>
