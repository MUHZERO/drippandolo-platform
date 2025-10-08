<?php

namespace App\Support;

use App\Models\Order;
use App\Models\Spend;
use App\Models\Revenu;
use Carbon\Carbon;

class DashboardService
{
    public static function getPeriod(int $month, int $year): array
    {
        $start = Carbon::create($year, $month, 1)->startOfMonth();
        $end = $start->copy()->endOfMonth();
        return [$start, $end];
    }

    public static function getStats(int $month, int $year): array
    {
        [$start, $end] = self::getPeriod($month, $year);

        $orders = Order::whereBetween('created_at', [$start, $end])->get();
        $spend = Spend::whereBetween('created_at', [$start, $end])->sum('amount');
        $revenue = Revenu::whereBetween('date', [$start->toDateString(), $end->toDateString()])->sum('amount');

        return [
            'revenue' => $revenue,
            'spend' => $spend,
            'net' => $revenue - $spend,
            'orders' => [
                'total' => $orders->count(),
                'delayed' => $orders->where('status', 'delayed')->count(),
                'shipped' => $orders->where('status', 'shipped')->count(),
                'delivered' => $orders->where('status', 'delivered')->count(),
                'canceled' => $orders->where('status', 'canceled')->count(),
            ]
        ];
    }

    public static function getDailyData(int $month, int $year): array
    {
        [$start, $end] = self::getPeriod($month, $year);
        $daysInMonth = $start->daysInMonth;

        $days = [];
        $revenue = [];
        $spend = [];
        $net = [];
        $orders = [];

        for ($i = 1; $i <= $daysInMonth; $i++) {
            $day = Carbon::create($year, $month, $i);
            $days[] = $day->format('d');

            $rev = Revenu::whereDate('date', $day)->sum('amount');
            $sp  = Spend::whereDate('created_at', $day)->sum('amount');
            $or  = Order::whereDate('created_at', $day)->count();

            $revenue[] = $rev;
            $spend[]   = $sp;
            $net[]     = $rev - $sp;
            $orders[]  = $or;
        }

        return compact('days', 'revenue', 'spend', 'net', 'orders');
    }
}
