<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAmazonLinkMediaResourcesTable extends Migration
{
    public function up()
    {
        Schema::create('amazon_link_media_resources', function (
            Blueprint $table
        ) {
            $table->id();
            $table->bigInteger('media_resource_id')->unsigned();
            $table->bigInteger('amazon_link_id')->unsigned();
            $table->integer('order')->default(0);
            $table
                ->foreign('media_resource_id')
                ->references('id')
                ->on('media_resources')
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
        Schema::dropIfExists('amazon_link_media_resources');
    }
}
