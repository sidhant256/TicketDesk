<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('categories', 'name')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->string('name')->after('id');
            });
        }

        if (! Schema::hasColumn('tickets', 'user_id')) {
            Schema::table('tickets', function (Blueprint $table) {
                $table->foreignId('user_id')->after('id')->constrained()->cascadeOnDelete();
            });
        }

        if (! Schema::hasColumn('tickets', 'category_id')) {
            Schema::table('tickets', function (Blueprint $table) {
                $table->foreignId('category_id')->after('user_id')->constrained()->cascadeOnDelete();
            });
        }

        if (! Schema::hasColumn('tickets', 'title')) {
            Schema::table('tickets', function (Blueprint $table) {
                $table->string('title')->after('category_id');
            });
        }

        if (! Schema::hasColumn('tickets', 'description')) {
            Schema::table('tickets', function (Blueprint $table) {
                $table->text('description')->after('title');
            });
        }

        if (! Schema::hasColumn('tickets', 'status')) {
            Schema::table('tickets', function (Blueprint $table) {
                $table->enum('status', ['open', 'in_progress', 'closed'])->default('open')->after('description');
            });
        }

        if (! Schema::hasColumn('tickets', 'priority')) {
            Schema::table('tickets', function (Blueprint $table) {
                $table->enum('priority', ['low', 'medium', 'high'])->default('medium')->after('status');
            });
        }

        if (! Schema::hasColumn('comments', 'ticket_id')) {
            Schema::table('comments', function (Blueprint $table) {
                $table->foreignId('ticket_id')->after('id')->constrained()->cascadeOnDelete();
            });
        }

        if (! Schema::hasColumn('comments', 'user_id')) {
            Schema::table('comments', function (Blueprint $table) {
                $table->foreignId('user_id')->after('ticket_id')->constrained()->cascadeOnDelete();
            });
        }

        if (! Schema::hasColumn('comments', 'body')) {
            Schema::table('comments', function (Blueprint $table) {
                $table->text('body')->after('user_id');
            });
        }
    }

    public function down(): void
    {
        //
    }
};