<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AutopsyCase extends Model
{
    protected $table = 'autopsy_cases';

    protected $fillable = [
        'case_number',
        'victim_name',
        'gender',
        'age',
        'date_received',
        'case_type',
        'description',
        'referring_institution',
        'status',
        'registration_officer_id',
        'assigned_doctor_id',
    ];

    public function assignedDoctor()
    {
        return $this->belongsTo(User::class, 'assigned_doctor_id');
    }

    public function registrationOfficer()
    {
        return $this->belongsTo(User::class, 'registration_officer_id');
    }

        public function documents()
    {
        return $this->hasMany(CaseDocument::class, 'case_id');
    }

    public function examination()
    {
        return $this->hasOne(\App\Models\AutopsyExamination::class, 'case_id');
    }

}
