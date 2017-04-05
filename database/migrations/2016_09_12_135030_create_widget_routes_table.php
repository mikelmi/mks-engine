<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWidgetRoutesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('widget_routes', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('widget_id');
            $table->string('route');
            $table->text('params')->nullable()->default(null);

            $table->index('route');

            $table->foreign('widget_id')->references('id')->on('widgets')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('widget_routes');
    }
}
