<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_pages_respond_ok(): void
    {
        $this->get('/')->assertOk();
        $this->get('/tentang')->assertOk();
        $this->get('/dokter')->assertOk();
        $this->get('/janji-temu')->assertOk();
        $this->get('/artikel')->assertOk();
        $this->get('/kontak')->assertOk();
    }

    public function test_admin_requires_authentication(): void
    {
        $this->get('/admin')->assertRedirect(route('login'));
    }

    public function test_login_page_is_accessible(): void
    {
        $this->get('/login')->assertOk();
    }
}
