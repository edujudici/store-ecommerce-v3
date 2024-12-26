<?php

namespace App\Services\User;

use App\Models\User;
use App\Services\BaseService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

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

    public function store($request): User
    {
        debug("Store user data");

        $params = array_merge($request, [
            'uuid' => Str::uuid(),
            'role' => 'shopper',
        ]);
        return $this->user->create($params);
    }

    public function storeBySocialite($request): User
    {
        $user = $this->user->where('google_id', $request->id)
            ->orWhere('email', $request->email)
            ->first();
        if (is_null($user)) {
            return $this->store([
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
