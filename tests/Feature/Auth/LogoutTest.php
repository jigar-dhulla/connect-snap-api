<?php

use App\Models\User;

test('authenticated users can logout', function () {
    $user = User::factory()->create();
    $token = $user->createToken('test-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer '.$token)
        ->postJson('/api/logout');

    $response->assertSuccessful();

    $this->assertDatabaseMissing('personal_access_tokens', [
        'tokenable_id' => $user->id,
    ]);
});

test('unauthenticated users cannot logout', function () {
    $this->postJson('/api/logout')
        ->assertUnauthorized();
});