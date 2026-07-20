<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('epreuves', function (Blueprint $table) {
            $table->foreignId('etablissement_id')
                ->nullable()
                ->after('user_id')
                ->constrained('etablissements') // 👈 IMPORTANT
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('epreuves', function (Blueprint $table) {
            $table->dropForeign(['etablissement_id']);
            $table->dropColumn('etablissement_id');
        });
    }

};
