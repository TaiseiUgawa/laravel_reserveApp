<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use Illuminate\Support\Facades\DB;
use App\Models\Event;
use Carbon\Carbon;
use App\Services\EventService;

class EventController extends Controller
{
    public function index()
    {
        $sumNumOfPeople = DB::table('reservations')
                            ->select('event_id', DB::raw('SUM(number_of_people) as number_of_people'))
                            ->whereNull('canceled_date')
                            ->groupBy('event_id');
        $today = Carbon::today();

        $events = DB::table('events')
                  ->leftJoinSub($sumNumOfPeople, 'sumNumOfPeople', function ($join) {
                      $join->on('events.id', '=', 'sumNumOfPeople.event_id');
                  })
                  ->whereDate('start_date', '>=', $today)
                  ->orderBy('start_date', 'asc')
                  ->paginate(10);

        return view('manager.events.index',compact('events'));
    }

    public function create()
    {
        return view('manager.events.create');
    }

    public function store(StoreEventRequest $request)
    {
        $has_eventTime = EventService::hasEventDuplication($request['event_date'], $request['start_time'], $request['end_time']);

        if($has_eventTime){
            session()->flash('status', 'その時間帯は既に他のイベントに予約されています');
            return view('manager.events.create');
        }

        $start_date = EventService::joinDateAndTime($request['event_date'], $request['start_time']);
        $end_date = EventService::joinDateAndTime($request['event_date'], $request['end_time']);

        Event::create([
            'name' => $request['event_name'],
            'information' => $request['information'],
            'max_people' => $request['max_people'],
            'start_date' => $start_date,
            'end_date' => $end_date,
            'is_visible' => $request['is_visible'],
        ]);

        session()->flash('status', '登録完了です');

        return to_route('events.index');
    }

    public function show(Event $event)
    {
        $event = Event::findOrFail($event->id);
        $eventDate = $event->eventDate;
        $startTime = $event->startTime;
        $endTime = $event->endTime;
        $users = $event->users;
        $reservedUserInfo = [];

        foreach ($users as $user) {

            $temp = [
                'name' => $user->name,
                'number_of_people' => $user->pivot->number_of_people,
                'canceled_date' => $user->pivot->canceled_date,
            ];
            array_push($reservedUserInfo, $temp);
        }

        return view('manager.events.show',
        compact('event', 'eventDate', 'startTime', 'endTime', 'users', 'reservedUserInfo'));
    }

    public function edit(Event $event)
    {
        $event = Event::findOrFail($event->id);
        $eventDate = $event->editEventDate;
        $startTime = $event->startTime;
        $endTime = $event->endTime;

        return view('manager.events.edit',
        compact('event', 'eventDate', 'startTime', 'endTime'));
    }

    public function update(UpdateEventRequest $request, Event $event)
    {
        $countEventDuplication = EventService::countEventDuplication($request['event_date'], $request['start_time'], $request['end_time']);

        if($countEventDuplication > 1){

            $event = Event::findOrFail($event->id);
            $eventDate = $event->editEventDate;
            $startTime = $event->startTime;
            $endTime = $event->endTime;

            session()->flash('status', 'その時間帯は既に他のイベントに予約されています');
            return view('manager.events.edit',
            compact('event', 'eventDate', 'startTime', 'endTime'));
        }

        $start_date = EventService::joinDateAndTime($request['event_date'], $request['start_time']);
        $end_date = EventService::joinDateAndTime($request['event_date'], $request['end_time']);

        $event = Event::findOrFail($event->id);
        $event->name = $request['event_name'];
        $event->information = $request['information'];
        $event->max_people = $request['max_people'];
        $event->start_date = $start_date;
        $event->end_date = $end_date;
        $event->is_visible = $request['is_visible'];
        $event->save();

        session()->flash('status', 'イベント情報の更新が完了しました');

        return to_route('events.index');
    }

    public function destroy(Event $event)
    {

    }

    public function past()
    {
        $sumNumOfPeople = DB::table('reservations')
                            ->select('event_id', DB::raw('SUM(number_of_people) as number_of_people'))
                            ->whereNull('canceled_date')
                            ->groupBy('event_id');
        $today = Carbon::today();

        $events = DB::table('events')
                    ->leftJoinSub($sumNumOfPeople, 'sumNumOfPeople', function ($join) {
                        $join->on('events.id', '=', 'sumNumOfPeople.event_id');
                    })
                    ->whereDate('start_date', '<', $today)
                    ->orderBy('start_date', 'desc')
                    ->paginate(10);

        return view('manager.events.past',compact('events'));
    }
}
