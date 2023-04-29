<?php

namespace App\Services;

use Illuminate\Support\Facades\DB; 
use Carbon\Carbon;

class EventService
{

    // ★Eventの重複チェック★
    // 開始日と終了時間の間に日時が被っているものがないか
    // チェックする
    public static function checkEventDuplication($eventDate, $startTime, $endTime)
    {
        return DB::table('events')
            ->whereDate('start_date', $eventDate)
            ->whereTime('end_date', '>', $startTime)
            ->whereTime('start_date', '<', $endTime)
            ->exists();
    }

    // 既にイベントが存在しているので、 
    // 重複しているのが1件なら問題なく、1件より多ければエラー
    public static function countEventDuplication($eventDate, $startTime, $endTime)
    {
        return DB::table('events')
            ->whereDate('start_date', $eventDate)
            ->whereTime('end_date', '>', $startTime)
            ->whereTime('start_date', '<', $endTime)
            ->count();
    }

    // イベントの日付と時間の文字列連結させ、
    // createFromFormatで$joinの日付を作成
    public static function joinDateAndTime($date, $time)
    {
        $join = $date. " " .  $time; 
        return Carbon::createFromFormat('Y-m-d H:i', $join);
    }

    public static function getWeekEvents($startDate, $endDate)
    {
        $reservedPeople = DB::table('reservations')
            ->select('event_id', DB::raw('sum(number_of_people) as number_of_people'))
            ->whereNotNull('canceled_date')
            ->groupBy('event_id');
    
            return DB::table('events')
            ->leftJoinSub($reservedPeople, 'reservedPeople', function($join){
                $join->on('events.id', '=', 'reservedPeople.event_id');
            })
            ->whereBetween('start_date', [$startDate, $endDate])
            ->orderBy('start_date', 'asc')
            ->get();
    }    

}