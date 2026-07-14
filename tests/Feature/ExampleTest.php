<?php

use App\Models\User;

it('redirects unauthenticated users from home', function () {
    $response = $this->get('/');

    $response->assertRedirect('/login');
});

it('renders the shared dashboard and redirects to user dashboard', function () {
    $user = User::factory()->create(['role' => 'user']);

    $this->actingAs($user)
        ->get('/dashboard')
        ->assertRedirect(route('user.dashboard', absolute: false));
});
