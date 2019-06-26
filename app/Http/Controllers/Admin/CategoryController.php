<?php 
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model;


class CategoryController extends Controller {

    /**
     * 汽车一览
     */
    public function index(Request $request)
    {
        return view('admin.category.index');
    }
}
