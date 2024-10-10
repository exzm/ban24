<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Vk extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('vk_posts', function (Blueprint $table) {
            $table->engine = 'MYISAM';
            $table->increments('id');
            $table->bigInteger('wall_id')->index();
            $table->bigInteger('post_id')->index();
            $table->longText('data');
            $table->timestamps();
        });


        Schema::create('vk_walls', function (Blueprint $table) {
            $table->engine = 'MYISAM';
            $table->integer('id')->unique();
            $table->string('url')->index();
            $table->string('name');
            $table->longText('data')->nullable();
            $table->timestamp('index_at')->index();
            $table->timestamps();
        });
        Schema::create('vk_photos', function (Blueprint $table) {
            $table->engine = 'MYISAM';
            $table->integer('id')->unique();
            $table->bigInteger('vk_id')->index();
            $table->string('type')->index();
            $table->string('path');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vk_posts');
        Schema::dropIfExists('vk_walls');

    }
}
