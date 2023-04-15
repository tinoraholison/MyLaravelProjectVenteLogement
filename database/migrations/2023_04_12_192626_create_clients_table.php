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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string("nom_client");
            $table->string("prenom_client");
            $table->string("cin_client");
            $table->string("adresse_client");
            $table->string('numero_client');
            $table->string("lieu_travail");
            $table->foreignId("logement_id")->constrained()->cascadeOnDelete();
            $table->date("date_achat");
            $table->string('type_achat');
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
        Schema::dropIfExists('clients');
    }
};
