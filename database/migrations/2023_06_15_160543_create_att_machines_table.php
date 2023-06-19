<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('att_machines', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name',20);
            $table->string('ip',20);
            $table->integer('port');
            $table->string('created_by',20);
            $table->string('modified_by',20);


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('att_machines');
    }
};
