<?php

namespace App\Services;

use App\Models\AvailabilityOverride;
use App\Models\Booking;
use App\Models\ProviderAvailability;
use App\Models\Service;
use App\Models\User;
use Carbon\Carbon;

class AvailabilityService
{
    public function generateSlots(User $provider, Service $service, int $days = 7): array
    {
        $tz = $provider->timezone ?: config('app.timezone', 'UTC');
        $nowUtc = Carbon::now('UTC');
        $slots = [];

        $todayLocal = Carbon::now($tz)->startOfDay();
        $duration = (int) $service->duration;

        if ($duration <= 0) {
            throw new \Exception("Service duration must be greater than 0 minutes");
        }

        for ($d = 0; $d < $days; $d++) {
            $dateLocal = $todayLocal->copy()->addDays($d);
            $dayOfWeek = (int) $dateLocal->dayOfWeek; // 0 = Sunday ... 6 = Saturday

            $recurrings = ProviderAvailability::where('provider_id', $provider->id)
                ->where('day_of_week', $dayOfWeek)
                ->get();

            $overrides = AvailabilityOverride::where('provider_id', $provider->id)
                ->where('date', $dateLocal->toDateString())
                ->get();

            $periods = [];

            foreach ($recurrings as $r) {
                $startLocal = Carbon::parse($dateLocal->toDateString() . ' ' . $r->start_time, $tz);
                $endLocal   = Carbon::parse($dateLocal->toDateString() . ' ' . $r->end_time, $tz);

                if ($endLocal->lessThanOrEqualTo($startLocal)) {
                    $endLocal->addDay();
                }

                $periods[] = ['start' => $startLocal, 'end' => $endLocal];
            }

            foreach ($overrides as $ov) {
                if ($ov->type === 'open') {
                    $s = $ov->start_time
                        ? Carbon::parse($ov->date.' '.$ov->start_time, $tz)
                        : $dateLocal->copy()->startOfDay();
                    $e = $ov->end_time
                        ? Carbon::parse($ov->date.' '.$ov->end_time, $tz)
                        : $dateLocal->copy()->endOfDay();
                    $periods[] = ['start' => $s, 'end' => $e];
                } else { // block
                    $blockStart = $ov->start_time
                        ? Carbon::parse($ov->date.' '.$ov->start_time, $tz)
                        : $dateLocal->copy()->startOfDay();
                    $blockEnd   = $ov->end_time
                        ? Carbon::parse($ov->date.' '.$ov->end_time, $tz)
                        : $dateLocal->copy()->endOfDay();

                    $newPeriods = [];
                    foreach ($periods as $p) {
                        if ($blockEnd->lessThanOrEqualTo($p['start']) || $blockStart->greaterThanOrEqualTo($p['end'])) {
                            $newPeriods[] = $p;
                            continue;
                        }
                        if ($blockStart->greaterThan($p['start'])) {
                            $newPeriods[] = ['start' => $p['start'], 'end' => $blockStart];
                        }
                        if ($blockEnd->lessThan($p['end'])) {
                            $newPeriods[] = ['start' => $blockEnd, 'end' => $p['end']];
                        }
                    }
                    $periods = $newPeriods;
                }
            }

            $dayStartUtc = $dateLocal->copy()->startOfDay()->setTimezone('UTC');
            $dayEndUtc   = $dateLocal->copy()->endOfDay()->setTimezone('UTC');

            $bookings = Booking::where('provider_id', $provider->id)
                ->whereBetween('start_time', [$dayStartUtc, $dayEndUtc])
                ->whereIn('status', ['pending','confirmed'])
                ->get(['start_time', 'end_time']);

            foreach ($periods as $p) {
                $cursor = $p['start']->copy();

                while ($cursor->lessThan($p['end'])) {
                    $slotStartLocal = $cursor->copy();
                    $slotEndLocal   = $slotStartLocal->copy()->addMinutes($duration);

                    if ($slotEndLocal->greaterThan($p['end'])) break;

                    $slotStartUtc = $slotStartLocal->copy()->setTimezone('UTC');
                    $slotEndUtc   = $slotEndLocal->copy()->setTimezone('UTC');

                    if ($slotEndUtc->lessThanOrEqualTo($nowUtc)) {
                        $cursor->addMinutes($duration);
                        continue;
                    }

                    $overlap = $bookings->contains(function ($b) use ($slotStartUtc, $slotEndUtc) {
                        return $b->start_time < $slotEndUtc && $b->end_time > $slotStartUtc;
                    });

                    if (! $overlap) {
                        $slots[] = [
                            'start' => $slotStartUtc->toDateTimeString(),
                            'end'   => $slotEndUtc->toDateTimeString(),
                            'local_start' => $slotStartLocal->toDateTimeString(),
                            'local_end'   => $slotEndLocal->toDateTimeString(),
                        ];
                    }

                    $cursor->addMinutes($duration);
                }
            }
        }

        return $slots;
    }

}
