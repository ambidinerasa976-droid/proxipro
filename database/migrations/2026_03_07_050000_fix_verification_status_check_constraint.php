<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Fix the status CHECK constraint on identity_verifications table.
 * SQLite enum() creates a CHECK constraint limiting values to 'pending', 'approved', 'rejected'.
 * We need to add 'returned' as a valid value.
 * 
 * In SQLite, you can't ALTER a CHECK constraint, so we recreate the table.
 */
return new class extends Migration
{
    public function up(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'sqlite') {
            // SQLite: must recreate the table to change CHECK constraint
            DB::statement('PRAGMA foreign_keys=OFF');

            // 1. Get all existing data
            $rows = DB::select('SELECT * FROM identity_verifications');

            // 2. Get current columns info
            $columns = DB::select("PRAGMA table_info('identity_verifications')");
            $colNames = array_map(fn($c) => $c->name, $columns);

            // 3. Rename current table
            DB::statement('ALTER TABLE identity_verifications RENAME TO _identity_verifications_old');

            // 4. Recreate with VARCHAR status (no CHECK constraint)
            $createSQL = DB::select("SELECT sql FROM sqlite_master WHERE type='table' AND name='_identity_verifications_old'");
            $sql = $createSQL[0]->sql ?? '';

            // Replace the old table creation with a new one using varchar instead of enum
            // Build the CREATE TABLE manually to be safe
            DB::statement("
                CREATE TABLE identity_verifications (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    user_id INTEGER NOT NULL,
                    document_type VARCHAR(255) NOT NULL,
                    document_front VARCHAR(255),
                    document_front_status VARCHAR(20) DEFAULT 'pending',
                    document_front_rejection_reason TEXT,
                    document_back VARCHAR(255),
                    document_back_status VARCHAR(20) DEFAULT 'pending',
                    document_back_rejection_reason TEXT,
                    selfie VARCHAR(255),
                    selfie_status VARCHAR(20) DEFAULT 'pending',
                    selfie_rejection_reason TEXT,
                    professional_document VARCHAR(255),
                    professional_document_type VARCHAR(30),
                    professional_document_status VARCHAR(20) DEFAULT 'pending',
                    professional_document_rejection_reason TEXT,
                    status VARCHAR(20) DEFAULT 'pending',
                    rejection_reason TEXT,
                    admin_message TEXT,
                    submitted_at DATETIME,
                    reviewed_at DATETIME,
                    reviewed_by INTEGER,
                    payment_id VARCHAR(255),
                    payment_amount DECIMAL(8,2) DEFAULT 0,
                    payment_status VARCHAR(20) DEFAULT 'pending',
                    payment_method VARCHAR(50),
                    created_at DATETIME,
                    updated_at DATETIME,
                    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
                    FOREIGN KEY (reviewed_by) REFERENCES users(id) ON DELETE SET NULL
                )
            ");

            // 5. Insert old data back, mapping column names properly
            foreach ($rows as $row) {
                $rowArray = (array) $row;
                // Only insert columns that exist in the new table
                $newCols = DB::select("PRAGMA table_info('identity_verifications')");
                $newColNames = array_map(fn($c) => $c->name, $newCols);

                $filteredData = [];
                foreach ($newColNames as $col) {
                    if (array_key_exists($col, $rowArray)) {
                        $filteredData[$col] = $rowArray[$col];
                    }
                }

                if (!empty($filteredData)) {
                    $placeholders = implode(', ', array_fill(0, count($filteredData), '?'));
                    $colList = implode(', ', array_map(fn($c) => "\"$c\"", array_keys($filteredData)));
                    DB::statement(
                        "INSERT INTO identity_verifications ($colList) VALUES ($placeholders)",
                        array_values($filteredData)
                    );
                }
            }

            // 6. Drop old table
            DB::statement('DROP TABLE _identity_verifications_old');

            DB::statement('PRAGMA foreign_keys=ON');
        } else {
            // MySQL: simply modify the column
            DB::statement("ALTER TABLE identity_verifications MODIFY COLUMN status VARCHAR(20) DEFAULT 'pending'");
        }
    }

    public function down(): void
    {
        // No rollback needed - VARCHAR is more permissive than enum
    }
};
