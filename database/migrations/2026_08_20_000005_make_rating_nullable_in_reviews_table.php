
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement(
            'ALTER TABLE reviews ALTER COLUMN rating DROP NOT NULL'
        );
    }

    public function down(): void
    {
        DB::statement(
            'ALTER TABLE reviews ALTER COLUMN rating SET NOT NULL'
        );
    }
};
