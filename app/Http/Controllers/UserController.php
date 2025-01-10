<?php

namespace App\Http\Controllers;

use App\Models\Rt;
use App\Models\Rw;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
  public function index()
  {
    $users = User::with(['rt', 'rw'])->get();

    return view('users.index', compact('users'));
  }

  public function create()
  {
    $rws = Rw::orderBy('number')->get();
    $rts = Rt::orderBy('rw_id')->orderBy('number')->with('rw')->get();
    return view('users.form', compact(['rws', 'rts']));
  }

  public function store(Request $request)
  {
    // Validate the incoming request data
    $validator = Validator::make($request->all(), [
      'name' => 'required|string|max:255',
      'email' => 'required|string|email|max:255|unique:users,email',
      'password' => 'required|string|min:8|confirmed',
      'role' => 'required|string|in:admin,user,rw,rt',
      // 'rw_id' => 'required_if:role,rw|exists:rws,id',
      // 'rt_id' => 'required_if:role,rt|exists:rts,id',
    ]);

    if ($validator->fails()) {
      return back()->withErrors($validator)->withInput();
    }

    try {
      // Initialize the data array
      $data = [
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role' => $request->role,
      ];

      // Add the rw_id if the role is 'rw'
      if ($request->role === 'rw') {
        $data['rw_id'] = $request->rw_id;
      }

      // Add the rt_id if the role is 'rt'
      if ($request->role === 'rt') {
        $data['rt_id'] = $request->rt_id;
      }

      // Create the user
      $user = User::create($data);

      // Redirect with success message
      return redirect()->route('users.index')->with('success', 'User created successfully!');
    } catch (\Exception $e) {
      // Handle error and redirect back with error message
      return back()->with('error', `There was an error creating the user. Please try again. $e`);
    }
  }

  public function show(string $id)
  {
    //
  }

  public function edit(string $id)
  {
    $rws = Rw::orderBy('number')->get();
    $rts = Rt::orderBy('rw_id')->orderBy('number')->with('rw')->get();
    $user = User::findOrFail($id);
    return view('users.form', compact(['rws', 'rts', 'user']));
  }

  public function update(Request $request, $id)
  {
    // Validate the data
    $request->validate([
      'name' => 'required|string|max:255',
      'email' => 'required|email|max:255|unique:users,email,' . $id,
      'password' => 'nullable|confirmed|min:8',
      'role' => 'required|string|in:admin,user,rw,rt',
      'rw_id' => 'nullable|exists:rws,id',
      'rt_id' => 'nullable|exists:rts,id',
    ]);

    // Find the user to update
    $user = User::findOrFail($id);

    // Update user details
    $user->name = $request->name;
    $user->email = $request->email;

    // If password is provided, update it
    if ($request->password) {
      $user->password = bcrypt($request->password);
    }

    $user->role = $request->role;
    $user->rw_id = $request->rw_id;
    $user->rt_id = $request->rt_id;

    // Save the updated user
    $user->save();

    // Redirect to the users list with a success message
    return redirect()->route('users.index')->with('success', 'User updated successfully');
  }

  public function destroy(string $id)
  {
    try {
      $user = User::findOrFail($id);
      $user->delete();
      return response()->json(['message' => 'User deleted successfully'], 200);
    } catch (\Throwable $th) {
      return response()->json(['message' => 'Failed to delete user'], 500);
    }
  }
}
