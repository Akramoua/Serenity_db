<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->string('id')->primary(); // will be the id of the admin and username in the same time
            $table->foreignId('id_user')->constrained('users')->cascadeOnDelete();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('avatar')->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->date('dateN')->nullable();
            $table->integer('age')->nullable();
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->string('role');
            $table->timestamps();
        });

        DB::unprepared('
            CREATE TRIGGER copy_user_info_to_admin
            AFTER INSERT ON users
            FOR EACH ROW
            BEGIN
            IF NEW.role = "admin" THEN

                INSERT INTO admins (id, id_user, first_name, last_name, points, avatar, email, password, dateN, age, gender, role, created_at, updated_at)
                VALUES (NEW.username, NEW.id, NEW.first_name, NEW.last_name, 100, NULL, NEW.email, NEW.password, NEW.dateN, NEW.age, NEW.gender, NEW.role, NOW(), NOW());
            END IF;
            END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};
