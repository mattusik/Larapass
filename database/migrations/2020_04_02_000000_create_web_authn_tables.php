<?php

use DarkGhostHunter\Larapass\Eloquent\WebAuthnCredential;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWebAuthnTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('web_authn_credentials', function (Blueprint $table) {

            if ($table->engine === 'mysql') {
                $table->addColumn('tinyblob', 'id');
            } else {
                $table->binary('id');
            }

            // Change accordingly for your users table if you need to.
            $table->unsignedBigInteger('user_id');

            $table->string('name')->nullable();
            $table->string('type', 16);
            $table->json('transports');
            $table->json('attestation_type');
            $table->json('trust_path');
            $table->uuid('aaguid');
            $table->binary('public_key');
            $table->unsignedInteger('counter')->default(0);

            // This saves the external "ID" that identifies the user. We use UUID default
            // since it's very straightforward. You can change this for a plain string.
            // It must be nullable because those old U2F keys do not use user handle.
            $table->uuid('user_handle')->nullable();

            $table->timestamps();
            $table->softDeletes(WebAuthnCredential::DELETED_AT);

            // SQLSTATE[42000]: Syntax error or access violation: 1170 BLOB/TEXT column 'id' used in key specification without a key length
            // (SQL: alter table `web_authn_credentials` add primary key `web_authn_credentials_id_user_id_primary`(`id`, `user_id`))
            // $table->primary(['id', 'user_id']);
        });

        \DB::statement(\DB::raw('ALTER TABLE `web_authn_credentials` ADD PRIMARY KEY `id_user_id` (`id`(255), `user_id`);'));

        Schema::create('web_authn_recoveries', function (Blueprint $table) {
            $table->string('email')->index();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('web_authn_credentials');
        Schema::dropIfExists('web_authn_recoveries');
    }
}
