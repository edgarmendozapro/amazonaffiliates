<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAmazonStoresTable extends Migration
{
    public function up()
    {
        Schema::create('amazon_stores', function (Blueprint $table) {
            $table->id();
            $table->string('country_iso')->unique();
            $table->boolean('default')->default(false);
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down()
    {
        Schema::dropIfExists('amazon_stores');
    }
}
