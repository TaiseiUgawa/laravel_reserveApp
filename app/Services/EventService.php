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
}

