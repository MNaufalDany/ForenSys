<?php

use App\Models\AutopsyCase;
use App\Models\User;

function createFeatureUser(string $role): User
{
    return User::create([
        'name' => ucfirst($role) . ' Feature Test',
        'email' => $role . '_' . uniqid() . '@test.com',
        'password' => 'password',
        'role' => $role,
        'status' => 'active',
    ]);
}

function createFeatureCase(User $officer, User $doctor, ?string $caseNumber = null): AutopsyCase
{
    return AutopsyCase::create([
        'case_number' => $caseNumber ?? 'CASE-FEATURE-' . uniqid(),
        'victim_name' => 'Budi Santoso',
        'gender' => 'male',
        'age' => 35,
        'date_received' => now()->toDateString(),
        'case_type' => 'accident',
        'referring_institution' => 'Kepolisian',
        'description' => 'Kasus feature test',
        'status' => 'assigned',
        'registration_officer_id' => $officer->id,
        'assigned_doctor_id' => $doctor->id,
    ]);
}

test('EP Create Case - data valid berhasil disimpan', function () {
    $officer = createFeatureUser('register');
    $doctor = createFeatureUser('dokter');
    $caseNumber = 'CASE-CREATE-' . uniqid();

    $response = $this
        ->withSession(['user.id' => $officer->id])
        ->post(route('cases.store'), [
            'case_number' => $caseNumber,
            'victim_name' => 'Budi Santoso',
            'gender' => 'male',
            'age' => 35,
            'date_received' => now()->toDateString(),
            'case_type' => 'accident',
            'assigned_doctor_id' => $doctor->id,
            'referring_institution' => 'Kepolisian',
            'description' => 'Kasus valid',
        ]);

    $response->assertRedirect(route('cases.index'));

    $this->assertDatabaseHas('autopsy_cases', [
        'case_number' => $caseNumber,
        'victim_name' => 'Budi Santoso',
    ]);
});

test('EP Create Case - case number kosong gagal validasi', function () {
    $officer = createFeatureUser('register');
    $doctor = createFeatureUser('dokter');

    $response = $this
        ->withSession(['user.id' => $officer->id])
        ->from(route('cases.create'))
        ->post(route('cases.store'), [
            'case_number' => '',
            'victim_name' => 'Budi Santoso',
            'gender' => 'male',
            'age' => 35,
            'date_received' => now()->toDateString(),
            'case_type' => 'accident',
            'assigned_doctor_id' => $doctor->id,
            'description' => 'Case number kosong',
        ]);

    $response->assertSessionHasErrors('case_number');
});

test('EP Create Case - case number duplikat gagal validasi', function () {
    $officer = createFeatureUser('register');
    $doctor = createFeatureUser('dokter');
    $caseNumber = 'CASE-DUPLICATE-' . uniqid();

    createFeatureCase($officer, $doctor, $caseNumber);

    $response = $this
        ->withSession(['user.id' => $officer->id])
        ->from(route('cases.create'))
        ->post(route('cases.store'), [
            'case_number' => $caseNumber,
            'victim_name' => 'Korban Baru',
            'gender' => 'male',
            'age' => 40,
            'date_received' => now()->toDateString(),
            'case_type' => 'accident',
            'assigned_doctor_id' => $doctor->id,
            'description' => 'Case number duplikat',
        ]);

    $response->assertSessionHasErrors('case_number');
});

test('EP Search Case - nomor kasus ditemukan', function () {
    $officer = createFeatureUser('register');
    $doctor = createFeatureUser('dokter');
    $case = createFeatureCase($officer, $doctor, 'CASE-SEARCH-' . uniqid());

    $response = $this->get(route('cases.index', [
        'search' => $case->case_number,
    ]));

    $response->assertStatus(200);
    $response->assertSee($case->case_number);
});

test('EP Search Case - nama korban ditemukan', function () {
    $officer = createFeatureUser('register');
    $doctor = createFeatureUser('dokter');

    createFeatureCase($officer, $doctor);

    $response = $this->get(route('cases.index', [
        'search' => 'Budi',
    ]));

    $response->assertStatus(200);
    $response->assertSee('Budi Santoso');
});

test('EP Search Case - data tidak ditemukan', function () {
    $response = $this->get(route('cases.index', [
        'search' => 'XYZ999',
    ]));

    $response->assertStatus(200);
    $response->assertSee('No cases found.');
});

test('EP Save Examination - status Submitted berhasil tersubmit', function () {
    $officer = createFeatureUser('register');
    $doctor = createFeatureUser('dokter');
    $case = createFeatureCase($officer, $doctor);

    $response = $this->post(route('cases.examination.save', $case->id), [
        'manner_of_death' => 'Accident',
        'status' => 'Submitted',
    ]);

    $response->assertRedirect(route('cases.examination', $case->id));

    $this->assertDatabaseHas('autopsy_examinations', [
        'case_id' => $case->id,
    ]);

    $case->refresh();

    expect($case->status)->toBe('completed');
});

test('EP Save Examination - status di luar pilihan gagal validasi', function () {
    $officer = createFeatureUser('register');
    $doctor = createFeatureUser('dokter');
    $case = createFeatureCase($officer, $doctor);

    $response = $this
        ->from(route('cases.examination', $case->id))
        ->post(route('cases.examination.save', $case->id), [
            'manner_of_death' => 'Accident',
            'status' => 'Menyelesaikan',
        ]);

    $response->assertSessionHasErrors('status');
});

test('BVA Save Examination - manner of death lebih dari 100 karakter gagal validasi', function () {
    $officer = createFeatureUser('register');
    $doctor = createFeatureUser('dokter');
    $case = createFeatureCase($officer, $doctor);

    $response = $this
        ->from(route('cases.examination', $case->id))
        ->post(route('cases.examination.save', $case->id), [
            'manner_of_death' => str_repeat('A', 101),
            'status' => 'Submitted',
        ]);

    $response->assertSessionHasErrors('manner_of_death');
});
