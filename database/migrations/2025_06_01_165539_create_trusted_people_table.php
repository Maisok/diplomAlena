<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrustedPeopleTable extends Migration
{
    public function up()
    {
        Schema::create('trusted_people', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->constrained('users')->onDelete('cascade');
            $table->string('last_name', 50);
            $table->string('first_name', 50);
            $table->string('patronymic', 50)->nullable();
            $table->string('phone_number', 20)->unique()->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('trusted_people');
    }
}