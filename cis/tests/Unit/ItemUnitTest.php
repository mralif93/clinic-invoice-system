<?php

namespace Tests\Unit;

use App\Models\Item;
use PHPUnit\Framework\TestCase;

class ItemUnitTest extends TestCase
{
    public function test_item_model_instantiation_and_attributes(): void
    {
        $item = new Item([
            'code' => 'MED-500',
            'name' => 'Paracetamol 500mg',
            'category' => 'Medication',
            'unit_price' => 8.50,
            'tax_rate' => 0.00,
            'stock_quantity' => 200,
        ]);

        $this->assertEquals('MED-500', $item->code);
        $this->assertEquals('Paracetamol 500mg', $item->name);
        $this->assertEquals('Medication', $item->category);
        $this->assertEquals(8.50, $item->unit_price);
        $this->assertEquals(0.00, $item->tax_rate);
        $this->assertEquals(200, $item->stock_quantity);
    }
}
