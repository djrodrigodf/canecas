<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;
use \DateTimeInterface;

class Venda extends Model implements HasMedia
{
    use SoftDeletes, HasMediaTrait;

    public $table = 'vendas';

    protected $appends = [
        'foto',
    ];

    const PAGO_SELECT = [
        'Sim' => 'Sim',
        'Nao' => 'Não',
    ];

    const ENTREGUE_SELECT = [
        'Sim' => 'Sim',
        'Nao' => 'Não',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'cliente',
        'produto',
        'frase',
        'valor',
        'pago',
        'entregue',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    const PRODUTO_SELECT = [
        'Caneca Branca'        => 'Caneca Branca',
        'Caneca Preta'         => 'Caneca Preta',
        'Caneca Alça colorida' => 'Caneca Alça colorida',
        'Caneca c/ Colher'     => 'Caneca c/ Colher',
        'Camisa'               => 'Camisa',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function registerMediaConversions(Media $media = null)
    {
        $this->addMediaConversion('thumb')->fit('crop', 50, 50);
        $this->addMediaConversion('preview')->fit('crop', 120, 120);
    }

    public function getFotoAttribute()
    {
        $files = $this->getMedia('foto');
        $files->each(function ($item) {
            $item->url       = $item->getUrl();
            $item->thumbnail = $item->getUrl('thumb');
            $item->preview   = $item->getUrl('preview');
        });

        return $files;
    }
}
