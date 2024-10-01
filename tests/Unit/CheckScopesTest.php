<?php

namespace Laravel\Passport\Tests\Unit;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Laravel\Passport\Exceptions\AuthenticationException;
use Laravel\Passport\Http\Middleware\CheckScopes;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery as m;
use PHPUnit\Framework\TestCase;

class CheckScopesTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    public function test_request_is_passed_along_if_scopes_are_present_on_token()
    {
        $middleware = new CheckScopes;
        $request = m::mock(Request::class);
        $request->shouldReceive('user')->andReturn($user = m::mock());
        $user->shouldReceive('token')->andReturn($token = m::mock());
        $user->shouldReceive('tokenCan')->with('foo')->andReturn(true);
        $user->shouldReceive('tokenCan')->with('bar')->andReturn(true);

        $response = $middleware->handle($request, function () {
            return new Response('response');
        }, 'foo', 'bar');

        $this->assertSame('response', $response->getContent());
    }

    public function test_exception_is_thrown_if_token_doesnt_have_scope()
    {
        $this->expectException('Laravel\Passport\Exceptions\MissingScopeException');

        $middleware = new CheckScopes;
        $request = m::mock(Request::class);
        $request->shouldReceive('user')->andReturn($user = m::mock());
        $user->shouldReceive('token')->andReturn($token = m::mock());
        $user->shouldReceive('tokenCan')->with('foo')->andReturn(false);

        $middleware->handle($request, function () {
            return new Response('response');
        }, 'foo', 'bar');
    }

    public function test_exception_is_thrown_if_no_authenticated_user()
    {
        $this->expectException(AuthenticationException::class);

        $middleware = new CheckScopes;
        $request = m::mock(Request::class);
        $request->shouldReceive('user')->once()->andReturn(null);

        $middleware->handle($request, function () {
            return new Response('response');
        }, 'foo', 'bar');
    }

    public function test_exception_is_thrown_if_no_token()
    {
        $this->expectException(AuthenticationException::class);

        $middleware = new CheckScopes;
        $request = m::mock(Request::class);
        $request->shouldReceive('user')->andReturn($user = m::mock());
        $user->shouldReceive('token')->andReturn(null);

        $middleware->handle($request, function () {
            return new Response('response');
        }, 'foo', 'bar');
    }
}
