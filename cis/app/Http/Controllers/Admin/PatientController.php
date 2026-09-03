<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    /**
     * Display a listing of patients with search and balance summary.
     */
    public function index(Request $request)
    {
        $query = Patient::with(['invoices'])->latest();

        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('id_number', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $perPage = (int) $request->input('per_page', 10);
        $patients = $query->paginate($perPage)->withQueryString();

        $stats = [
            'total_patients' => Patient::count(),
            'with_allergies' => Patient::whereNotNull('allergies')->where('allergies', '!=', '')->where('allergies', '!=', 'None')->count(),
            'total_unpaid_balance' => Patient::all()->sum(fn ($p) => $p->outstanding_balance),
        ];

        return view('admin.patients.index', compact('patients', 'stats'));
    }

    /**
     * Show form to create a new patient.
     */
    public function create()
    {
        return view('admin.patients.create');
    }

    /**
     * Store newly created patient record.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'id_number' => ['required', 'string', 'max:50', 'unique:patients,id_number'],
            'phone' => ['required', 'string', 'max:50'],
            'date_of_birth' => ['nullable', 'date'],
            'gender' => ['nullable', 'string'],
            'allergies' => ['nullable', 'string'],
            'address' => ['nullable', 'string'],
        ]);

        $patient = Patient::create($validated);

        return redirect()->route('admin.patients.show', $patient->id)
            ->with('success', "Patient record for '{$patient->name}' registered successfully.");
    }

    /**
     * Display patient profile with billing history.
     */
    public function show(Patient $patient)
    {
        $patient->load(['invoices.items', 'invoices.payments']);
        return view('admin.patients.show', compact('patient'));
    }

    /**
     * Show form to edit patient profile.
     */
    public function edit(Patient $patient)
    {
        return view('admin.patients.edit', compact('patient'));
    }

    /**
     * Update patient record.
     */
    public function update(Request $request, Patient $patient)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'id_number' => ['required', 'string', 'max:50', 'unique:patients,id_number,' . $patient->id],
            'phone' => ['required', 'string', 'max:50'],
            'date_of_birth' => ['nullable', 'date'],
            'gender' => ['nullable', 'string'],
            'allergies' => ['nullable', 'string'],
            'address' => ['nullable', 'string'],
        ]);

        $patient->update($validated);

        return redirect()->route('admin.patients.show', $patient->id)
            ->with('success', "Patient record updated successfully.");
    }

    /**
     * Delete patient record if no invoices attached.
     */
    public function destroy(Patient $patient)
    {
        if ($patient->invoices()->exists()) {
            return back()->with('error', 'Cannot delete patient record with associated invoices. Archive or void invoices first.');
        }

        $patient->delete();
        return redirect()->route('admin.patients.index')->with('success', 'Patient record deleted successfully.');
    }
}
