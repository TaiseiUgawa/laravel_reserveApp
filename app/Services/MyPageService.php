<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MyPageService
{
    public static function getReservedEvent($events, $string)
    {
        $reservedEvent = [];

        if ($string === 'formToday')
        {
            foreach ($events->sortBy('start_date') as $event) {
                if(is_null($event->pivot->canceled_date) && $event->start_date >= Carbon::now()->format('Y-m-d 00:00:00'))
                {
                    $eventInfo = [
                        'id' => $event->id,
                        'name' => $event->name,
                        'start_date' => $event->start_date,
                        'end_date' => $event->end_date,
                        'number_of_people' => $event->pivot->number_of_people,
                    ];

                    array_push($reservedEvent, $eventInfo);
                }
            }
        }

        if ($string === 'past')
        {
            foreach ($events->sortBy('start_date') as $event) {
                if(is_null($event->pivot->canceled_date) && $event->start_date < Carbon::now()->format('Y-m-d 00:00:00'))
                {
                    $eventInfo = [
                        'id' => $event->id,
                        'name' => $event->name,
                        'start_date' => $event->start_date,
                        'end_date' => $event->end_date,
                        'number_of_people' => $event->pivot->number_of_people,
                    ];

                    array_push($reservedEvent, $eventInfo);
                }
            }
        }

        return $reservedEvent;
    }
}
