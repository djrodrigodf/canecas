@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.venda.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.vendas.update", [$venda->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="cliente">{{ trans('cruds.venda.fields.cliente') }}</label>
                <input class="form-control {{ $errors->has('cliente') ? 'is-invalid' : '' }}" type="text" name="cliente" id="cliente" value="{{ old('cliente', $venda->cliente) }}" required>
                @if($errors->has('cliente'))
                    <span class="text-danger">{{ $errors->first('cliente') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.venda.fields.cliente_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required">{{ trans('cruds.venda.fields.produto') }}</label>
                <select class="form-control {{ $errors->has('produto') ? 'is-invalid' : '' }}" name="produto" id="produto" required>
                    <option value disabled {{ old('produto', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Venda::PRODUTO_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('produto', $venda->produto) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('produto'))
                    <span class="text-danger">{{ $errors->first('produto') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.venda.fields.produto_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="frase">{{ trans('cruds.venda.fields.frase') }}</label>
                <textarea class="form-control {{ $errors->has('frase') ? 'is-invalid' : '' }}" name="frase" id="frase">{{ old('frase', $venda->frase) }}</textarea>
                @if($errors->has('frase'))
                    <span class="text-danger">{{ $errors->first('frase') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.venda.fields.frase_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="valor">{{ trans('cruds.venda.fields.valor') }}</label>
                <input class="form-control {{ $errors->has('valor') ? 'is-invalid' : '' }}" type="number" name="valor" id="valor" value="{{ old('valor', $venda->valor) }}" step="0.01">
                @if($errors->has('valor'))
                    <span class="text-danger">{{ $errors->first('valor') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.venda.fields.valor_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required">{{ trans('cruds.venda.fields.pago') }}</label>
                <select class="form-control {{ $errors->has('pago') ? 'is-invalid' : '' }}" name="pago" id="pago" required>
                    <option value disabled {{ old('pago', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Venda::PAGO_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('pago', $venda->pago) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('pago'))
                    <span class="text-danger">{{ $errors->first('pago') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.venda.fields.pago_helper') }}</span>
            </div>
            <div class="form-group">
                <label>{{ trans('cruds.venda.fields.entregue') }}</label>
                <select class="form-control {{ $errors->has('entregue') ? 'is-invalid' : '' }}" name="entregue" id="entregue">
                    <option value disabled {{ old('entregue', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Venda::ENTREGUE_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('entregue', $venda->entregue) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('entregue'))
                    <span class="text-danger">{{ $errors->first('entregue') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.venda.fields.entregue_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="foto">{{ trans('cruds.venda.fields.foto') }}</label>
                <div class="needsclick dropzone {{ $errors->has('foto') ? 'is-invalid' : '' }}" id="foto-dropzone">
                </div>
                @if($errors->has('foto'))
                    <span class="text-danger">{{ $errors->first('foto') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.venda.fields.foto_helper') }}</span>
            </div>
            <div class="form-group">
                <button class="btn btn-danger" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>



@endsection

@section('scripts')
<script>
    var uploadedFotoMap = {}
Dropzone.options.fotoDropzone = {
    url: '{{ route('admin.vendas.storeMedia') }}',
    maxFilesize: 22, // MB
    acceptedFiles: '.jpeg,.jpg,.png,.gif',
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 22,
      width: 4096,
      height: 4096
    },
    success: function (file, response) {
      $('form').append('<input type="hidden" name="foto[]" value="' + response.name + '">')
      uploadedFotoMap[file.name] = response.name
    },
    removedfile: function (file) {
      console.log(file)
      file.previewElement.remove()
      var name = ''
      if (typeof file.file_name !== 'undefined') {
        name = file.file_name
      } else {
        name = uploadedFotoMap[file.name]
      }
      $('form').find('input[name="foto[]"][value="' + name + '"]').remove()
    },
    init: function () {
@if(isset($venda) && $venda->foto)
      var files = {!! json_encode($venda->foto) !!}
          for (var i in files) {
          var file = files[i]
          this.options.addedfile.call(this, file)
          this.options.thumbnail.call(this, file, file.preview)
          file.previewElement.classList.add('dz-complete')
          $('form').append('<input type="hidden" name="foto[]" value="' + file.file_name + '">')
        }
@endif
    },
     error: function (file, response) {
         if ($.type(response) === 'string') {
             var message = response //dropzone sends it's own error messages in string
         } else {
             var message = response.errors.file
         }
         file.previewElement.classList.add('dz-error')
         _ref = file.previewElement.querySelectorAll('[data-dz-errormessage]')
         _results = []
         for (_i = 0, _len = _ref.length; _i < _len; _i++) {
             node = _ref[_i]
             _results.push(node.textContent = message)
         }

         return _results
     }
}
</script>
@endsection