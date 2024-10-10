<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommentPost extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'firm_id' => 'required|numeric',
            'city_id' => 'required|numeric',
            'user'    => 'required|max:255',
            'comment' => 'required|min:30|max:4048',
            'score'   => 'numeric|max:5|min:1',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'user.required'  => 'Введите имя',
            'text.required'  => 'Напишите несколько слов о компании',
            'comment.min'    => 'Напишите больше',
            'comment.max'    => 'Отзыв слишком большой',
            'score.required' => 'Укажите оценку от 1 до 5',
            'score.max'      => 'Хакер?',
            'score.min'      => 'Хакер?',
        ];
    }


}
