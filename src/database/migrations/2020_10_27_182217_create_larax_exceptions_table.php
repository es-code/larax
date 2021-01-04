<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLaraxExceptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('larax_exceptions', function (Blueprint $table) {
            $table->id();
            $table->string('ip')->nullable();
            $table->string('url')->nullable();
            $table->text('headers')->nullable();
            $table->text('body')->nullable();
            $table->integer('user_id')->default(0);
            $table->string('guard')->nullable();
            $table->longText('exception');
            $table->boolean('solved')->default(0);
            $table->timestamps();
            $table->index('url');
            $table->index('user_id');
            $table->index('guard');
            $table->index('solved');
            $table->index('ip');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('larax_exceptions');
    }
}
