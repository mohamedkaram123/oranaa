<?php


namespace App\Traits;

use App\Models\Employee;
use App\Models\User;

trait Userable
{


    public function generateNewToken(array $args = []): array
    {

        $guards = [
            'admin' => User::class,
        ];

        $needed_guard = null;

        foreach ($guards as $guard => $class) {
            if (get_class($this) == $class) {
                $needed_guard = $guard;
                break;
            }
        }

        if (!$needed_guard) die("we can't find guard");

        $ttl = 60 * 24 * 30 * 12 * 20; // 20 years for mahmoud

        if (isset($args['expiration_minutes'])) {
            $ttl = $args['expiration_minutes'];
        }

        return [
            'token' => auth($needed_guard)->setTTL($ttl)->login($this),
        ];
    }


}
