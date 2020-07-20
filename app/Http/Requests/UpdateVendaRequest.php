<?php

namespace App\Http\Requests;

use App\Venda;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class UpdateVendaRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('venda_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'cliente' => [
                'required',
            ],
            'produto' => [
                'required',
            ],
            'pago'    => [
                'required',
            ],
        ];
    }
}
