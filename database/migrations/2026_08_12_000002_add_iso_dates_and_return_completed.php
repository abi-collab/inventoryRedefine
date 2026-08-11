<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->date('ordered_on')->nullable()->after('order_date');
            $table->index('ordered_on');
        });

        Schema::table('expenses', function (Blueprint $table) {
            $table->date('spent_on')->nullable()->after('expense_date');
            $table->index('spent_on');
        });

        Schema::table('returnitems', function (Blueprint $table) {
            $table->timestamp('completed_at')->nullable()->after('status');
        });

        foreach (DB::table('orders')->select('id', 'order_date')->cursor() as $order) {
            $iso = $this->parseLegacyDate($order->order_date);
            if ($iso) {
                DB::table('orders')->where('id', $order->id)->update(['ordered_on' => $iso]);
            }
        }

        foreach (DB::table('expenses')->select('id', 'expense_date')->cursor() as $expense) {
            $iso = $this->parseLegacyDate($expense->expense_date);
            if ($iso) {
                DB::table('expenses')->where('id', $expense->id)->update(['spent_on' => $iso]);
            }
        }
    }

    public function down(): void
    {
        Schema::table('returnitems', function (Blueprint $table) {
            $table->dropColumn('completed_at');
        });

        Schema::table('expenses', function (Blueprint $table) {
            $table->dropIndex(['spent_on']);
            $table->dropColumn('spent_on');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['ordered_on']);
            $table->dropColumn('ordered_on');
        });
    }

    protected function parseLegacyDate(?string $value): ?string
    {
        if (! $value) {
            return null;
        }

        foreach (['m/d/Y', 'd/m/Y', 'Y-m-d'] as $format) {
            try {
                return Carbon::createFromFormat($format, $value)->toDateString();
            } catch (\Throwable) {
                // try next
            }
        }

        try {
            return Carbon::parse($value)->toDateString();
        } catch (\Throwable) {
            return null;
        }
    }
};
