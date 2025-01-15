<?php

namespace Dietrichxx\FileManager\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class File extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'path',
        'extension'
    ];

    protected $appends = ['url'];

    public function getUrlAttribute(): string
    {
        return Storage::url("{$this->path}/{$this->title}.{$this->extension}");
    }
}
