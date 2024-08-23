<?php

namespace Tests\Unit;

use App\Models\Category;
use PHPUnit\Framework\TestCase;

class CategoryModelFunctionalityTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function test_attributes_are_set_correctly()
    {
        $category = new Category([
            'name' => 'Sample Category Name',
        ]);

        $this->assertEquals('Sample Category Name', $category->name);
    }

    // public function test_non_fillable_attributes_are_not_set()
    // {
    //     $category = new Category([
    //         'name' => 'Sample SubCategory Name',
    //         'author' => 'John Doe',
    //     ]);

    //     $this->assertArrayNotHaskey('author', $category->getAttributes());
    // }
}
