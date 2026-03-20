<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
class CreateAttendancesTable extends Migration {
    public function up() {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->enum('status', ['present','absent','late','half_day','holiday'])->default('absent');
            $table->time('punch_in')->nullable();
            $table->time('punch_out')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
            $table->unique(['employee_id','date']);
        });
    }
    public function down() { Schema::dropIfExists('attendances'); }
}