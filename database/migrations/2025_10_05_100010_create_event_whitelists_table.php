<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('event_whitelists', function (Blueprint $table) {
            $table->id();

            $table->string('email');
            $table->foreignId('event_id')->constrained('events')->cascadeOnDelete()->cascadeOnUpdate();
            $table->unsignedTinyInteger('number_adult_ticket');
            $table->unsignedTinyInteger('number_child_ticket');

            $table->unique(['email', 'event_id']);

            $table->timestamps();
        });

        Schema::table('events', function (Blueprint $table) {
            $table->uuid()->after('id');
        });

        DB::table('events')->update(['uuid' => DB::raw('uuid()')]);

        Schema::table('events', function (Blueprint $table) {
            $table->uuid()->unique()->change();
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('event_whitelist_id')
                ->after('id')
                ->nullable()
                ->constrained('event_whitelists')
                ->nullOnDelete()
                ->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropConstrainedForeignId('event_whitelist_id');
        });

        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });

        Schema::dropIfExists('event_whitelists');
    }
};
