<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyVendaRequest;
use App\Http\Requests\StoreVendaRequest;
use App\Http\Requests\UpdateVendaRequest;
use App\Venda;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class VendasController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('venda_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $vendas = Venda::all();

        return view('admin.vendas.index', compact('vendas'));
    }

    public function create()
    {
        abort_if(Gate::denies('venda_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.vendas.create');
    }

    public function store(StoreVendaRequest $request)
    {
        $venda = Venda::create($request->all());

        foreach ($request->input('foto', []) as $file) {
            $venda->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('foto');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $venda->id]);
        }

        return redirect()->route('admin.vendas.index');
    }

    public function edit(Venda $venda)
    {
        abort_if(Gate::denies('venda_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.vendas.edit', compact('venda'));
    }

    public function update(UpdateVendaRequest $request, Venda $venda)
    {
        $venda->update($request->all());

        if (count($venda->foto) > 0) {
            foreach ($venda->foto as $media) {
                if (!in_array($media->file_name, $request->input('foto', []))) {
                    $media->delete();
                }
            }
        }

        $media = $venda->foto->pluck('file_name')->toArray();

        foreach ($request->input('foto', []) as $file) {
            if (count($media) === 0 || !in_array($file, $media)) {
                $venda->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('foto');
            }
        }

        return redirect()->route('admin.vendas.index');
    }

    public function show(Venda $venda)
    {
        abort_if(Gate::denies('venda_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.vendas.show', compact('venda'));
    }

    public function destroy(Venda $venda)
    {
        abort_if(Gate::denies('venda_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $venda->delete();

        return back();
    }

    public function massDestroy(MassDestroyVendaRequest $request)
    {
        Venda::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('venda_create') && Gate::denies('venda_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new Venda();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
