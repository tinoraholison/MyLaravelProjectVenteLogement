<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cites', function (Blueprint $table) {
            $table->id();
            $table->string("nom_cite");
            $table->string("libelle_cite");
            $table->foreignId("agence_id")->constrained()->cascadeOnDelete();
            $table->string("numero_terrain");
            $table->string("superficie_terrain");
            $table->timestamps();
        });
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("cites",function (Blueprint $table){
            $table->dropForeign("agence_id");
        });
        Schema::dropIfExists('cites');
    }
};
