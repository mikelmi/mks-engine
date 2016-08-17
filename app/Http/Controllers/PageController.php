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
        return $this->show(Page::find($id));
    }

    public function getByPath($path = null)
    {
        if (!$path) {
            return $this->home();
        }

        return $this->show(Page::where('path', $path)->first());
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