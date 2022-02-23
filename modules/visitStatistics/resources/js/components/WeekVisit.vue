<template>
    <div>
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

        <div v-for="i in 8">
            <div v-if="i<8" style="display: flex; align-items: center;">
                <span class="day-week">{{ getDayOfWeek(i) }}</span>
                <div v-for="h in 24" class="h_div" :style="{backgroundColor:getBackgroundColor(i,h)}"
                     @click="showDetail(i,h)"></div>
            </div>

            <div v-else style="display: flex; align-items: center;">
                <span class="day-week"></span>
                <div v-for="h in 24" class="hour_div">
                    <span>{{ replaceNumber(h - 1) }}</span>
                </div>
            </div>

        </div>

        <v-dialog width="400" v-model="dialog">
            <v-card>
                <v-card-text>
                    <div style="text-align:center;">
                        <div style="padding:20px 10px 10px; color: black;">تعداد بازدید:
                            {{ replaceNumber(hour_view) }}
                        </div>
                        <div style="padding:20px 10px 10px; color: black;">درصد بازدید در طول روز:
                            {{ replaceNumber(Math.round(hour_precentage)) }}
                        </div>

                    </div>
                </v-card-text>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
import methods from "../methods";

export default {
    name: "WeekVisit",
    mixins: [methods],
    data() {
        return {
            dialog: false,
            hour_view: 0,
            hour_precentage: 0,
            visits: [],
            year: '',
            years: [],
            week: [
                'شنبه',
                'یک‌شنبه',
                'دوشنبه',
                'سه‌شنبه',
                'چهارشنبه',
                'پنج‌شنبه',
                'جمعه'
            ],
            dayVisit: {},
            colors: {
                '0-10': '#E0F7FA',
                '10-20': '#B2EBF2',
                '20-30': '#80DEEA',
                '30-40': '#4DD0E1',
                '40-50': '#26C6DA',
                '50-60': '#00ACC1',
                '60-70': '#0097A7',
                '70-80': '#00838F',
                '80-90': '#006064',
                '90-100': 'black',
            },
            send_request: false
        }
    },
    mounted() {
        this.getData();
    },
    methods: {
        getData: function () {
            this.send_request = true;
            let url = this.$siteUrl + '/statistics/week/visit';
            if (this.year !== '') {
                url = url + '/' + this.year;
            }
            this.axios.get(url).then(response => {
                this.send_request = false;
                if (response.data.years !== undefined) {
                    this.dayVisit = {};
                    this.years = response.data.years;
                    this.year = parseInt(response.data.year);
                    this.visits = response.data.visits;
                    for (let i = 0; i < this.visits.length; i++) {
                        if (this.dayVisit[this.visits[i]['days_of_week']] === undefined) {
                            this.dayVisit[this.visits[i]['days_of_week']] = parseInt(this.visits[i]['view']);
                        } else {
                            this.dayVisit[this.visits[i]['days_of_week']] = this.dayVisit[this.visits[i]['days_of_week']] + parseInt(this.visits[i]['view']);
                        }
                    }
                    this.$forceUpdate();
                }

            }).catch((error) => {
                this.send_request = false;
            });
        },
        getDayOfWeek: function (k) {
            k = k - 1;
            return this.week[k];
        },
        getBackgroundColor: function (i, h) {
            const day = (i - 1);
            const hour = (h - 1);
            let view = 0;
            let d = 0;
            let color = '';
            for (let j = 0; j < this.visits.length; j++) {
                if (this.visits[j].hour === hour && this.visits[j].days_of_week === day) {
                    view = this.visits[j]['view'];
                    if (view > 0) {
                        d = (view / this.dayVisit[day]) * 100;
                    }
                }
            }
            const colorKeys = Object.keys(this.colors);
            for (let j = 0; j < colorKeys.length; j++) {
                const item = colorKeys[j].split('-');
                if (d > parseInt(item[0]) && d <= parseInt(item[1])) {
                    color = this.colors[colorKeys[j]];
                }
            }
            return color;
        },
        setYear: function (value) {
            this.year = value;
            this.getData();
        },
        showDetail(i, h) {
            this.dialog = true;
            this.hour_precentage = 0;
            this.hour_view = 0;
            const day = i - 1;
            const hour = h - 1;
            for (let j = 0; j < this.visits.length; j++) {
                if (this.visits[j].hour == hour && this.visits[j].days_of_week == day) {
                    this.hour_view = this.visits[j].view;
                    if (this.hour_view > 0) {
                        this.hour_precentage = (this.hour_view / this.dayVisit[day]) * 100;
                    }
                }
            }
        }
    }
}
</script>

<style>
@import "../style.css";
</style>
