<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateColumnRatingFirms extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('firms', function (Blueprint $table) {
            $table->integer('rating_value')->default(0);
            $table->integer('rating_count')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('firms', function (Blueprint $table) {
            $table->dropColumn('rating_value');
            $table->dropColumn('rating_count');
        });
    }
}
