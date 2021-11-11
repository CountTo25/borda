<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBoardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('boards', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->string('name')->nullable();
            $table->string('short_name');

            $table->boolean('is_hidden')->default(0);
            $table->boolean('is_readonly')->default(0);
            $table->boolean('is_text_only')->default(0);

            $table->string('default_username')->default(config('defaults.username') ?? 'Аноним');
            $table->integer('bump_limit')->default(500);
            $table->integer('max_threads')->nullable()->default(100);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('boards');
    }
}
