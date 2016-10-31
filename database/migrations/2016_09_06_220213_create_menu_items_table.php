<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenuItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu_items', function (Blueprint $table) {
            $table->increments('id');

            \Kalnoy\Nestedset\NestedSet::columns($table);

            $table->unsignedInteger('menu_id')->nullable();
            $table->string('title');
            $table->string('route')->nullable()->default(null);
            $table->json('params')->nullable();
            $table->string('url')->nullable()->default(null);
            $table->string('target', 20)->default('');

            $table->foreign('menu_id')->references('id')->on('menu')
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
        Schema::dropIfExists('menu_items');
    }
}
