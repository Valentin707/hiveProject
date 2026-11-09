<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $userIdType = Schema::getColumnType('users', 'id');
        $requiresUuidConversion = in_array($userIdType, ['bigint', 'integer', 'int'], true);

        if ($requiresUuidConversion && DB::table('users')->exists()) {
            throw new RuntimeException(
                'Cannot convert the users table to UUIDs automatically because it contains users. Back up the database and migrate those IDs with their related records before continuing.'
            );
        }

        if ($requiresUuidConversion) {
            Schema::table('users', function (Blueprint $table): void {
                $table->unsignedBigInteger('id')->change();
            });

            Schema::table('users', function (Blueprint $table): void {
                $table->dropPrimary();
                $table->dropColumn('id');
            });

            Schema::table('users', function (Blueprint $table): void {
                $table->uuid('id')->primary()->first();
            });
        }

        if (! Schema::hasColumn('users', 'two_factor_secret')) {
            Schema::table('users', function (Blueprint $table): void {
                $table->text('two_factor_secret')->nullable();
            });
        }

        if (! Schema::hasColumn('users', 'two_factor_recovery_codes')) {
            Schema::table('users', function (Blueprint $table): void {
                $table->text('two_factor_recovery_codes')->nullable();
            });
        }

        if (! Schema::hasColumn('users', 'two_factor_confirmed_at')) {
            Schema::table('users', function (Blueprint $table): void {
                $table->timestamp('two_factor_confirmed_at')->nullable();
            });
        }

        if (
            Schema::hasTable('sessions')
            && Schema::hasColumn('sessions', 'user_id')
            && in_array(Schema::getColumnType('sessions', 'user_id'), ['bigint', 'integer', 'int'], true)
        ) {
            DB::table('sessions')->whereNotNull('user_id')->update(['user_id' => null]);

            Schema::table('sessions', function (Blueprint $table): void {
                $table->dropIndex(['user_id']);
            });

            Schema::table('sessions', function (Blueprint $table): void {
                $table->uuid('user_id')->nullable()->change();
            });

            Schema::table('sessions', function (Blueprint $table): void {
                $table->index('user_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        throw new RuntimeException('The UUID user ID conversion cannot be safely reversed automatically.');
    }
};
