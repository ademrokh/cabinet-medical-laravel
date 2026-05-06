<?php

namespace App\Jobs;

use App\Models\Doctor;
use App\Models\PlanningSuggestion;
use App\Models\SmartPlanning;
use App\Models\User;
use App\Services\LlmService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;

class GeneratePlanningJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $doctorId;
    public array $patients;
    public array $availability;
    public string $date;

    public function __construct(int $doctorId, array $patients, array $availability, ?string $date = null)
    {
        $this->doctorId = $doctorId;
        $this->patients = $patients;
        $this->availability = $availability;
        $this->date = $date ?? now()->toDateString();
    }

    public function handle(LlmService $llm): void
    {
        $planning = SmartPlanning::create([
            'doctor_id' => $this->doctorId,
            'date' => $this->date,
            'status' => 'processing',
        ]);

        try {
            $doctor = Doctor::with('user', 'specialty')->findOrFail($this->doctorId);

            $payload = [
                'doctor' => [
                    'name' => $doctor->user->name,
                    'specialty' => $doctor->specialty?->name,
                ],
                'date' => $this->date,
                'patients' => $this->patients,
                'availability' => $this->availability,
            ];

            $result = $llm->generatePlanning($payload);

            $planning->update([
                'generated_plan' => $result,
                'status' => 'completed',
                'generated_at' => now(),
            ]);

            if (!empty($result['suggestions']) && is_array($result['suggestions'])) {
                foreach ($result['suggestions'] as $suggestion) {
                    $patientId = $this->resolvePatientId($suggestion['patient'] ?? null);

                    if (!$patientId || empty($suggestion['suggested_time'])) {
                        continue;
                    }

                    PlanningSuggestion::create([
                        'patient_id' => $patientId,
                        'suggested_time' => $suggestion['suggested_time'],
                        'priority' => $suggestion['priority'] ?? 'normal',
                        'validated' => false,
                    ]);
                }
            }
        } catch (Throwable $e) {
            $planning->update([
                'status' => 'failed',
            ]);

            throw $e;
        }
    }

    private function resolvePatientId(?string $patientName): ?int
    {
        if (!$patientName) {
            return null;
        }

        $patient = User::where('role', 'patient')
            ->where('name', $patientName)
            ->first();

        return $patient?->id;
    }
}
