<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
class CreatePerformancesTable extends Migration {
    public function up() {
        Schema::create('performances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->string('task');
            $table->text('description')->nullable();
            $table->integer('rating')->nullable();
            $table->text('review')->nullable();
            $table->date('review_date')->nullable();
            $table->enum('status', ['assigned','in_progress','completed'])->default('assigned');
            $table->timestamps();
        });
    }
    public function down() { Schema::dropIfExists('performances'); }
}