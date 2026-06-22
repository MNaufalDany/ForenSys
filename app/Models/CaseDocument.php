<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CaseDocument extends Model
{
    protected $fillable = [
        'case_id',
        'document_name',
        'document_type',
        'file_path',
        'uploaded_by',
    ];

    public function case()
    {
        return $this->belongsTo(AutopsyCase::class, 'case_id');
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function documents()
    {
        return $this->hasMany(CaseDocument::class, 'case_id');
    }

}
