<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Carbon\CarbonImmutable;
use App\Services\Eventservice;

class Calendar extends Component
{
    public $currentDate;
    public $currentWeek;
    public $day;
    public $sevenDaysLater;
    public $events;
    public $hasEventDay;
    public $dayName;

    public function mount()
    {
        $this->currentDate = CarbonImmutable::today();
        $this->sevenDaysLater = $this->currentDate->addDays(7);
        $this->currentWeek = [];

        $this->events = Eventservice::getEventOfWeek(
            $this->currentDate->format('Y-m-d'),
            $this->sevenDaysLater->format('Y-m-d')
        );

        for ($i = 0 ; $i < 7 ; $i++)
        {
            $this->day = CarbonImmutable::today()->addDays($i)->format('m月d日');
            $this->hasEventDay = CarbonImmutable::today()->addDays($i)->format('Y-m-d');
            $this->dayName = CarbonImmutable::today()->addDays($i)->dayName;

            array_push($this->currentWeek, [
                'day' => $this->day,
                'hasEventDay' => $this->hasEventDay,
                'dayName' => $this->dayName,
            ]);
        }
    }

    public function getDate($date)
    {
        $this->currentDate = $date;
        $this->sevenDaysLater = CarbonImmutable::parse($this->currentDate)->addDays(7);
        $this->currentWeek = [];

        $this->events = Eventservice::getEventOfWeek(
            $this->currentDate,
            $this->sevenDaysLater->format('Y-m-d')
        );

        for ($i = 0 ; $i < 7 ; $i++)
        {
            $this->day = CarbonImmutable::parse($this->currentDate)->addDays($i)->format('m月d日');
            $this->hasEventDay = CarbonImmutable::parse($this->currentDate)->addDays($i)->format('Y-m-d');
            $this->dayName = CarbonImmutable::parse($this->currentDate)->addDays($i)->dayName;

            array_push($this->currentWeek, [
                'day' => $this->day,
                'hasEventDay' => $this->hasEventDay,
                'dayName' => $this->dayName,
            ]);
        }
    }

    public function render()
    {
        return view('livewire.calendar');
    }
}
