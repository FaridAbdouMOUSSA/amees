<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('telephone')->nullable()->after('email');
            $table->string('directeur')->nullable()->after('telephone');
            $table->string('commune')->nullable()->after('directeur');
            $table->text('description')->nullable()->after('commune');
            $table->string('lien_localisation')->nullable()->after('description');
            $table->string('photo_profil')->nullable()->after('lien_localisation');
            $table->timestamp('derniere_modification_nom')->nullable()->after('photo_profil');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'telephone', 'directeur', 'commune', 'description', 
                'lien_localisation', 'photo_profil', 'derniere_modification_nom'
            ]);
        });
    }
};