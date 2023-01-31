<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('countrysus', static function(Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('families', static function(Blueprint $table) {
           $table->id();
           $table->string('name');
           $table->timestamps();
        });

        Schema::create('countryors', static function(Blueprint $table) {
           $table->id();
           $table->string('name');
           $table->timestamps();
        });

        Schema::create('hazards', static function(Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
         });

         Schema::create('kings', static function(Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
         });

        Schema::create('tools', function (Blueprint $table) {
            $table->id();
            $table->string('item')->nullable();
            $table->foreignId('family_id')->nullable()->constrained();
            $table->foreignId('countrysu_id')->constrained();
            $table->foreignId('countryor_id')->constrained();
            $table->foreignId('hazard_id')->constrained();
            $table->foreignId('king_id')->constrained();
            $table->date('date')->nullable();
            $table->string('product')->nullable();
            $table->string('brand')->nullable();
            $table->string('reference');
            $table->string('spec1')->nullable();
            $table->string('spec2')->nullable();
            $table->string('spec3')->nullable();
            $table->string('supplier1')->nullable();
            $table->string('contact1')->nullable();
            $table->string('email1')->nullable();
            $table->string('supplier2')->nullable();
            $table->string('contact2')->nullable();
            $table->string('email2')->nullable();
            $table->string('supplier3')->nullable();
            $table->string('contact3')->nullable();
            $table->string('email3')->nullable();
            $table->foreignId('user_id')->constrained();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tools');
        Schema::dropIfExists('families');
        Schema::dropIfExists('countrysus');
        Schema::dropIfExists('countryors');
        Schema::dropIfExists('hazards');
        Schema::dropIfExists('kings');
    }
}
