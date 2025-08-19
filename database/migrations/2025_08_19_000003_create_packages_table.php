<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('menu_item_package', function (Blueprint $table) {
            $table->foreignId('menu_item_id')->constrained('menu_items')->cascadeOnDelete();
            $table->foreignId('package_id')->constrained('packages')->cascadeOnDelete();
            $table->unsignedInteger('quantity')->default(1);
            $table->timestamps();
            $table->primary(['menu_item_id', 'package_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menu_item_package');
        Schema::dropIfExists('packages');
    }
};
