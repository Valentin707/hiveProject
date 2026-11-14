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
        Schema::table('applications', function (Blueprint $table): void {
            $table->string('oauth_client_id')->nullable()->index();
        });

        Schema::create('application_user', function (Blueprint $table): void {
            $table->foreignUuid('application_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('user_id')->constrained()->cascadeOnDelete();
            $table->primary(['application_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('application_user');

        Schema::table('applications', function (Blueprint $table): void {
            $table->dropIndex(['oauth_client_id']);
            $table->dropColumn('oauth_client_id');
        });
    }
};
