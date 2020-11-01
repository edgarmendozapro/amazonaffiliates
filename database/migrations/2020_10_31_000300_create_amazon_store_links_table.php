<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAmazonStoreLinksTable extends Migration
{
    public function up()
    {
        Schema::create('amazon_store_links', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('amazon_store_id')->unsigned();
            $table->bigInteger('amazon_link_id')->unsigned();
            $table->string('url');
            $table
                ->foreign('amazon_store_id')
                ->references('id')
                ->on('amazon_stores')
                ->onDelete('cascade');
            $table
                ->foreign('amazon_link_id')
                ->references('id')
                ->on('amazon_links')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('amazon_store_links');
    }
}
