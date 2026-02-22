<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('home_page', function (Blueprint $table) {
            $table->id();

            // Hero
            $table->string('hero_badge')->default('مرحباً بك في موقعي الشخصي');
            $table->string('hero_name')->default('أوس بن فواز الفوزان');
            $table->text('hero_subtitle')->nullable();
            $table->text('hero_quote')->nullable();
            $table->string('hero_image')->nullable();
            $table->text('hero_floating_text')->nullable();

            // Expertise cards (JSON array of {icon, title, desc})
            $table->json('expertise_cards')->nullable();

            // Tip banner
            $table->text('tip_banner')->nullable();

            // Achievements (JSON array of {num, label})
            $table->json('achievements')->nullable();

            // Consultations
            $table->text('consultations_text')->nullable();

            // Volunteering
            $table->text('volunteering_intro')->nullable();
            $table->json('volunteering_bullets')->nullable();
            $table->text('volunteering_closing')->nullable();

            // Contact
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->json('social_links')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('home_page');
    }
};
