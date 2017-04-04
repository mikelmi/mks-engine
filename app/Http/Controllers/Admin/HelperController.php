<?php
/**
 * Author: mike
 * Date: 04.04.17
 * Time: 17:35
 */

namespace App\Http\Controllers\Admin;


use Illuminate\Http\Request;
use Mikelmi\MksAdmin\Http\Controllers\AdminController;

class HelperController extends AdminController
{
    public function template($path)
    {
        return view('admin._partial.'.$path);
    }

    public function fileManager(Request $request)
    {
        $params = array_merge([
            'langCode' => app()->getLocale(),
        ], $request->query());

        if ($request->ajax()) {
            return '<div class="page-iframe-wrap" mks-page-iframe>
                        <iframe src="' . route('filemanager', $params) . '" class="page-iframe" frameborder="0"></iframe>
                    </div>';
        }

        return redirect()->route('filemanager', $params);
    }
}