<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('frontend_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // e.g. 'hero_title', 'hero_subtitle', 'logo_simakin', 'logo_bps', 'logo_kantor', 'bg_color'
            $table->text('value')->nullable();
            $table->string('type')->default('text'); // text | textarea | image | color
            $table->string('label')->nullable();      // label user-friendly
            $table->string('group')->default('general'); // general | hero | gambar
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('frontend_settings');
    }
};
