<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WaitingListRequest extends FormRequest
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
        /**
         * "$this->client" é o parâmetro de declarado na rota: /clients/{client}
         */
        $id = $this->waitingList->id ?? null;

        return [
            'name'  => ['required', 'min:3', 'max:150'],
            'email' => ['required', 'email', "unique:waiting_lists,email,{$id},id"]
        ];
    }
}
