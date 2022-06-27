<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $users = User::with('role')->paginate();

        return view('users.index', compact('users'));
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::default()],
        ]);
        $fields = $request->only(['name', 'email', 'role_id']);
        $user = new User();
        $user->fill($fields);
        $user->setAttribute('password', Hash::make($request->input('password')));
        $user->save();
        return redirect()->route('users.index')->with('message', 'Success create a user');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * @param User $user
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    /**
     * @param UpdateProfileRequest $request
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Throwable
     */
    public function update(UpdateProfileRequest $request, User $user)
    {
        $fields = $request->only('name', 'email', 'role_id');
        $user->fill($fields);
        if ($password = $request->input('password')) {
            $user->setAttribute('password', Hash::make($password));
        }
        $user->saveOrFail();
        return redirect()
                ->route('users.index')
                ->with('message', 'Success update user');
    }

    /**
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(User $user)
    {
        //cannot delete self
        if (auth()->user()->getAuthIdentifier() == $user->id) {
            return redirect()
                ->route('users.index')
                ->with('error', 'Failed delete user');
        }

        $user->delete();
        return redirect()
            ->route('users.index')
            ->with('message', 'Success delete user');
    }
}
