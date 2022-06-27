<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EventService
{
    public static function hasEventDuplication($eventDate, $startTime, $endTime)
    {
        return DB::table('events')
        ->whereDate('start_date', $eventDate)
        ->whereTime('start_date', '<' ,$endTime)
        ->whereTime('end_date', '>' ,$startTime)
        ->exists();
    }

    public static function joinDateAndTime($date, $time)
    {
        $start = $date . ' ' . $time;
        return Carbon::createFromFormat('Y-m-d H:i',$start);
    }

    public static function countEventDuplication($eventDate, $startTime, $endTime)
    {
        return DB::table('events')
        ->whereDate('start_date', $eventDate)
        ->whereTime('start_date', '<' ,$endTime)
        ->whereTime('end_date', '>' ,$startTime)
        ->count();
    }

    public static function getEventOfWeek($selectDate, $sevenDaysLayter)
    {
        $sumNumOfPeople = DB::table('reservations')
                            ->select('event_id', DB::raw('SUM(number_of_people) as number_of_people'))
                            ->whereNull('canceled_date')
                            ->groupBy('event_id');

        return  DB::table('events')
                ->leftJoinSub($sumNumOfPeople, 'sumNumOfPeople', function ($join) {
                    $join->on('events.id', '=', 'sumNumOfPeople.event_id');
                })
                ->whereBetween('start_date', [$selectDate, $sevenDaysLayter])
                ->orderBy('start_date', 'asc')
                ->get();
    }
}

