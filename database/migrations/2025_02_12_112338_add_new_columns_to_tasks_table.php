<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->boolean('isActive')->default(true)->after('dueDate');
            $table->boolean('isDeleted')->default(false)->after('isActive'); 
            $table->unsignedBigInteger('createdBy')->nullable()->after('isDeleted'); 
            $table->unsignedBigInteger('updatedBy')->nullable()->after('createdBy');
            $table->unsignedBigInteger('deletedBy')->nullable()->after('updatedBy'); 
            $table->timestamp('deleted_at')->nullable()->after('deletedBy');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn(['isActive', 'isDeleted', 'createdBy', 'updatedBy', 'deletedBy', 'deleted_at']);
        });
    }
};
