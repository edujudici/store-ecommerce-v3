<?php

namespace App\Services\User;

use App\Models\User;
use App\Services\BaseService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UserService extends BaseService
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function index(): Collection
    {
        return $this->user
            ->with('addresses')
            ->get();
    }

    public function findById($request): User
    {
        return $this->user
            ->where('uuid', $request->input('id'))
            ->firstOrFail();
    }

    public function findByEmail($request): User
    {
        return $this->user
            ->where('email', $request['email'])
            ->firstOrFail();
    }

    public function store($request): User
    {
        $this->validateFields($request->all());

        $params = [
            'name' => $request->input('name'),
            'surname' => $request->input('surname'),
            'email' => $request->input('email'),
            'role' => $request->input('role'),
        ];

        $isUpdate = $request->has('id');

        if (!$isUpdate) {
            $params['uuid'] = Str::uuid();
            $params['password'] = Hash::make($request->input('password'));
        }

        return $this->user->updateOrCreate([
            'uuid' => $request->input('id'),
        ], $params);
    }

    public function destroy($request): bool
    {
        $user = $this->findById($request);
        return $user->delete();
    }

    private function validateFields($request): void
    {
        $isUpdate = isset($request['id']) ? true : false;

        $rules = [
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'password' => $isUpdate ? 'nullable|string|min:8|confirmed' : 'required|string|min:8|confirmed',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                $isUpdate
                    ? Rule::unique('users', 'uuid')->ignore($request['id']) // Update
                    : 'unique:users,email', // Create
            ]
        ];
        $titles = [
            'name' => 'Nome',
            'surname' => 'Sobrenome',
            'password' => 'Senha',
            'email' => 'E-mail',
        ];
        $this->_validate($request, $rules, $titles);
    }

    public function create($request): User
    {
        $request['uuid'] = Str::uuid();
        return $this->user->create($request);
    }

    public function storeBySocialite($request): User
    {
        $user = $this->user->where('google_id', $request->id)
            ->orWhere('email', $request->email)
            ->first();
        if (is_null($user)) {
            return $this->create([
                'role' => 'shopper',
                'name' => $request->user['given_name'],
                'surname' => $request->user['family_name'],
                'email' => $request->email,
                'google_id' => $request->id,
            ]);
        }

        if (! $user->google_id) {
            $user->google_id = $request->id;
            $user->save();
        }

        return $user;
    }
}
