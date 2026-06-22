<?php

use App\Http\Controllers\CaseController;
use App\Models\AutopsyCase;
use App\Models\AutopsyExamination;
use App\Models\User;
use Illuminate\Http\Request;

function makeForensysUser(string $role): User
{
    return User::create([
        'name' => ucfirst($role) . ' Test',
        'email' => $role . '_' . uniqid() . '@test.com',
        'password' => 'password',
        'role' => $role,
        'status' => 'active',
    ]);
}

function makeForensysCase(string $suffix, User $officer, User $doctor, string $caseType = 'accident'): AutopsyCase
{
    return AutopsyCase::create([
        'case_number' => 'CASE-' . strtoupper($suffix) . '-' . uniqid(),
        'victim_name' => 'Korban Test',
        'gender' => 'male',
        'age' => 35,
        'date_received' => now()->toDateString(),
        'case_type' => $caseType,
        'referring_institution' => 'Kepolisian',
        'description' => 'Kasus pengujian unit test',
        'status' => 'assigned',
        'registration_officer_id' => $officer->id,
        'assigned_doctor_id' => $doctor->id,
    ]);
}

test('P1 - submitted dengan toxicology pending dicentang', function () {
    $officer = makeForensysUser('register');
    $doctor = makeForensysUser('dokter');
    $case = makeForensysCase('p1', $officer, $doctor, 'accident');

    $request = Request::create("/cases/{$case->id}/examination", 'POST', [
        'manner_of_death' => 'Accident',
        'toxicology_pending' => 'on',
        'status' => 'Submitted',
    ]);

    (new CaseController())->saveExamination($request, $case->id);

    $case->refresh();
    $examination = AutopsyExamination::where('case_id', $case->id)->first();

    expect($case->status)->toBe('completed')
        ->and($examination)->not->toBeNull()
        ->and(strtolower($examination->status))->toBe('submitted')
        ->and($examination->submitted_at)->not->toBeNull()
        ->and($examination->cause_of_death)->toContain('Toxicology Pending: Yes');
});

test('P2 - submitted tanpa toxicology pending', function () {
    $officer = makeForensysUser('register');
    $doctor = makeForensysUser('dokter');
    $case = makeForensysCase('p2', $officer, $doctor, 'accident');

    $request = Request::create("/cases/{$case->id}/examination", 'POST', [
        'manner_of_death' => 'Accident',
        'status' => 'Submitted',
    ]);

    (new CaseController())->saveExamination($request, $case->id);

    $case->refresh();
    $examination = AutopsyExamination::where('case_id', $case->id)->first();

    expect($case->status)->toBe('completed')
        ->and($examination)->not->toBeNull()
        ->and(strtolower($examination->status))->toBe('submitted')
        ->and($examination->submitted_at)->not->toBeNull()
        ->and($examination->cause_of_death)->toContain('Toxicology Pending: No');
});

test('P3 - submitted dengan data temuan dan penyebab kematian lengkap', function () {
    $officer = makeForensysUser('register');
    $doctor = makeForensysUser('dokter');
    $case = makeForensysCase('p3', $officer, $doctor, 'criminal');

    $request = Request::create("/cases/{$case->id}/examination", 'POST', [
        'finding_1' => 'Patah tulang rusuk',
        'finding_2' => 'Paru-paru robek',
        'immediate_cause' => 'Trauma tumpul',
        'underlying_cause' => 'Kecelakaan kerja',
        'significant_conditions' => 'Tidak ada',
        'manner_of_death' => 'Accident',
        'status' => 'Submitted',
    ]);

    (new CaseController())->saveExamination($request, $case->id);

    $case->refresh();
    $examination = AutopsyExamination::where('case_id', $case->id)->first();

    expect($case->status)->toBe('completed')
        ->and($examination)->not->toBeNull()
        ->and(strtolower($examination->status))->toBe('submitted')
        ->and($examination->submitted_at)->not->toBeNull()
        ->and($examination->findings)->toContain('Patah tulang rusuk')
        ->and($examination->cause_of_death)->toContain('Trauma tumpul')
        ->and($examination->cause_of_death)->toContain('Kecelakaan kerja');
});
