<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title> @yield('title', 'SiWarga Dashboard')</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome CSS -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <!-- DataTables -->
  <link rel="stylesheet" href="https://cdn.datatables.net/2.2.0/css/dataTables.dataTables.min.css">
  <!-- Custom CSS -->
  <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>

<body>
  <div class="d-flex w-100">
    <div class="sidebar">
      <h4 class="text-center py-3">SiWarga</h4>
      <a class="nav-link {{ request()->routeIs('dashboard.*') ? 'active' : '' }}" href="#"><i
          class="fas fa-tachometer-alt"></i> <span class="nav-text">Dashboard</span></a>
      <a class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.index') }}"><i
          class="fas fa-users"></i> <span class="nav-text">Users</span></a>
      <a class="nav-link {{ request()->routeIs('a') ? 'active' : '' }}" href="#"><i class="fas fa-cogs"></i> <span
          class="nav-text">Settings</span></a>
      <a class="nav-link {{ request()->routeIs('s') ? 'active' : '' }}" href="#"><i
          class="fas fa-sign-out-alt"></i> <span class="nav-text">Logout</span></a>
    </div>

    <div class="content flex-grow-1">
      <nav class="navbar navbar-expand-lg navbar-light bg-light mb-4 rounded shadow-sm">
        <div class="container-fluid">
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
              <li class="nav-item">
                <a class="nav-link" href="#"><i class="fas fa-user"></i> {{ auth()->user()->name }}</a>
              </li>
            </ul>
          </div>
        </div>
      </nav>
      <div class="container">
        @yield('content')
      </div>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.datatables.net/2.2.0/js/dataTables.min.js"></script>

  <!-- SweetAlert -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  {{-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.0/dist/sweetalert2.all.min.js"></script> --}}
  @if (session('success'))
    <script>
      Swal.fire({
        icon: 'success',
        title: 'Success',
        text: "{{ session('success') }}",
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000
      });
    </script>
  @elseif(session('error'))
    <script>
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: "{{ session('error') }}",
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000
      });
    </script>
  @endif
  @yield('script')
</body>

</html>
