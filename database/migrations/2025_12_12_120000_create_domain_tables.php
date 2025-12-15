<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employeefile', function (Blueprint $table) {
            $table->string('empcde', 15)->primary();
            $table->string('imglocation', 255);
            $table->string('ess_empcode', 50)->nullable();
            $table->charset = 'utf8mb3';
            $table->collation = 'utf8mb3_unicode_ci';
        });

        Schema::create('capturefile', function (Blueprint $table) {
            $table->bigIncrements('recid');
            $table->string('empcde', 15);
            $table->string('scimagename', 255);
            $table->string('scdirectoryname', 255);
            $table->string('screason', 255)->nullable();
            $table->string('snimagename', 255);
            $table->string('sndirectoryname', 255);
            $table->string('snreason', 255)->nullable();
            $table->date('capdate');
            $table->time('captime');
            $table->charset = 'utf8mb3';
            $table->collation = 'utf8mb3_unicode_ci';
        });

        Schema::create('daytran', function (Blueprint $table) {
            $table->string('empcde', 15);
            $table->time('time');
            $table->date('date');
            $table->string('type', 5);
            $table->bigIncrements('recid');
            $table->charset = 'utf8mb3';
            $table->collation = 'utf8mb3_unicode_ci';
        });

        Schema::create('screenshotfile', function (Blueprint $table) {
            $table->bigIncrements('recid');
            $table->string('empcde', 15);
            $table->string('directoryname', 255);
            $table->string('imagename', 50);
            $table->date('date')->nullable();
            $table->time('time')->nullable();
            $table->string('trankey', 30)->nullable();
            $table->string('tranrun', 10)->nullable();
            $table->string('reason', 255)->nullable();
            $table->charset = 'utf8mb3';
            $table->collation = 'utf8mb3_unicode_ci';

            $table->index('empcde');
            $table->foreign('empcde')
                ->references('empcde')
                ->on('employeefile');
        });

        Schema::create('snapshotfile', function (Blueprint $table) {
            $table->bigIncrements('recid');
            $table->string('empcde', 15);
            $table->string('imagename', 255);
            $table->string('directoryname', 255);
            $table->date('date');
            $table->time('time');
            $table->string('trankey', 30)->nullable();
            $table->string('tranrun', 10)->nullable();
            $table->string('reason', 255)->nullable();
            $table->charset = 'utf8mb3';
            $table->collation = 'utf8mb3_unicode_ci';
        });

        Schema::create('syspar', function (Blueprint $table) {
            $table->bigIncrements('recid');
            $table->integer('interval')->nullable();
            $table->string('progver', 15)->nullable();
            $table->charset = 'utf8mb3';
            $table->collation = 'utf8mb3_unicode_ci';
        });

        Schema::create('tablepar', function (Blueprint $table) {
            $table->string('empcde', 15);
            $table->integer('gridorder');
            $table->string('usrcde', 15);
            $table->increments('id');
            $table->charset = 'utf8mb3';
            $table->collation = 'utf8mb3_unicode_ci';
        });

        Schema::create('userfile', function (Blueprint $table) {
            $table->string('usrcde', 15)->primary();
            $table->string('usrpwd', 255)->nullable();
            $table->string('monitorsetup', 255);
            $table->charset = 'utf8mb3';
            $table->collation = 'utf8mb3_unicode_ci';
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('userfile');
        Schema::dropIfExists('tablepar');
        Schema::dropIfExists('syspar');
        Schema::dropIfExists('snapshotfile');
        Schema::dropIfExists('screenshotfile');
        Schema::dropIfExists('daytran');
        Schema::dropIfExists('capturefile');
        Schema::dropIfExists('employeefile');
    }
};

