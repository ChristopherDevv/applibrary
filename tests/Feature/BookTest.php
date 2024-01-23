<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BookTest extends TestCase
{
    public function test_book_index()
    {
        $response = $this->get(route('api.book.index'));
        $response->assertStatus(200);
        $this->assertIsArray($response->json('books'));
      
    }

    public function test_book_show()
    {
        $response = $this->get(route('api.book.show', 1));
        $response->assertStatus(200);
        $this->assertIsArray($response->json('book'));
        $this->assertEquals(1, $response->json('book.id'));
    }
}
