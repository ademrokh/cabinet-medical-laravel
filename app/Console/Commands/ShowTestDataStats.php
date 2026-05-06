<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Doctor;
use App\Models\Appointment;
use App\Models\SmartPlanning;
use App\Models\PlanningSuggestion;

class ShowTestDataStats extends Command
{
    protected $signature = 'test-data:stats';
    protected $description = 'Display test data statistics';

    public function handle()
    {
        $this->info('=== TEST DATA SUMMARY ===');
        $this->newLine();

        $this->line('📊 Data Counts:');
        $this->info('  Patients: ' . User::where('role', 'patient')->count());
        $this->info('  Doctors: ' . Doctor::count());
        $this->info('  Appointments: ' . Appointment::count());
        $this->info('  Smart Plannings: ' . SmartPlanning::count());
        $this->info('  Planning Suggestions: ' . PlanningSuggestion::count());

        $this->newLine();
        $this->line('📈 Smart Planning Status Breakdown:');
        $statuses = SmartPlanning::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get();
        foreach ($statuses as $status) {
            $this->info('  ' . ucfirst($status->status) . ': ' . $status->count);
        }

        $this->newLine();
        $this->line('🎯 Planning Suggestions by Priority:');
        $priorities = PlanningSuggestion::selectRaw('priority, COUNT(*) as count')
            ->groupBy('priority')
            ->get();
        foreach ($priorities as $priority) {
            $icon = match ($priority->priority) {
                'urgent' => '🔴',
                'normal' => '🟡',
                'low' => '🟢',
                default => '⚪'
            };
            $this->info('  ' . $icon . ' ' . ucfirst($priority->priority) . ': ' . $priority->count);
        }

        $this->newLine();
        $this->line('🏥 Sample Smart Planning:');
        $sp = SmartPlanning::with('doctor.user', 'doctor.specialty')->first();
        if ($sp) {
            $this->info('  ID: ' . $sp->id);
            $this->info('  Doctor: ' . $sp->doctor->user->name);
            $this->info('  Specialty: ' . ($sp->doctor->specialty->name ?? 'N/A'));
            $this->info('  Date: ' . $sp->date->format('Y-m-d'));
            $this->info('  Status: ' . ucfirst($sp->status));
            $this->info('  Appointments in plan: ' . count($sp->generated_plan['planning'] ?? []));

            if (!empty($sp->generated_plan['planning'])) {
                $this->newLine();
                $this->line('  📅 Sample appointments:');
                foreach (array_slice($sp->generated_plan['planning'], 0, 3) as $apt) {
                    $this->info('    • ' . $apt['time'] . ' - ' . $apt['patient'] . ' (' . $apt['priority'] . ')');
                }
            }
        }

        $this->newLine();
        $this->info('✅ Test data is ready for testing!');
        $this->info('See TEST_DATA_GUIDE.md for SQL queries and testing scenarios.');
    }
}
