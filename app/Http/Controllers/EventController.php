<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Services\EventService;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //Carbonで本日の日付を取得
        $today = Carbon::today();

        // reservationsテーブルのデータを$reservedPeopleに格納
        $reservedPeople = DB::table('reservations')

        // eventのiDと参加者の人数の合計のカラムを指定
        ->select('event_id', DB::raw('sum(number_of_people) as number_of_people'))
        
        // event_idの重複が出ないよう、groupにする
        ->groupBy('event_id');

        // eventsのテーブル情報を$eventsに代入
        $events = DB::table('events')

        // event.idとreservedPeople.event_idを外部結合
        // 合計人数がない場合、nullとして表示
        ->leftJoinSub($reservedPeople, 'reservedPeople', function($join){
            $join->on('events.id', '=', 'reservedPeople.event_id');
        })

        // 開始日が本日以降のデータを取得する
        ->whereDate('start_date','>=',$today)

        // start_dateを昇順にする
        ->orderBy('start_date', 'asc')
        ->paginate(10);

        return view('manager.events.index', 
        compact('events'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('manager.events.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreEventRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreEventRequest $request)
    {
        $check = EventService::checkEventDuplication(
            $request['event_date'],$request['start_time'],$request['end_time']);

        if($check){ 
            session()->flash('status', 'この時間帯は既に他の予約が存在します。'); 
            return view('manager.events.create'); 
        }

            $startDate = EventService::joinDateAndTime($request['event_date'],$request['start_time']);
            $endDate = EventService::joinDateAndTime($request['event_date'],$request['end_time']);

        Event::create([ 
            'name' => $request['event_name'], 
            'information' => $request['information'], 
            'start_date' => $startDate, 
            'end_date' => $endDate, 
            'max_people' => $request['max_people'], 
            'is_visible' => $request['is_visible'], 
        ]); 
        session()->flash('status', '登録okです'); 
        return to_route('events.index');
    }

    public function show(Event $event)
    {
        // EventのID情報を$eventで受け取る
        $event = Event::findOrFail($event->id);
        $users = $event->users;

        $reservations = [];

        foreach($users as $user)
        {
            // 中間テーブル(reservationテーブル)
            // の情報を取得するため、pivotと記述
            $reservedInfo = [
                'name' => $user->name,
                'number_of_people' => $user->pivot->number_of_people,
                'canceled_date' =>  $user->pivot->canceled_date
            ];

            // 連想配列で取得したものを $reservedInfoから$reservationsへ
            // array_pushで格納している       
            array_push($reservations, $reservedInfo);
        }

        $eventDate = $event->eventDate;
        $startTime = $event->startTime;
        $endTime = $event->endTime;

        return view('manager.events.show',
        compact('event','users','reservations','eventDate','startTime','endTime'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function edit(Event $event)
    {
        $event = Event::findOrFail($event->id);
        $today = Carbon::today()->format('Y年m月d日');

        // ★早期return★
        // 本日より前のイベントのページを開いたら
        // 404ページを開くようにする
        if($event->eventDate < $today){
            return abort(404);
        }

        $eventDate = $event->editEventDate;
        $startTime = $event->startTime;
        $endTime = $event->endTime;

        return view('manager.events.edit',
        compact('event', 'eventDate', 'startTime', 'endTime'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateEventRequest  $request
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateEventRequest $request, Event $event)
    {
        $check = EventService::countEventDuplication(
            $request['event_date'],$request['start_time'],$request['end_time']);

            if($check > 1){
                $event = Event::findOrFail($event->id);
                $eventDate = $event->editEventDate;
                $startTime = $event->startTime;
                $endTime = $event->endTime;
                session()->flash('status', 'この時間帯は既に他の予約が存在します。');
                return view('manager.events.edit', 
                compact('event', 'eventDate', 'startTime', 'endTime'));
            }

            $startDate = EventService::joinDateAndTime($request['event_date'],$request['start_time']);
            $endDate = EventService::joinDateAndTime($request['event_date'],$request['end_time']);

            $event->name = $request['event_name'];
            $event->information = $request['information'];
            $event->start_date = $startDate;
            $event->end_date = $endDate;
            $event->max_people = $request['max_people'];
            $event->is_visible = $request['is_visible'];
            $event->save();

            session()->flash('status', '内容を変更しました'); 
            return to_route('events.index');
    }

    public function past()
    {
        // 今日の日付取得
        $today = Carbon::today();

        $todays=Carbon::parse($today);

        $reservedPeople = DB::table('reservations')
        ->select('event_id', DB::raw('sum(number_of_people) as number_of_people'))
        ->groupBy('event_id');

        $events = DB::table('events')
        ->leftJoinSub($reservedPeople, 'reservedPeople', function($join){
            $join->on('events.id', '=', 'reservedPeople.event_id');
        })

        // 今日より前の日の日付を取得
        ->whereDate('start_date', '<', $today)
        ->orderBy('start_date', 'desc')
        ->paginate(10);

        return view('manager.events.past', 
        compact('events'));

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function destroy(Event $event)
    {
        //
    }
}
