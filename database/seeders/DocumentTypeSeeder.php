<?php

namespace Database\Seeders;

use App\Models\DocumentType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DocumentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $documentTypes = [
            [
                'name' => 'Philippine Passport',
                'created_at' => now(),
            ],
            [
                'name' => 'PSA Birth Certificate',
                'created_at' => now(),
            ],
            [
                'name' => 'Government ID',
                'created_at' => now(),
            ],
            [
                'name' => 'NBI Clearance',
                'created_at' => now(),
            ],
            [
                'name' => 'Seaman’s Book (SIRB)',
                'created_at' => now(),
            ],
            [
                'name' => 'STCW Basic Training (BT)',
                'created_at' => now(),
            ],
            [
                'name' => 'Certificates of Competency (CoC)',
                'created_at' => now(),
            ],
            [
                'name' => 'Pre-Employment Medical Exam (PEME)',
                'created_at' => now(),
            ],
            [
                'name' => 'Signed Employment Contract',
                'created_at' => now(),
            ],
            [
                'name' => 'Overseas Employment Certificate (OEC)',
                'created_at' => now(),
            ],
            [
                'name' => 'Visa',
                'created_at' => now(),
            ],
        ];

        DocumentType::query()->insert($documentTypes);
    }
}
