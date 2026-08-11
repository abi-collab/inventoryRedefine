<?php

namespace Tests\Feature;

use App\Support\RecordsTombstone;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\CreatesUsers;
use Tests\TestCase;

class SyncTombstoneTest extends TestCase
{
    use CreatesUsers;

    protected function setUp(): void
    {
        parent::setUp();

        $path = database_path('supabase_test.sqlite');
        if (file_exists($path)) {
            unlink($path);
        }
        touch($path);

        Config::set('database.connections.supabase', [
            'driver' => 'sqlite',
            'database' => $path,
            'prefix' => '',
            'foreign_key_constraints' => true,
        ]);

        DB::purge('supabase');
        DB::reconnect('supabase');

        Schema::connection('supabase')->create('products', function ($table) {
            $table->bigIncrements('id');
            $table->integer('category_id');
            $table->string('product_name');
            $table->string('selling_price');
            $table->string('product_quantity')->nullable();
            $table->timestamps();
        });

        Schema::connection('supabase')->create('sync_checkpoints', function ($table) {
            $table->id();
            $table->string('table_name')->unique();
            $table->timestamp('last_synced_at')->nullable();
            $table->unsignedBigInteger('last_id')->nullable();
            $table->string('status')->default('idle');
            $table->text('error')->nullable();
            $table->unsignedInteger('rows_synced')->default(0);
            $table->timestamps();
        });
    }

    protected function tearDown(): void
    {
        $path = database_path('supabase_test.sqlite');
        if (file_exists($path)) {
            @unlink($path);
        }
        parent::tearDown();
    }

    public function test_tombstone_deletes_remote_row(): void
    {
        $productId = $this->seedProduct(2);

        DB::connection('supabase')->table('products')->insert([
            'id' => $productId,
            'category_id' => 1,
            'product_name' => 'Test Product',
            'selling_price' => '100',
            'product_quantity' => 2,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('products')->where('id', $productId)->delete();
        RecordsTombstone::for('products', $productId);

        $exit = Artisan::call('sync:supabase', ['--force' => true, '--table' => 'products']);
        $this->assertSame(0, $exit);

        $this->assertSame(0, DB::connection('supabase')->table('products')->where('id', $productId)->count());
        $this->assertSame(0, DB::table('sync_tombstones')->count());
    }
}
