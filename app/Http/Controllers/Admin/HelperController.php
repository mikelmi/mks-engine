<?php
/**
 * Author: mike
 * Date: 04.04.17
 * Time: 17:35
 */

namespace App\Http\Controllers\Admin;


use App\Repositories\IconRepository;
use Illuminate\Http\Request;

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

    public function icons(IconRepository $iconRepository)
    {
        return $iconRepository->all();
    }
}