@extends('layouts.admin')
@section('content')
@can('venda_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.vendas.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.venda.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.venda.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-Venda">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.venda.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.venda.fields.cliente') }}
                        </th>
                        <th>
                            {{ trans('cruds.venda.fields.produto') }}
                        </th>
                        <th>
                            {{ trans('cruds.venda.fields.frase') }}
                        </th>
                        <th>
                            {{ trans('cruds.venda.fields.valor') }}
                        </th>
                        <th>
                            {{ trans('cruds.venda.fields.pago') }}
                        </th>
                        <th>
                            {{ trans('cruds.venda.fields.entregue') }}
                        </th>
                        <th>
                            {{ trans('cruds.venda.fields.foto') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($vendas as $key => $venda)
                        <tr data-entry-id="{{ $venda->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $venda->id ?? '' }}
                            </td>
                            <td>
                                {{ $venda->cliente ?? '' }}
                            </td>
                            <td>
                                {{ App\Venda::PRODUTO_SELECT[$venda->produto] ?? '' }}
                            </td>
                            <td>
                                {{ $venda->frase ?? '' }}
                            </td>
                            <td>
                                {{ $venda->valor ?? '' }}
                            </td>
                            <td>
                                {{ App\Venda::PAGO_SELECT[$venda->pago] ?? '' }}
                            </td>
                            <td>
                                {{ App\Venda::ENTREGUE_SELECT[$venda->entregue] ?? '' }}
                            </td>
                            <td>
                                @foreach($venda->foto as $key => $media)
                                    <a href="{{ $media->getUrl() }}" target="_blank" style="display: inline-block">
                                        <img src="{{ $media->getUrl('thumb') }}">
                                    </a>
                                @endforeach
                            </td>
                            <td>
                                @can('venda_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.vendas.show', $venda->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('venda_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.vendas.edit', $venda->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('venda_delete')
                                    <form action="{{ route('admin.vendas.destroy', $venda->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
                                    </form>
                                @endcan

                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>



@endsection
@section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('venda_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.vendas.massDestroy') }}",
    className: 'btn-danger',
    action: function (e, dt, node, config) {
      var ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
          return $(entry).data('entry-id')
      });

      if (ids.length === 0) {
        alert('{{ trans('global.datatables.zero_selected') }}')

        return
      }

      if (confirm('{{ trans('global.areYouSure') }}')) {
        $.ajax({
          headers: {'x-csrf-token': _token},
          method: 'POST',
          url: config.url,
          data: { ids: ids, _method: 'DELETE' }})
          .done(function () { location.reload() })
      }
    }
  }
  dtButtons.push(deleteButton)
@endcan

  $.extend(true, $.fn.dataTable.defaults, {
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 100,
  });
  let table = $('.datatable-Venda:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection