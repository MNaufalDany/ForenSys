<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AutopsyExamination extends Model
{
    protected $fillable = [
        'case_id',
        'external_examination',
        'internal_examination',
        'findings',
        'cause_of_death',
        'notes',
        'status',
        'submitted_at',
    ];

    public function case()
    {
        return $this->belongsTo(AutopsyCase::class, 'case_id');
    }

    public function examination()
    {
        return $this->hasOne(\App\Models\AutopsyExamination::class, 'case_id');
    }
}


