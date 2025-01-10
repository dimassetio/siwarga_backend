@extends('layouts.app')

@section('title', 'Manage Users')

@section('content')
  <div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h3>Manage Users</h3>
      <a href="{{ route('users.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Add User</a>
    </div>

    <div class="card">
      <div class="card-body rounded">
        <table id="usersTable" class="table table-striped table-bordered">
          <thead>
            <tr>
              <th>#</th>
              <th>Name</th>
              <th>Email</th>
              <th>Role</th>
              <th>RT/RW</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($users as $index => $user)
              <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->role }}</td>
                @if (isset($user->rt))
                  <td>{{ $user->rt->rw->number . '/' . $user->rt->number }}</td>
                @elseif (isset($user->rw))
                  <td>{{ $user->rw->number }}</td>
                @else
                  <td> - </td>
                @endif
                <td>
                  <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning btn-sm">
                    <i class="fas fa-edit"></i> Edit
                  </a>
                  <button class="btn btn-danger btn-sm delete-user" data-id="{{ $user->id }}">
                    <i class="fas fa-trash"></i> Delete
                  </button>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>

  </div>
@endsection

@section('script')
  <script>
    $(document).ready(function() {
      // Initialize DataTable
      $('#usersTable').DataTable();

      // Delete Confirmation
      $('.delete-user').on('click', function() {
        const userId = $(this).data('id');
        Swal.fire({
          title: 'Are you sure?',
          text: 'This action cannot be undone!',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          cancelButtonColor: '#3085d6',
          confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
          if (result.isConfirmed) {
            $.ajax({
              url: `/users/${userId}`,
              type: 'DELETE',
              headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
              },
              success: function(response) {
                Swal.fire(
                  'Deleted!',
                  'User has been deleted.',
                  'success'
                ).then(() => location.reload());
              },
              error: function(err) {
                Swal.fire(
                  'Error!',
                  `There was an issue deleting the user. ${err} q`,
                  'error'
                );
              }
            });
          }
        });
      });
    });
  </script>
@endsection
