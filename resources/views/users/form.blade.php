@extends('layouts.app')

@section('title', 'Create User')

@section('content')
  <div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h3>Create User</h3>
      <a href="{{ route('users.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back to Users</a>
    </div>

    <div class="card">
      <div class="card-body">
        <form action="{{ isset($user) ? route('users.update', $user->id) : route('users.store') }}" method="POST">
          @csrf
          @if (isset($user))
            @method('PUT')
          @endif

          <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror"
              value="{{ old('name', isset($user) ? $user->name : '') }}" placeholder="Enter user name" required>
            @error('name')
              <div class="invalid-feedback">
                {{ $message }}
              </div>
            @enderror
          </div>

          <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror"
              value="{{ old('email', isset($user) ? $user->email : '') }}" placeholder="Enter user email" required>
            @error('email')
              <div class="invalid-feedback">
                {{ $message }}
              </div>
            @enderror
          </div>

          <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" id="password" name="password"
              class="form-control @error('password') is-invalid @enderror" placeholder="Enter password"
              {{ isset($user) ? '' : 'required' }}>
            @error('password')
              <div class="invalid-feedback">
                {{ $message }}
              </div>
            @enderror
          </div>

          <div class="mb-3">
            <label for="password_confirmation" class="form-label">Confirm Password</label>
            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control"
              placeholder="Confirm password" {{ isset($user) ? '' : 'required' }}>
          </div>

          <div class="mb-3">
            <label for="role" class="form-label">Role</label>
            <select id="role" name="role" class="form-select @error('role') is-invalid @enderror" required>
              <option value="" disabled>Select role</option>
              <option value="admin" {{ old('role', isset($user) ? $user->role : '') == 'admin' ? 'selected' : '' }}>Admin
              </option>
              <option value="user" {{ old('role', isset($user) ? $user->role : '') == 'user' ? 'selected' : '' }}>User
              </option>
              <option value="rw" {{ old('role', isset($user) ? $user->role : '') == 'rw' ? 'selected' : '' }}>RW
              </option>
              <option value="rt" {{ old('role', isset($user) ? $user->role : '') == 'rt' ? 'selected' : '' }}>RT
              </option>
            </select>
            @error('role')
              <div class="invalid-feedback">
                {{ $message }}
              </div>
            @enderror
          </div>

          <!-- Conditional RW Access Section -->

          <div id="rwAccessSection" class="mb-3" style="display: none;">
            <label class="form-label">Pengelola RW</label>
            <select id="rw_id" name="rw_id" class="form-select @error('rw_id') is-invalid @enderror">
              <option value="">Pilih Rw</option>
              @foreach ($rws as $rw)
                <option value="{{ $rw->id }}"
                  {{ old('rw_id', isset($user) ? $user->rw_id : '') == $rw->id ? 'selected' : '' }}>{{ $rw->number }}
                </option>
              @endforeach
            </select>
            @error('rw_id')
              <div class="invalid-feedback">
                {{ $message }}
              </div>
            @enderror
          </div>

          <div id="rtAccessSection" class="mb-3" style="display: none;">
            <label class="form-label">Pengelola RT</label>
            <select id="rt_id" name="rt_id" class="form-select @error('rt_id') is-invalid @enderror">
              <option value="">Pilih RT</option>
              @foreach ($rts as $rt)
                <option value="{{ $rt->id }}"
                  {{ old('rt_id', isset($user) ? $user->rt_id : '') == $rt->id ? 'selected' : '' }}>
                  {{ $rt->rw->number . '/' . $rt->number }}</option>
              @endforeach
            </select>
            @error('rt_id')
              <div class="invalid-feedback">
                {{ $message }}
              </div>
            @enderror
          </div>



          <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save User</button>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection

@section('script')
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const roleSelect = document.getElementById('role');
      const rwSelect = document.getElementById('rw_id');
      const rwAccessSection = document.getElementById('rwAccessSection');
      const rtAccessSection = document.getElementById('rtAccessSection');

      roleSelect.addEventListener('change', function() {
        if (roleSelect.value === 'rw') {
          rwAccessSection.style.display = 'block';
          rtAccessSection.style.display = 'none';
        } else if (roleSelect.value === 'rt') {
          rtAccessSection.style.display = 'block';
          rwAccessSection.style.display = 'none';
        } else {
          rwAccessSection.style.display = 'none';
          rtAccessSection.style.display = 'none';
        }
      });

      // Trigger the change event on page load for old input values
      if (roleSelect.value === 'rw') {
        rwAccessSection.style.display = 'block';
      } else if (roleSelect.value === 'rt') {
        rtAccessSection.style.display = 'block';
      }
    });
  </script>
@endsection
