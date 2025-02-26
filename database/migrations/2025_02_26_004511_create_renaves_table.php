<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // const CREATED_AT = 'created_at';
	// const UPDATED_AT = 'updated_at';
	// const DELETE_AT = 'delete_at';

    public function up(): void
    {
        Schema::create('renaves', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dealer_id')->constrained('revendas','rev_cod');
            $table->foreignId('vehicle_id')->constrained('veiculos','veic_cod');
            $table->foreignId('client_id')->constrained('clientes','clie_cod');
            $table->string('nfe_key', 255);
            $table->integer('nfe_type');
            $table->integer('status');
            $table->json('json_data_sent')->nullable();
            $table->json('json_data_return')->nullable();
            $table->decimal('nfe_value', 12, 2)->nullable()->comment('Valor nfe');
            $table->date('entry_date')->nullable();
            $table->date('departure_date')->nullable();
            $table->integer('crv_number');
            $table->integer('crv_type');
            $table->integer('crv_security_code');
            $table->date('km_measurement_date');
            $table->date('doc_signature_date');
            $table->enum('partner_id', ['1', '2', '3', '4'])->comment('1 => Renave Data Stock | 2 => Renave Facíl | 3 => Renave Zero | => 4 Renave Web');
            $table->integer('created_by')->nullable()->constrained('users')->onDelete('restrict');
            $table->integer('updated_by')->nullable()->constrained('users')->onDelete('restrict');
            $table->integer('deleted_by')->nullable()->constrained('users')->onDelete('restrict');
            $table->timestamp('created_at');
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('delete_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('renaves');
    }
};
