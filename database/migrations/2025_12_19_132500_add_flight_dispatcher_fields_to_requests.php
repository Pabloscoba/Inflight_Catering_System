<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('requests', function (Blueprint $table) {
            if (! Schema::hasColumn('requests', 'dispatcher_comments')) {
                $table->text('dispatcher_comments')->nullable()->after('notes');
            }
            if (! Schema::hasColumn('requests', 'dispatcher_recommended')) {
                $table->boolean('dispatcher_recommended')->default(false)->after('dispatcher_comments');
                $table->unsignedBigInteger('dispatcher_recommended_by')->nullable()->after('dispatcher_recommended');
                $table->timestamp('dispatcher_recommended_at')->nullable()->after('dispatcher_recommended_by');
            }
        });
    }

    public function down()
    {
        Schema::table('requests', function (Blueprint $table) {
            if (Schema::hasColumn('requests', 'dispatcher_comments')) {
                $table->dropColumn('dispatcher_comments');
            }
            if (Schema::hasColumn('requests', 'dispatcher_recommended')) {
                $table->dropColumn(['dispatcher_recommended', 'dispatcher_recommended_by', 'dispatcher_recommended_at']);
            }
        });
    }
};
