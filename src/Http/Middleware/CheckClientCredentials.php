<?php

namespace Laravel\Passport\Http\Middleware;

use Laravel\Passport\AccessToken;
use Laravel\Passport\Exceptions\MissingScopeException;

class CheckClientCredentials extends CheckCredentials
{
    /**
     * Validate token scopes.
     *
     * @param  string[]  $scopes
     *
     * @throws \Laravel\Passport\Exceptions\MissingScopeException
     */
    protected function validateScopes(AccessToken $token, array $scopes): void
    {
        if (in_array('*', $token->oauth_scopes)) {
            return;
        }

        foreach ($scopes as $scope) {
            if ($token->cant($scope)) {
                throw new MissingScopeException($scope);
            }
        }
    }
}
