<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Archive extends Model
{
    protected $table = 'archives';

    protected $fillable = [
        'title',
        'date',
        'role_id',
        'archive_type_id',
        'description'
    ];

    public function archive_type()
    {
        return $this->belongsTo(ArchiveType::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }
}
