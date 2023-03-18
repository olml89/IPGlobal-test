<?php declare(strict_types=1);

namespace olml89\IPGlobalTest\Common\Infrastructure\Laravel\Http\Requests;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Http\FormRequest;
use olml89\IPGlobalTest\User\Domain\User;

abstract class AuthenticatedApiRequest extends FormRequest
{
    /**
     * @throws AuthenticationException
     */
    public function getAuthenticatedUser(): User
    {
        $user = $this->user();

        if (!($user instanceof User)) {
            throw new AuthenticationException();
        }

        return $user;
    }
}
