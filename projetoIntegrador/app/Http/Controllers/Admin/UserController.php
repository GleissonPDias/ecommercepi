<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Validation\Rule; // 👈 CORREÇÃO 1: Faltava importar a Rule (singular)

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // (O seu código aqui está perfeito)
        $admins = User::where('is_admin', true)->orderBy('name')->get();
        $clients = User::where('is_admin', false)->orderBy('name')->paginate(15);
        
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
        // (O seu código aqui está perfeito)
        return view('admin.users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 👇 CORREÇÃO 2: 'validade' -> 'validate'
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            // 👇 CORREÇÃO 3: 'Passowrd' -> 'Password'
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'is_admin' => ['required', 'boolean'],
        ]);

        $data['password'] = Hash::make($data['password']);

        User::create($data);

        return redirect()->route('admin.users.index')->with('success', 'Usuário criado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // (Não estamos a usar esta rota, por isso está OK ficar vazia)
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        // (O seu código aqui está perfeito)
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        // (O seu código aqui estava correto,
        // mas só funciona se a 'use Illuminate\Validation\Rule;' (singular)
        // for importada no topo do ficheiro)
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'is_admin' => 'required|boolean',
        ]);

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'Usuário atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // 👇 CORREÇÃO 4: A sua lógica de 'destroy' estava incompleta 👇
        
        // 1. A sua verificação de segurança (está perfeita)
        if($user->id === auth()->id()){
            return redirect()->route('admin.users.index')
                ->with('error', 'Você não pode excluir sua própria conta de Administrador!');
        }
        
        // 2. O que faltava: A lógica para apagar o utilizador
        $user->delete();
        
        // 3. O que faltava: O redirecionamento de sucesso
        return redirect()->route('admin.users.index')
            ->with('success', 'Usuário apagado com sucesso!');
    }
}