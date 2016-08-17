<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 17.08.16
 * Time: 15:03
 */

namespace App\Http\Controllers;


use App\Models\Page;

class PageController extends Controller
{
    public function getById($id)
    {
        $page = Page::find($id);

        if (!$page) {
            abort(404);
        }

        return $this->show($page);
    }

    public function getByPath($path = null)
    {
        if (!$path) {
            return $this->home();
        }

        $page = Page::where('path', $path)->first();

        if (!$page) {
            abort(404);
        }

        return $this->show($page);
    }

    public function show(Page $page)
    {
        return view('page.show', compact('page'));
    }

    public function home()
    {
        //TODO: Show home page from settings
        return 'Home page';
    }
}