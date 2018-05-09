<?php
namespace Tests\Unit;

use Tests\TestCase;
use Mockery;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\Role;
use Symfony\Component\HttpKernel\Exception\HttpException;
use App\User;
use Illuminate\Support\Facades\Hash;

class RoleTest extends TestCase
{

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testPublic()
    {
        $request = $this->getMockedRequest(Route::get('/login', [
            'uses' => 'LoginController@index'
            // No acl -> Public
        ]));

        // Pass it to the middleware
        $middleware = new Role();
        $mRequest = $middleware->handle($request, function ($req) {
            /** @var \Illuminate\Support\Facades\Request $req */

            // $req->setSt
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

        $this->expectException(HttpException::class);

        $middleware = new Role();
        $mRequest = $middleware->handle($request, function ($req) {
            /** @var \Illuminate\Support\Facades\Request $req */

            // $req->setSt
            return $req;
        });
    }

    public function testPrivateWithUserAllowed()
    {
        $user = new User([
            'name' => 'John Smith',
            'email' => 'john@test.com',
            'password' => Hash::make('qwerty'),
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

        $middleware = new Role();
        $mRequest = $middleware->handle($request, function ($req) {
            /** @var \Illuminate\Support\Facades\Request $req */

            // $req->setSt
            return $req;
        });

        $this->assertInstanceOf('Illuminate\Http\Request', $mRequest);
    }

    public function testPrivateWithUserNotAllowed()
    {
        $user = new User([
            'name' => 'John Smith',
            'email' => 'john@test.com',
            'password' => Hash::make('qwerty'),
            'role' => 'manager'

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

        $this->expectException(HttpException::class);

        $middleware = new Role();
        $mRequest = $middleware->handle($request, function ($req) {
            /** @var \Illuminate\Support\Facades\Request $req */

            // $req->setSt
            return $req;
        });
    }

    private function getMockedRequest($route, $user = null)
    {
        $request = Mockery::mock('Illuminate\Http\Request');

        $request->shouldReceive('route')
            ->once()
            ->andReturn($route)
            ->getMock();

        $request->shouldReceive('user')
            ->
        // ->once()
        andReturn($user)
            ->getMock();

        return $request;
    }
}
