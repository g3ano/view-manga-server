<?php

use App\Models\v1\Team;
use App\Models\v1\User;
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
        Schema::create('team_user', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Team::class, 'team_id')
                ->constrained('teams')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreignIdFor(User::class, 'user_id')
                ->constrained('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->boolean('is_leader')->default(0);
            $table->enum('is_pending', [1, 2, 3])->default(2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('team_user');
    }
};
