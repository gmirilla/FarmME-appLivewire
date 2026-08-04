<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MapImageAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_cannot_upload_a_map_image(): void
    {
        $response = $this->post('/save-map-image', [
            'image' => 'data:image/png;base64,'.base64_encode('fake-image-bytes'),
        ]);

        $response->assertRedirect('/login');
    }
}
