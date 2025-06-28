<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn('class'); // Remove old

            // Add new fields
        
            $table->string('student_id')->nullable()->unique()->after('id');
            $table->string('department')->nullable();
            $table->string('program')->nullable();
            $table->string('year_level')->nullable();
            $table->string('gender')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->year('enrollment_year')->nullable();
            $table->string('address')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn([
                'student_id',
                'department',
                'program',
                'year_level',
                'gender',
                'date_of_birth',
                'enrollment_year',
                'address'
            ]);

            $table->string('class')->nullable(); // Revert if needed
        });
    }
    
};
