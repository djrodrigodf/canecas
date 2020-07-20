@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.venda.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.vendas.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.venda.fields.id') }}
                        </th>
                        <td>
                            {{ $venda->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.venda.fields.cliente') }}
                        </th>
                        <td>
                            {{ $venda->cliente }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.venda.fields.produto') }}
                        </th>
                        <td>
                            {{ App\Venda::PRODUTO_SELECT[$venda->produto] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.venda.fields.frase') }}
                        </th>
                        <td>
                            {{ $venda->frase }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.venda.fields.valor') }}
                        </th>
                        <td>
                            {{ $venda->valor }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.venda.fields.pago') }}
                        </th>
                        <td>
                            {{ App\Venda::PAGO_SELECT[$venda->pago] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.venda.fields.entregue') }}
                        </th>
                        <td>
                            {{ App\Venda::ENTREGUE_SELECT[$venda->entregue] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.venda.fields.foto') }}
                        </th>
                        <td>
                            @foreach($venda->foto as $key => $media)
                                <a href="{{ $media->getUrl() }}" target="_blank" style="display: inline-block">
                                    <img src="{{ $media->getUrl('thumb') }}">
                                </a>
                            @endforeach
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.vendas.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection