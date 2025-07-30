<?php

namespace App\Services\Auth;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthService
{
    /**
     * Create user
     *
     * @param string $name
     * @param string $email
     * @param string $password
     * @return void
     * @throws Exception
     */
    public function storeUser(
        string $name,
        string $email,
        string $password,
    ): void {
        try {
            DB::beginTransaction();

            User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password),
            ]);

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();

            logError('Something happened.', [
                'message' => $th->getMessage(),
                'file' => $th->getFile(),
                'function' => __FUNCTION__,
            ]);

            throw new Exception();
        }
    }
}
