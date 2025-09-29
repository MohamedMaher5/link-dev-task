<?php

namespace App\Services\Admin;

use App\Models\Booking;
use Illuminate\Support\Facades\DB;

class ReportService
{
    protected function applyFilters($query, array $filters)
    {
        if (!empty($filters['provider_id'])) {
            $query->where('provider_id', $filters['provider_id']);
        }

        if (!empty($filters['service_id'])) {
            $query->where('service_id', $filters['service_id']);
        }

        if (!empty($filters['from_date']) && !empty($filters['to_date'])) {
            $query->whereBetween('start_time', [$filters['from_date'], $filters['to_date']]);
        }

        return $query;
    }

    public function bookingsPerProvider(array $filters)
    {
        $query = Booking::select('provider_id', DB::raw('COUNT(*) as total'))
            ->groupBy('provider_id');

        $this->applyFilters($query, $filters);

        return $query->with('provider:id,name')->get();
    }

    public function serviceStatusRate(array $filters)
    {
        $query = Booking::select(
            'service_id',
            DB::raw("COUNT(*) as total"),
            DB::raw("ROUND(SUM(CASE WHEN status = 'confirmed' THEN 1 ELSE 0 END) / COUNT(*) * 100, 2) as confirmed_percentage"),
            DB::raw("ROUND(SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END) / COUNT(*) * 100, 2) as cancelled_percentage")
        )->groupBy('service_id');

        $this->applyFilters($query, $filters);

        return $query->with('service:id,name')->get();
    }

    public function peakHours(array $filters)
    {
        $query = Booking::select(
            DB::raw('DAYOFWEEK(start_time) as day_of_week'),
            DB::raw('DAYNAME(start_time) as day_name'),
            DB::raw('HOUR(start_time) as hour'),
            DB::raw('COUNT(*) as count')
        )
            ->groupBy('day_of_week', 'day_name', 'hour')
            ->orderBy('count', 'desc');

        $this->applyFilters($query, $filters);

        return $query->limit(10)->get();
    }

    public function averageBookingDuration(array $filters)
    {
        $query = Booking::select(
            'provider_id',
            DB::raw('AVG(TIMESTAMPDIFF(MINUTE, start_time, end_time)) as avg_duration')
        )
            ->groupBy('provider_id');

        $this->applyFilters($query, $filters);

        return $query->with('provider:id,name,email')->get();
    }
}
