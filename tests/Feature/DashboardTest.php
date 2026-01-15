<?php

use App\Models\User;

test('guests are redirected to the login page', function () {
    $this->get(route('chat.index'))->assertRedirect(route('login'));
});

test('authenticated users can visit the dashboard', function () {
    /** @var User $user */
    $user = User::factory()->create();

    $this->actingAs($user)->get(route('chat.index'))->assertOk();
});
