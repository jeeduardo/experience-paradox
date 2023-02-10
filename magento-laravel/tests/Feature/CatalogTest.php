<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CatalogTest extends TestCase
{
    /**
     * Test category page statuses (e.g. 200/404) and response strings
     *
     * @return void
     */
    public function testCategoryPageStatuses()
    {
        $response = $this->get('/catalog/gear');
        $response->assertStatus(200);
        $response->assertSee('Joust Duffle');
        $response->assertSee('categoryMenus');
        $response->assertSee('Bags');

        $response = $this->get('/catalog');
        $response->assertStatus(404);
        $response->assertSee('Not Found');

        $response = $this->get('/catalog?mehwhateverbroandsis');
        $response->assertStatus(404);
        $response->assertSee('404');

        // Has gear path in it but non-existent child
        // @todo: new use case: Customer only knows "/catalog/bags" and expects to see "/catalog/gear/bags"?
        $response = $this->get('/catalog/bear/blehblehbleh');
        $response->assertStatus(404);
        $response->assertSee('Not Found');

        $response = $this->get('/catalog/some-unknown-category');
        $response->assertStatus(404);

        $response = $this->get('/catalog/gear/fitness-equipment');
        $response->assertSee('Yoga');
    }
}
