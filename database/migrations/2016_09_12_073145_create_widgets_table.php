<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWidgetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('widgets', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('name');
            $table->string('class');
            $table->string('title');
            $table->text('content')->nullable()->default(null);
            $table->string('position');
            $table->string('lang', 10)->nullable()->default(null);
            $table->integer('ordering')->default(0);
            $table->boolean('status')->default(true);
            $table->json('params')->nullable()->default(null);

            $table->index('name');
            $table->index('position');
            $table->index('lang');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('widgets');
    }
}
