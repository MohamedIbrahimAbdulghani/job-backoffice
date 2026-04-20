<?php

use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Hash;

// this is to make test about validation all fields
test('Create user that will pass validation', function () {
    // Arrange
    $data = [
        'name' => 'Mohamed Ibrahim',
        'email' => 'mohamed@gmail.com',
        'password' => Hash::make(123456789),
        'role' => 'admin'
    ];

    // Act
    $user = User::create($data);

    // Assert
    expect($user->name)->toBe($data['name']);
    expect($user->email)->toBe($data['email']);
    expect($user->password)->toBe($data['password']);
    expect($user->role)->toBe($data['role']);
});


// this is to make test about validation what fail field
test('Create user that will fail validation', function () {
    // Arrange
    $data = [
        'name' => '',
        'email' => 'mohamed@gmail.com',
    ];

    // Act
    try {
        $user = User::create($data);
        $failed = false;
    } catch(QueryException $e) {
        $failed = true;
    }

    // Assert
    expect($failed)->toBeTrue();
    expect(User::where('email', 'mohamed@gmail.com')->exists())->toBeFalse();
});