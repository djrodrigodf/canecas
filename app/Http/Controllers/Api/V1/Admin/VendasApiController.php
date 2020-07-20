<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreVendaRequest;
use App\Http\Requests\UpdateVendaRequest;
use App\Http\Resources\Admin\VendaResource;
use App\Venda;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VendasApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('venda_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new VendaResource(Venda::all());
    }

    public function store(StoreVendaRequest $request)
    {
        $venda = Venda::create($request->all());

        if ($request->input('foto', false)) {
            $venda->addMedia(storage_path('tmp/uploads/' . $request->input('foto')))->toMediaCollection('foto');
        }

        return (new VendaResource($venda))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Venda $venda)
    {
        abort_if(Gate::denies('venda_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new VendaResource($venda);
    }

    public function update(UpdateVendaRequest $request, Venda $venda)
    {
        $venda->update($request->all());

        if ($request->input('foto', false)) {
            if (!$venda->foto || $request->input('foto') !== $venda->foto->file_name) {
                if ($venda->foto) {
                    $venda->foto->delete();
                }

                $venda->addMedia(storage_path('tmp/uploads/' . $request->input('foto')))->toMediaCollection('foto');
            }
        } elseif ($venda->foto) {
            $venda->foto->delete();
        }

        return (new VendaResource($venda))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Venda $venda)
    {
        abort_if(Gate::denies('venda_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $venda->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
