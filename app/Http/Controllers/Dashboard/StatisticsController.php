<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Repositories\Dashboard\Statistics\StatisticsRepositoryInterface;
use Illuminate\Http\Request;

class StatisticsController extends Controller
{
    protected StatisticsRepositoryInterface $statistics;

    /**
     * @param StatisticsRepositoryInterface $statistics
     */
    public function __construct(StatisticsRepositoryInterface $statistics)
    {
        $this->statistics = $statistics;
    }


}
