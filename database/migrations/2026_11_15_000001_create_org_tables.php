<?php

declare(strict_types=1);

use App\Models\Department;
use App\Models\Person;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('departments', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->nestedSet();
            $table->timestamps();
        });

        Schema::create('people', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamps();
        });

        // One timeline per person: where they sat, who they reported to,
        // what they were called and paid. Values are nullable because a
        // retraction writes an anti-row of nulls.
        Schema::create('assignments', function (Blueprint $table): void {
            $table->id();
            $table->bitemporalForeignFor(Person::class);
            $table->foreignIdFor(Department::class)->nullable()->constrained()->restrictOnDelete();
            $table->foreignIdFor(Person::class, 'manager_id')->nullable()->constrained('people')->restrictOnDelete();
            $table->string('title')->nullable();
            $table->unsignedInteger('salary')->nullable();
            $table->bitemporalPeriods();
            $table->timestamps();

            $table->preventBitemporalOverlaps(['person_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assignments');
        Schema::dropIfExists('people');
        Schema::dropIfExists('departments');
    }
};
