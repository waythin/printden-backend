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
        Schema::create('order_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->double('price');
            $table->foreignId('album_id')->nullable()->constrained('albums')->cascadeOnDelete();
            $table->string('album_custom_cover')->nullable();
            $table->integer('no_of_pages')->nullable(); //For album
            $table->foreignId('frame_id')->nullable()->constrained('frames')->cascadeOnDelete();
            $table->string('orientation')->nullable(); //landscape/ portrate  for Frame. Square/ rectangle (For Collage & album)
            $table->string('bleed_type')->nullable();
            //$table->foreignId('document_id')->constrained('documents')->cascadeOnDelete();
            $table->foreignId('print_type_id')->constrained('print_types')->cascadeOnDelete();
            $table->foreignId('size_id')->constrained('sizes')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_details');
    }
};
