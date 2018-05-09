<?php
namespace Tests\Unit;

use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Tests\TestCase;
use Mockery;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\Resource;
use App\User;

class ResourceTest extends TestCase
{

    public function testPublicAccess()
    {
        $request = $this->getMockedRequest(Route::get('/login', [
            'uses' => 'LoginController@index'
            // No acl -> Public
        ]));

        // Pass it to the middleware
        $middleware = new Resource();
        $mRequest = $middleware->handle($request, function ($req) {
            return $req;
        });

        $this->assertInstanceOf('Illuminate\Http\Request', $mRequest);
    }

    public function testPrivateWithoutUser()
    {
        $request = $this->getMockedRequest(Route::get('/home', [
            'uses' => 'HomeController@index',
            'acl' => [
                'role' => [
                    'admin',
                    'viewer',
                    'user'
                ]
            ]
        ]));

        $this->expectException(HttpExceptionInterface::class);

        $middleware = new Resource();
        $middleware->handle($request, function () {});
    }

    public function testPrivateWithUserAllowed()
    {
        $user = new User([
            'name' => 'John Smith',
            'email' => 'john@test.com',
            'role' => 'admin',
            'resources' => '{
                 "home" :  ["index"],
                 "items" :  ["index", "view", "create", "delete"]
            }'
        ]);

        $request = $this->getMockedRequest(Route::get('/home', [
            'uses' => 'HomeController@index',
            'acl' => [
                'role' => [
                    'admin',
                    'viewer',
                    'user'
                ]
            ]
        ]), $user);

        $middleware = new Resource();
        $mRequest = $middleware->handle($request, function ($req) {
            return $req;
        });

        $this->assertInstanceOf('Illuminate\Http\Request', $mRequest);
    }

    private function getMockedRequest($route, $user = null)
    {
        $request = Mockery::mock('Illuminate\Http\Request');

        $request->shouldReceive('route')
            ->andReturn($route)
            ->getMock();

        $request->shouldReceive('user')
            ->andReturn($user)
            ->getMock();

        return $request;
    }
}
