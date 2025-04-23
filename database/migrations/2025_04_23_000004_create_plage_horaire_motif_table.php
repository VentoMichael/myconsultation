<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('plage_horaire_motif', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plage_horaire_id')->constrained('plage_horaires')->onDelete('cascade');
            $table->foreignId('motif_id')->constrained('motifs')->onDelete('cascade');
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('plage_horaire_motif');
    }
};
