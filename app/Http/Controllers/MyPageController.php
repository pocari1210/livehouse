<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Event;
use App\Models\Reservation;
use Illuminate\Support\Facades\Auth; 
use App\Services\MyPageService; 

class MyPageController extends Controller
{
    public function index(){ 

        // ログインしているユーザー情報取得
        $user = User::findOrFail(Auth::id());

        // リレーションで紐づいたuserとeventの情報を取得
        // (イベント一覧を取得)
        $events = $user->events;
        $fromTodayEvents = MyPageService::reservedEvent($events, 'fromToday'); 
        $pastEvents = MyPageService::reservedEvent($events, 'past'); 

        return view('mypage/index', compact('fromTodayEvents', 'pastEvents')); 
    }

    public function show($id)
    {
        $event = Event::findOrFail($id);
        $reservation = Reservation::where('user_id', '=', Auth::id())
        ->where('event_id', '=', $id)
        ->latest()
        ->first();

        return view('mypage/show', compact('event', 'reservation'));
    }
    
    public function cancel($id)
    {
        $reservation = Reservation::where('user_id', '=', Auth::id())
        ->where('event_id', '=', $id)
        ->latest()
        ->first();

        // キャンセルをおこなった日時を取得する
        // (タイムスタンプと同じ形式で登録される)
        $reservation->canceled_date = Carbon::now()->format('Y-m-d H:i:s');
        $reservation->save();

        session()->flash('status', 'キャンセルできました');

        return to_route('dashboard');
    }

}