<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\ClinicProfile;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * FR-6.1 & FR-6.2: Display and update clinic identity and invoicing defaults.
     */
    public function clinicProfile()
    {
        $profile = ClinicProfile::getActiveProfile();
        return view('admin.settings.profile', compact('profile'));
    }

    /**
     * Save clinic identity settings.
     */
    public function updateClinicProfile(Request $request)
    {
        $validated = $request->validate([
            'clinic_name' => ['required', 'string', 'max:255'],
            'registration_number' => ['required', 'string', 'max:100'],
            'phone' => ['required', 'string', 'max:50'],
            'email' => ['required', 'email', 'max:100'],
            'address' => ['required', 'string'],
            'currency_symbol' => ['required', 'string', 'max:10'],
            'default_tax_rate' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'invoice_terms' => ['nullable', 'string'],
            'receipt_footer' => ['nullable', 'string'],
        ]);

        $profile = ClinicProfile::getActiveProfile();
        $profile->update($validated);

        AuditLog::log(
            'SETTINGS_UPDATED',
            'Governance',
            'CLINIC-PROFILE',
            "Updated clinic profile identity and receipt terms for {$profile->clinic_name}",
            $validated
        );

        return back()->with('success', 'Clinic identity and receipt parameters saved successfully.');
    }

    /**
     * Preview Invoice & Thermal Receipt Design Layouts.
     */
    public function invoiceTemplates()
    {
        $profile = ClinicProfile::getActiveProfile();
        return view('admin.settings.templates', compact('profile'));
    }

    /**
     * FR-7.1 & FR-7.2: Immutable Audit Trail Logs.
     */
    public function auditLogs(Request $request)
    {
        $query = AuditLog::with('user')->latest();

        if ($request->filled('module') && $request->module !== 'all') {
            $query->where('module', $request->module);
        }

        if ($request->filled('action') && $request->action !== 'all') {
            $query->where('action', $request->action);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('target_reference', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('user_name', 'like', "%{$search}%");
            });
        }

        $perPage = (int) $request->input('per_page', 15);
        $logs = $query->paginate($perPage)->withQueryString();

        $stats = [
            'total_logs' => AuditLog::count(),
            'sensitive_voids' => AuditLog::where('action', 'INVOICE_VOIDED')->count(),
            'governance_updates' => AuditLog::where('module', 'Governance')->count(),
        ];

        return view('admin.settings.audit-logs', compact('logs', 'stats'));
    }
}
