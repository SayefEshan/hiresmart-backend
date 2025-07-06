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
        // Users table indexes
        Schema::table('users', function (Blueprint $table) {
            $table->index(['is_verified', 'created_at']);
        });

        // Job listings table indexes - MOST IMPORTANT
        Schema::table('job_listings', function (Blueprint $table) {
            $table->index(['is_active', 'is_archived', 'created_at']); // For public job listings
            $table->index(['user_id', 'is_active']); // For employer's active jobs
        });

        // Applications table indexes - CRITICAL
        Schema::table('applications', function (Blueprint $table) {
            $table->index(['job_listing_id', 'status']); // For employer viewing applications
            $table->index(['user_id', 'created_at']); // For candidate's applications
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Users table indexes
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['is_verified', 'created_at']);
        });

        // Job listings table indexes
        Schema::table('job_listings', function (Blueprint $table) {
            $table->dropIndex(['is_active', 'is_archived', 'created_at']);
            $table->dropIndex(['user_id', 'is_active']);
        });

        // Applications table indexes
        Schema::table('applications', function (Blueprint $table) {
            $table->dropIndex(['job_listing_id', 'status']);
            $table->dropIndex(['user_id', 'created_at']);
        });
    }
};
