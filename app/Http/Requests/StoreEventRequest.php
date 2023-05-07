<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEventRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'event_name' => ['required', 'max:50'],
            'information' => ['required', 'max:200'],
            'event_date' => ['required', 'date'],
            'start_time' => ['required'],

            // after:をつけ、start_time(開始日)以降でないと登録できないようにする
            'end_time' => ['required', 'after:start_time'],

            // 1~20の間でmax_people(定員数)を設定する
            'max_people' => ['required', 'numeric', 'between:1,20'],

            //eventを表示・非表示を選択する
            'is_visible' => ['required', 'boolean']
        ];
    }
}
