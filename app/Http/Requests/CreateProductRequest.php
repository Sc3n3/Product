<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateProductRequest extends FormRequest
{
    /**
     * @var string
     */
    protected $signature;

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
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'signature' => hash_hmac('sha256', $this->content, $this->user()->getApiSecret())
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required',
            'description' => 'required',
            'price' => 'required',
            'signature' => function($a, $v, $f){
                if ($v !== $this->header('hash')) {
                    $f($a .' mismatch!');
                }
            }
        ];
    }
}
