<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Employee;
use Illuminate\Console\Command;

class CheckEmployeeRetirementCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pegawai:check-pensiun';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Pemeriksaan otomatis usia pensiun pegawai (58 tahun) dan pembaruan status kepegawaian';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Memulai pemeriksaan status pensiun pegawai...');

        $employees = Employee::whereNotNull('birth_date')->get();
        $processedCount = 0;

        foreach ($employees as $employee) {
            if ($employee->is_sudah_pensiun) {
                $employee->checkAndUpdateStatusPensiun();
                $processedCount++;
                $this->line("Pegawai {$employee->full_name} (NIP: {$employee->employee_number}) - Tanggal Pensiun: " . $employee->tanggal_pensiun_otomatis?->format('d/m/Y') . " -> STATUS PENSIUN.");
            }
        }

        $this->info("Pemeriksaan selesai. Total {$processedCount} pegawai yang mencapai/melewati usia pensiun (58 tahun) telah diproses.");

        return Command::SUCCESS;
    }
}
