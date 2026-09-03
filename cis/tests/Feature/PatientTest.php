<?php

namespace Tests\Feature;

use App\Models\Patient;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PatientTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['role' => 'admin']);
    }

    public function test_patients_index_renders_and_lists_records(): void
    {
        $this->actingAs($this->user);

        Patient::create([
            'name' => 'Muhammad Hafiz',
            'id_number' => '880120-10-5321',
            'phone' => '+60178899001',
        ]);

        $response = $this->get(route('admin.patients.index'));
        $response->assertStatus(200);
        $response->assertSee('Muhammad Hafiz');
        $response->assertSee('880120-10-5321');
    }

    public function test_patient_create_form_renders(): void
    {
        $this->actingAs($this->user);
        $response = $this->get(route('admin.patients.create'));
        $response->assertStatus(200);
        $response->assertSee('Register New Patient');
    }

    public function test_can_create_patient_record(): void
    {
        $this->actingAs($this->user);

        $payload = [
            'name' => 'Chong Wei Lun',
            'id_number' => '950715-08-6119',
            'phone' => '+60163322114',
            'date_of_birth' => '1995-07-15',
            'gender' => 'Male',
            'allergies' => 'Penicillin',
            'address' => 'Unit 12-03, Petaling Jaya',
        ];

        $response = $this->post(route('admin.patients.store'), $payload);
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('patients', [
            'name' => 'Chong Wei Lun',
            'id_number' => '950715-08-6119',
            'allergies' => 'Penicillin',
        ]);
    }

    public function test_can_show_patient_profile_and_billing_history(): void
    {
        $this->actingAs($this->user);

        $patient = Patient::create([
            'name' => 'Aisha Tan',
            'id_number' => '940312-01-4455',
            'phone' => '+60123344556',
            'allergies' => 'Sulfa',
        ]);

        $response = $this->get(route('admin.patients.show', $patient->id));
        $response->assertStatus(200);
        $response->assertSee('Aisha Tan');
        $response->assertSee('Allergy: Sulfa');
    }

    public function test_patient_edit_form_renders(): void
    {
        $this->actingAs($this->user);

        $patient = Patient::create([
            'name' => 'Aisha Tan',
            'id_number' => '940312-01-4455',
            'phone' => '+60123344556',
        ]);

        $response = $this->get(route('admin.patients.edit', $patient->id));
        $response->assertStatus(200);
        $response->assertSee('Edit Patient Record');
    }

    public function test_can_update_patient_record(): void
    {
        $this->actingAs($this->user);

        $patient = Patient::create([
            'name' => 'Aisha Tan',
            'id_number' => '940312-01-4455',
            'phone' => '+60123344556',
        ]);

        $response = $this->put(route('admin.patients.update', $patient->id), [
            'name' => 'Aisha Tan Abdullah',
            'id_number' => '940312-01-4455',
            'phone' => '+60129998877',
            'gender' => 'Female',
            'allergies' => 'None',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('patients', [
            'id' => $patient->id,
            'name' => 'Aisha Tan Abdullah',
            'phone' => '+60129998877',
        ]);
    }

    public function test_can_delete_patient_without_invoices(): void
    {
        $this->actingAs($this->user);

        $patient = Patient::create([
            'name' => 'Temporary Patient',
            'id_number' => '990101-10-9999',
            'phone' => '+60120000000',
        ]);

        $response = $this->delete(route('admin.patients.destroy', $patient->id));
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('patients', [
            'id' => $patient->id,
        ]);
    }
}
