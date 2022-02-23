<?php

namespace Modules\visitStatistics\Models;

use App\Lib\Jdf;
use DB;
use Illuminate\Support\Facades\Auth;

class Statistics
{
    protected $ip;
    protected $user_id;
    protected $year;
    protected $month;
    protected $day;
    protected $hour;
    protected $days_of_week;
    protected $request;

    public function __construct()
    {
        $jdf = new Jdf();
        $this->request = request();
        $this->ip = $this->request->ip();
        $this->user_id = Auth::check() ? Auth::user()->id : 0;
        $this->year = $jdf->tr_num($jdf->jdate('Y'));
        $this->month = $jdf->tr_num($jdf->jdate('n'));
        $this->day = $jdf->tr_num($jdf->jdate('d'));
        $this->days_of_week = $jdf->tr_num($jdf->jdate('w'));
        $this->hour = $jdf->tr_num($jdf->jdate('H'));
    }

    public function addData()
    {
        $c1 = $this->checkUserVisit();
        if (!$c1) {
            $this->addUserVisit();
            $this->addDayVisit(true);
        } else {
            $this->updateUserVisit();
            $this->addDayVisit(false);
        }
        $this->addWeekVisit();
    }

    public function checkUserVisit()
    {
        return DB::table('statistics__user_visit')->where([
            'year' => $this->year,
            'month' => $this->month,
            'day' => $this->day,
            'user_id' => $this->user_id,
        ])->first();
    }

    public function addUserVisit()
    {
        return DB::table('statistics__user_visit')->insert([
            'year' => $this->year,
            'month' => $this->month,
            'day' => $this->day,
            'ip' => $this->ip,
            'user_id' => $this->user_id,
            'view'=>1,
        ]);
    }
    public function updateUserVisit(){
        DB::table('statistics__user_visit')->where([
            'year' => $this->year,
            'month' => $this->month,
            'day' => $this->day,
            'user_id' => $this->user_id,
        ])->increment('view');
    }

    public function addDayVisit($updateUnique)
    {
        $row = DB::table('statistics__day_visit')->where([
            'year' => $this->year,
            'month' => $this->month,
            'day' => $this->day
        ])->first();
        $google_visit = 0;
        if ($this->checkGoogleRedirect()) {
            $google_visit = 1;
        }
        if ($row) {
            $update = ['view' => ($row->view + 1)];
            if ($updateUnique) {
                $update ['unique_visit'] = ($row->unique_visit + 1);
            }
            $update ['google_visit'] = ($row->google_visit + $google_visit);
            DB::table('statistics__day_visit')->where([
                'year' => $this->year,
                'month' => $this->month,
                'day' => $this->day
            ])->update($update);
        } else {
            DB::table('statistics__day_visit')->insert([
                'year' => $this->year,
                'month' => $this->month,
                'day' => $this->day,
                'google_visit' => $google_visit,
                'view' => 1,
                'unique_visit' => 1
            ]);
        }
    }

    public function addWeekVisit()
    {
        $row = DB::table('statistics__week_visit')->where([
            'year' => $this->year,
            'days_of_week' => $this->days_of_week,
            'hour' => $this->hour
        ])->first();
        if ($row) {
            $view = $row->view + 1;
            DB::table('statistics__week_visit')->where([
                'year' => $this->year,
                'days_of_week' => $this->days_of_week,
                'hour' => $this->hour
            ])->update(['view' => $view]);
        } else {
            DB::table('statistics__week_visit')->insert([
                'year' => $this->year,
                'days_of_week' => $this->days_of_week,
                'hour' => $this->hour,
                'view' => 1
            ]);
        }
    }

    public function checkGoogleRedirect()
    {
        $result = false;
        $backUrl = url()->previous();
        if ($backUrl) {
            $parseUrl = parse_url($backUrl);
            if (str_contains($parseUrl['host'], 'google.com')) {
                $result = true;
            }
        }
        return $result;
    }

    public function getMonthVisit()
    {
        $jdf = new Jdf();
        $year = $this->request->has('year') ? $this->request->get('year') : $this->year;
        $month = $this->request->has('month') ? $this->request->get('month') : $this->month;
        $timestamp = getTimestamp($year . '/' . $month . '1', 'first');
        $t = $jdf->tr_num($jdf->jdate('t', $timestamp));
        $visits = DB::table('statistics__day_visit')
            ->where(['year' => $year, 'month' => $month])
            ->get()->toArray();
        $unique_visit = array();
        $view = array();
        $google_visit = array();
        for ($i = 0; $i < $t; $i++) {
            $unique_visit[$i] = 0;
            $view[$i] = 0;
            $google_visit[$i] = 0;
            foreach ($visits as $visit) {
                if ($visit->day == ($i + 1)) {
                    $unique_visit[$i] = intval($visit->unique_visit);
                    $view[$i] = intval($visit->view);
                    $google_visit[$i] = intval($visit->google_visit);
                }
            }
        }
        $first = DB::table('statistics__day_visit')->first();
        $years = [];
        if ($first && $first->year !== $this->year) {
            $j = 0;
            $a = $first->year;
            settype($a, 'integer');
            for ($i = $a; $i <= $this->year; $i++) {
                $years[$j] = $i;
                $j++;
            }
        }
        if (sizeof($years) == 0) {
            $years = [intval($year)];
        }
        return [
            'month' => $month,
            'year' => intval($year),
            'view' => $view,
            'unique_visit' => $unique_visit,
            'google_visit' => $google_visit,
            'years' => $years
        ];
    }

    public function getWeekVisit($year)
    {
        $jdf = new Jdf();
        $year = $year ? $year : $this->year;
        $visits = DB::table('statistics__week_visit')->where(['year' => $year])->get();
        $first = DB::table('statistics__week_visit')->first();
        $years = [];
        if ($first && $first->year !== $this->year) {
            $j = 0;
            $a = $first->year;
            settype($a, 'integer');
            for ($i = $a; $i <= $this->year; $i++) {
                $years[$j] = $i;
                $j++;
            }
        }
        if (sizeof($years) == 0) {
            $years = [intval($year)];
        }
        return [
            'visits' => $visits,
            'year' => intval($this->year),
            'years' => $years
        ];
    }

}
