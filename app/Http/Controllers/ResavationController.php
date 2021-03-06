<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Reservation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ResavationController extends Controller
{
    public function dashboard() { return view('dashboard'); }

    public function detail($id)
    {
        $event = Event::findOrFail($id);
        $sumReservedPeople = DB::table('reservations')
                            ->select('event_id', DB::raw('SUM(number_of_people) as number_of_people'))
                            ->whereNull('canceled_date')
                            ->groupBy('event_id')
                            ->having('event_id', '=', $event->id)
                            ->first();

        if(!is_null($sumReservedPeople))
        {
            $reserablePeople = $event->max_people - $sumReservedPeople->number_of_people;
        }
        else { $reserablePeople = $event->max_people; }

        $isReserved = Reservation::where('user_id', '=', Auth::id())
                                    ->where('event_id', '=', $id)
                                    ->where('canceled_date', '=', null)
                                    ->latest()
                                    ->first();

        return view('event-detail', compact('event', 'reserablePeople', 'isReserved'));
    }

    public function reserve(Request $request)
    {
        $event = Event::findOrFail($request->id);
        $sumReservedPeople = DB::table('reservations')
                            ->select('event_id', DB::raw('SUM(number_of_people) as number_of_people'))
                            ->whereNull('canceled_date')
                            ->groupBy('event_id')
                            ->having('event_id', '=', $event->id)
                            ->first();

        if(is_null($sumReservedPeople) || $event->max_people >= ($sumReservedPeople->number_of_people + $request->reserved_people) )
        {
            Reservation::create([
                'user_id' => Auth::id(),
                'event_id' => $request['id'],
                'number_of_people' => $request['reserved_people'],
            ]);
            session()->flash('status', '???????????????????????????');
            return to_route('dashboard');
        }
        else
        {
            session()->flash('status', '??????????????????????????????????????????');
            return view('dashborad');
        }
    }
}
