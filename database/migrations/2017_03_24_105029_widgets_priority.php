<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class WidgetsPriority extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('widgets', function (Blueprint $table) {
            $table->dropColumn('ordering');
            $table->integer('priority')->default(0);
            //$table->renameColumn('ordering', 'priority');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('widgets', function (Blueprint $table) {
            //$table->renameColumn('priority', 'ordering');
            $table->dropColumn('priority');
            $table->integer('ordering')->default(0);
        });
    }
}
