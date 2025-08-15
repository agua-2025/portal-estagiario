<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PublicDocument extends Model
{
    protected $fillable = [
        'title','type','file_path','file_size','published_at','is_published','downloads',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'is_published' => 'boolean',
        'downloads'    => 'integer',
    ];


    // Extensão (PDF, DOCX, etc.)
    public function getExtAttribute(): string
    {
        $path = $this->file_path ?? '';
        $ext  = pathinfo($path, PATHINFO_EXTENSION);
        return strtoupper($ext ?: '');
    }

    // Tamanho humano (ex.: "2.5 MB")
    public function getSizeHumanAttribute(): ?string
    {
        if (!$this->file_size) return null;
        $bytes = (int) $this->file_size;
        $u = ['B','KB','MB','GB','TB']; $i = 0;
        while ($bytes >= 1024 && $i < count($u) - 1) { $bytes /= 1024; $i++; }
        return number_format($bytes, $i ? 1 : 0).' '.$u[$i];
    }

    // Nome sugerido para download (bonitinho)
    public function downloadFilename(): string
    {
        $ext = strtolower($this->ext ?: 'pdf');
        return Str::slug($this->title).'.'.$ext;
    }

    // Escopo: somente documentos já publicados
    public function scopePublished($q)
    {
        return $q->where('is_published', true)
                 ->whereNotNull('published_at')
                 ->where('published_at', '<=', now());
    }
}
