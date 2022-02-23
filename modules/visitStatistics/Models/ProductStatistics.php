<?php

namespace Modules\visitStatistics\Models;

use App\Lib\Jdf;
use DB;
use function Symfony\Component\Translation\t;

class ProductStatistics
{
    protected $ip;
    protected $year;
    protected $month;
    protected $day;
    protected $request;
    protected $product_id;

    public function __construct($product_id)
    {
        $Jdf = new Jdf();
        $this->request = request();
        $this->ip = $this->request->ip();
        $this->year = $Jdf->tr_num($Jdf->jdate('Y'));
        $this->month = $Jdf->tr_num($Jdf->jdate('n'));
        $this->day = $Jdf->tr_num($Jdf->jdate('d'));
        $this->product_id = $product_id;
    }

    public function addData()
    {
        $c1 = $this->checkUserVisit();
        if (!$c1) {
            $this->addUserVisit();
            $this->addDayVisit(true);
        } else {
            $this->addDayVisit(false);
        }
    }

    public function checkUserVisit()
    {
        return DB::table('statistics__user_visit_product')->where([
            'year' => $this->year,
            'month' => $this->month,
            'day' => $this->day,
            'ip' => $this->ip,
            'product_id' => $this->product_id
        ])->first();
    }

    public function addUserVisit()
    {
        return DB::table('statistics__user_visit_product')->insert([
            'year' => $this->year,
            'month' => $this->month,
            'day' => $this->day,
            'ip' => $this->ip,
            'product_id' => $this->product_id
        ]);
    }

    public function addDayVisit($updateUnique)
    {
        $row = DB::table('statistics__product_visit')->where([
            'year' => $this->year,
            'month' => $this->month,
            'day' => $this->day,
            'product_id' => $this->product_id
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
            DB::table('statistics__product_visit')->where([
                'year' => $this->year,
                'month' => $this->month,
                'day' => $this->day,
                'product_id' => $this->product_id
            ])->update($update);
        } else {
            DB::table('statistics__product_visit')->insert([
                'year' => $this->year,
                'month' => $this->month,
                'day' => $this->day,
                'view' => 1,
                'unique_visit' => 1,
                'google_visit' => $google_visit,
                'product_id' => $this->product_id
            ]);
        }
    }

    public function getVisit()
    {
        $Jdf = new Jdf();
        $year = $this->request->has('year') ? $this->request->get('year') : $this->year;
        $month = $this->request->has('month') ? $this->request->get('month') : $this->month;
        $timestamp = getTimestamp($year . '/' . $month . '/1', 'first');
        $t = $Jdf->tr_num($Jdf->jdate('t', $timestamp));
        $visits = DB::table('statistics__product_visit')
            ->where(['year' => $year, 'month' => $month, 'product_id'=> $this->product_id])
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
        $first = DB::table('statistics__product_visit')->first();
        $years = [];
        if ($first && $first->year != $this->year) {
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
            'unique_visit' => $unique_visit,
            'view' => $view,
            'years' => $years,
            'google_visit' => $google_visit
        ];
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

}
