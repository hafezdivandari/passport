<?php

namespace Laravel\Passport\Http\Controllers;

use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Http\Request;
use Laravel\Passport\Contracts\ApprovedDeviceAuthorizationResponse;
use League\OAuth2\Server\AuthorizationServer;

class ApproveDeviceAuthorizationController
{
    use HandlesOAuthErrors, RetrievesDeviceCodeFromSession;

    /**
     * Create a new controller instance.
     */
    public function __construct(
        protected AuthorizationServer $server,
        protected StatefulGuard $guard
    ) {
    }

    /**
     * Approve the device authorization request.
     */
    public function __invoke(
        Request $request,
        ApprovedDeviceAuthorizationResponse $response
    ): ApprovedDeviceAuthorizationResponse {
        $this->withErrorHandling(fn () => $this->server->completeDeviceAuthorizationRequest(
            $this->getDeviceCodeFromSession($request),
            $this->guard->user()->getAuthIdentifier(),
            true
        ));

        return $response;
    }
}
