<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Event;
use App\Models\Reservation;
use Illuminate\Support\Facades\Auth;
use App\Services\MypageService;
use Carbon\Carbon;

class MyPageController extends Controller
{
    public function index()
    {
        $user = User::findOrFail(Auth::id());
        $events = $user->events;

        $fromTodayEvents = MypageService::getReservedEvent($events, 'formToday');
        $pastEvents = MypageService::getReservedEvent($events, 'past');

        return view('mypage.index', compact('events', 'fromTodayEvents', 'pastEvents'));
    }

    public function show($id)
    {
        $event = Event::findOrFail($id);
        $eventDate = $event->eventDate;
        $startTime = $event->startTime;
        $endTime = $event->endTime;

        $resavation = Reservation::where('user_id', '=', Auth::id())
                                    ->where('event_id', '=', $id)
                                    ->latest()
                                    ->first();

        return view('mypage.show', compact('event', 'resavation', 'eventDate', 'startTime', 'endTime'));
    }

    public function cancel($id)
    {
        $resavation = Reservation::where('user_id', '=', Auth::id())
                                    ->where('event_id', '=', $id)
                                    ->latest()
                                    ->first();

        $resavation->canceled_date = Carbon::now()->format('Y-m-d H-i-s');
        $resavation->save();

        session()->flash('status', '予約をキャンセルしました');
        return to_route('dashboard');
    }
}
