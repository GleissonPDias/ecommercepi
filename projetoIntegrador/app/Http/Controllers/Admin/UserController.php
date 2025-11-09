<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
// 1. Busca todos os usuários que são admins (is_admin = true)
        //    Usamos get() aqui, pois a lista de admins costuma ser pequena.
        $admins = User::where('is_admin', true)->orderBy('name')->get();

        // 2. Busca todos os usuários que são clientes (is_admin = false)
        //    Usamos paginate() aqui, pois esta lista pode ser muito grande.
        $clients = User::where('is_admin', false)->orderBy('name')->paginate(15);
        
        // 3. Envia AMBAS as listas para a view
        return view('admin.users.index', [
            'admins' => $admins,
            'clients' => $clients
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validade([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Passowrd::defaults()],
            'is_admin' => ['required', 'boolean'],
        ]);

        $data['password'] = Hash::make($data['password']);

        User::create($data);

        return redirect()->route('admin.users.index')->with('success', 'Usuario criado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'is_admin' => 'required|boolean',
        ]);

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'Usuario atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        if($user->id === auth()->id()){
            return redirect()->route('admin.users.index')->with('error', 'Você não pode excluir sua própria conta de Admnistrador!');
        }
    }
}
