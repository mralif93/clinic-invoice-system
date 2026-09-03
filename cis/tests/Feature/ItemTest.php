<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ItemTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['role' => 'admin']);
    }

    public function test_items_catalog_index_renders(): void
    {
        $this->actingAs($this->user);

        Item::create([
            'code' => 'MED-042',
            'name' => 'Amoxicillin 500mg',
            'category' => 'Medication',
            'unit_price' => 25.00,
            'tax_rate' => 0.00,
            'stock_quantity' => 120,
        ]);

        $response = $this->get(route('admin.items.index'));
        $response->assertStatus(200);
        $response->assertSee('Amoxicillin 500mg');
        $response->assertSee('MED-042');
    }

    public function test_item_create_page_renders(): void
    {
        $this->actingAs($this->user);
        $response = $this->get(route('admin.items.create'));
        $response->assertStatus(200);
        $response->assertSee('Add Master Catalog Item');
    }

    public function test_can_create_new_catalog_item(): void
    {
        $this->actingAs($this->user);

        $payload = [
            'code' => 'LAB-99',
            'name' => 'Thyroid Function Panel',
            'category' => 'Lab',
            'unit_price' => 120.00,
            'tax_rate' => 0.00,
            'stock_quantity' => 500,
        ];

        $response = $this->post(route('admin.items.store'), $payload);
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('items', [
            'code' => 'LAB-99',
            'name' => 'Thyroid Function Panel',
            'unit_price' => 120.00,
        ]);
    }

    public function test_item_edit_page_renders(): void
    {
        $this->actingAs($this->user);

        $item = Item::create([
            'code' => 'PRC-10',
            'name' => 'Nebulizer Therapy',
            'category' => 'Procedure',
            'unit_price' => 35.00,
            'tax_rate' => 0.00,
            'stock_quantity' => 999,
        ]);

        $response = $this->get(route('admin.items.edit', $item->id));
        $response->assertStatus(200);
        $response->assertSee('Edit Catalog Item');
    }

    public function test_can_update_catalog_item(): void
    {
        $this->actingAs($this->user);

        $item = Item::create([
            'code' => 'PRC-10',
            'name' => 'Nebulizer Therapy',
            'category' => 'Procedure',
            'unit_price' => 35.00,
            'tax_rate' => 0.00,
            'stock_quantity' => 999,
        ]);

        $response = $this->put(route('admin.items.update', $item->id), [
            'code' => 'PRC-10',
            'name' => 'Nebulizer Therapy (Double Session)',
            'category' => 'Procedure',
            'unit_price' => 60.00,
            'tax_rate' => 0.00,
            'stock_quantity' => 999,
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('items', [
            'id' => $item->id,
            'name' => 'Nebulizer Therapy (Double Session)',
            'unit_price' => 60.00,
        ]);
    }

    public function test_can_delete_catalog_item(): void
    {
        $this->actingAs($this->user);

        $item = Item::create([
            'code' => 'MED-TMP',
            'name' => 'Temporary Medicine',
            'category' => 'Medication',
            'unit_price' => 10.00,
            'tax_rate' => 0.00,
            'stock_quantity' => 10,
        ]);

        $response = $this->delete(route('admin.items.destroy', $item->id));
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('items', [
            'id' => $item->id,
        ]);
    }
}
