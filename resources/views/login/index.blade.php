@extends('layouts.app')

@section('title', 'Login')

@section('content')
    <div class="card">
        <h1 class="card__title">Masuk</h1>
        <p class="card__hint">Gunakan akun yang diberikan koordinator.</p>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group">
                <label for="username" class="form-label">Username</label>
                <input type="text" id="username" name="username" value="{{ old('username') }}"
                       class="form-input" required autofocus>
                @error('username')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <input type="password" id="password" name="password" class="form-input" required>
                @error('password')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="btn">Login</button>
        </form>
    </div>
@endsection
