<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSomeColumnsToLettersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('letters', function (Blueprint $table) {
            $table->integer('document_id')->unsigned()
                ->nullable()
                ->unique()
                ->default(null)
                ->after('letter_code_id')->comment('ID Dokumen');
            $table->foreign('document_id')->references('id')->on('documents')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('letters', function (Blueprint $table) {
            $table->dropForeign(['document_id']);
            $table->dropColumn(['document_id']);
        });
    }
}
