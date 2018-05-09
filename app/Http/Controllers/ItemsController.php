<?php
namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ItemsController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function index()
    {
        die("Items:index");
    }

    public function view()
    {
        die("Items:view");
    }

    public function create()
    {
        die("Items:create");
    }

    public function delete()
    {
        die("Items:delete");
    }
}
