<?php

namespace App\Jobs;

use App\Models\Crew;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ImportCrewJob implements ShouldQueue
{
    use Queueable;

    public $rows;

    /**
     * Create a new job instance.
     */
    public function __construct($rows)
    {
        $this->rows = $rows;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $data = [];

        foreach ($this->rows as $row) {
            $data[] = [
                'rank_id' => $row[0],
                'first_name' => $row[1],
                'middle_name' => $row[2],
                'last_name' => $row[3],
                'address' => $row[4],
                'birth_date' => $row[5],
                'email' => $row[6],
                'weight' => $row[7],
                'height' => $row[8],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        Crew::query()->insert($data);
    }
}
