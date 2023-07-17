<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('organizations', function (Blueprint $table) {
            $table->id();
            $table->string('company_type');
            $table->string('company_name')->unique();
            $table->string('company_number')->nullable();
            $table->string('email')->unique();
            
            // step 2
            $table->string('first_name');
            $table->string('last_name');
            $table->string('country');
            $table->integer('country_code');
            $table->string('phone');
            $table->string('website')->nullable();
            $table->string('twitter')->nullable();
            $table->string('linkedin')->nullable();
            $table->string('facebook')->nullable();
            
            // step 3
            $table->json('organization_status');
            $table->longText('organization_description');
            
            // Step 4

            $table->string('company_registration')->nullable();
            $table->string('vat_registration')->nullable();
            $table->string('doc_1')->nullable();
            $table->string('doc_2')->nullable();
            $table->string('doc_3')->nullable();
            $table->string('doc_4')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organizations');
    }
};
