<?php

namespace App\Http\Livewire;

use Livewire\Component;
//use Carbon\Carbon;
use Carbon\CarbonImmutable;
use App\Services\EventService;

class Calendar extends Component
{

    public $currentDate; //今日の日付(日付を指定した日から加算)
    public $currentWeek; //1週間分データ
    public $day; //日付のデータ
    public $checkDay; //日付判定用
    public $dayOfWeek; //曜日
    public $sevenDaysLater; //7日後の日付
    public $events;

    public function mount()
    {
	$this->currentDate = CarbonImmutable::today();
    $this->currentWeek = [];
    $this->sevenDaysLater = $this->currentDate->addDays(7);

    $this->events = EventService::getWeekEvents(
        $this->currentDate->format('Y-m-d'),
        $this->sevenDaysLater->format('Y-m-d'),
    );

        for($i=0;$i<7;$i++){
            $this->day = CarbonImmutable::today()->addDays($i)->format('m月d日');
            $this->checkDay = CarbonImmutable::today()->addDays($i)->format('Y-m-d');
            // dayName:Carbonの機能で、曜日を表示できる
            $this->dayOfWeek = CarbonImmutable::today()->addDays($i)->dayName;
            array_push($this->currentWeek,[
                'day'=>$this->day,
                'checkDay'=>$this->checkDay,
                'dayOfWeek'=>$this->dayOfWeek
            ]);
        }

    }

    // getDateメソッドでは現在の日付を軸に、
    // 7日間の日付、曜日を取得している
    public function getDate($date)
    {
        $this->currentDate = $date;
        $this->currentWeek = [];
        $this->sevenDaysLater = CarbonImmutable::parse($this->currentDate)->addDays(7);
            
            
        $this->events = EventService::getWeekEvents(
            $this->currentDate,
            $this->sevenDaysLater->format('Y-m-d'),
        );

    for($i=0;$i<7;$i++){
        $this->day = CarbonImmutable::parse($this->currentDate)->addDays($i)->format('m月d日');
        $this->checkDay = CarbonImmutable::parse($this->currentDate)->addDays($i)->format('Y-m-d');
        $this->dayOfWeek = CarbonImmutable::parse($this->currentDate)->addDays($i)->dayName;
        array_push($this->currentWeek,[
            'day'=>$this->day,
            'checkDay'=>$this->checkDay,
            'dayOfWeek'=>$this->dayOfWeek
        ]);
    }
}

    public function render()
    {
        return view('livewire.calendar');
    }
}
