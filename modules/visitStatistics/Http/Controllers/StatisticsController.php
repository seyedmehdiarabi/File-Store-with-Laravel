<?php

namespace Modules\visitStatistics\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\products\Repository\ProductRepositoryInterface;
use Modules\visitStatistics\Models\ProductStatistics;
use Modules\visitStatistics\Models\Statistics;

class StatisticsController extends Controller
{
    public function month_visit()
    {
        $statistics = new Statistics();
        return $statistics->getMonthVisit();
    }

    public function week_visit($year = null)
    {
        $statistics = new Statistics();
        return $statistics->getWeekVisit($year);
    }

    public function product_visit_view($product_id)
    {
        $product = null;
        if (file_exists(base_path('modules/products/Repository/ProductRepositoryInterface.php'))) {
            $repository = app(ProductRepositoryInterface::class);
            $product = $repository->find($product_id);
        }
        return CView('visitStatistics::chart.product', compact('product'));
    }

    public function get_product_visit($product_id)
    {
        $statistics = new ProductStatistics($product_id);
        return $statistics->getVisit();
    }
}
