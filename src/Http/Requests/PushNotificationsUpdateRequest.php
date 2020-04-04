<?php

namespace WooSignal\LaraApp\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PushNotificationsUpdateRequest extends FormRequest
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
            'push_settings' => 'required|string|max:255'
        ];
    }
}
