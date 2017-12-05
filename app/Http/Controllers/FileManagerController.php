<?php

namespace App\Http\Controllers;


use App\Services\FileManager;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class FileManagerController extends Controller
{
    /**
     * @var FileManager
     */
    protected $fm;

    protected $viewIndex = 'filemanager.index';

    /**
     * @return FileManager|\Illuminate\Foundation\Application|mixed
     */
    protected function fm()
    {
        if (!$this->fm) {
            $this->fm = app(FileManager::class);
        }

        return $this->fm;
    }

    protected function getHandlerUrl($params = null)
    {
        return route('filemanager.handler', $params);
    }

    protected function getUploadUrl($params = null)
    {
        return route('filemanager.upload', $params);
    }

    protected function getDownloadUrl($params = null)
    {
        return route('filemanager.download', $params);
    }

    protected function getDownloadMultipleUrl($params = null)
    {
        return route('filemanager.downloadMulti', $params);
    }

    public function index(Request $request)
    {
        $params = $request->only(['type']);

        if ($lang = $request->get('langCode')) {
            app()->setLocale($lang);
        }

        $handlerUrl = $this->getHandlerUrl($params);
        $uploadUrl = $this->getUploadUrl($params);
        $downloadUrl = $this->getDownloadUrl();
        $downloadMultipleUrl = $this->getDownloadMultipleUrl();

        return view($this->viewIndex, compact(
            'params', 'handlerUrl', 'uploadUrl', 'downloadUrl', 'downloadMultipleUrl'));
    }

    /**
     * Handle angular-filemanager api
     * 
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request)
    {
        $this->validate($request, [
            'action' => 'required|alpha_dash'
        ]);

        $method = strtolower($request->method()) . ucfirst($request->get('action'));

        if (!method_exists($this, $method)) {
            abort(500, 'Method ' . $method . ' not found');
        }

        return $this->callAction($method, [$request, $this->fm]);
    }

    /**
     * List files
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \League\Flysystem\FileNotFoundException
     */
    public function postList(Request $request)
    {
        $this->validate($request, [
            'path' => 'required'
        ]);

        return $this->resultResponse($this->fm()->getList($request->get('path'), $request->get('type')));
    }

    /**
     * Create new folder
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function postCreateFolder(Request $request)
    {
        $this->validate($request, [
            'newPath' => 'required'
        ]);

        $path = $request->get('newPath');

        if ($this->fm()->exists($path)) {
           return $this->errorResponse($this->t('Folder already exists'));
        }

        if (!$this->fm()->makeDirectory($path)) {
            return $this->errorResponse($this->t('Folder creation failed'));
        }

        return $this->successResponse();
    }

    /**
     * Remove files
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function postRemove(Request $request)
    {
        $this->validate($request, [
            'items' => 'required|array',
        ]);

        $items = $request->get('items');

        if (!$this->fm()->remove($items)) {
            return $this->errorResponse($this->t('Removing failed'));
        }

        return $this->successResponse();
    }

    /**
     * Rename file
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function postRename(Request $request)
    {
        $this->validate($request, [
            'item' => 'required',
            'newItemPath' => 'required'
        ]);

        $old = $request->get('item');
        $new = $request->get('newItemPath');

        if ($error = $this->isDenyExtension($request, $new)) {
            return $error;
        }

        if (!$this->fm()->exists($old)) {
            return $this->errorResponse($this->t('File not found'));
        }

        if (!$this->fm()->rename($old, $new)) {
            return $this->errorResponse($this->t('Renaming failed'));
        }

        return $this->successResponse();
    }

    /**
     * Move files
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function postMove(Request $request)
    {
        $this->validate($request, [
            'items' => 'required|array',
            'newPath' => 'required'
        ]);

        $items = $request->get('items');
        $newPath = $request->get('newPath');

        foreach ($items as $item) {
            if (!$this->fm()->exists($item)) {
                continue;
            }

            if (!$this->fm()->move($item, $newPath)) {
                return $this->errorResponse($this->t('Moving failed'));
            }
        }

        return $this->successResponse();
    }

    /**
     * Copy files
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function postCopy(Request $request)
    {
        $this->validate($request, [
            'items' => 'required|array',
            'newPath' => 'required'
        ]);

        $items = $request->get('items');
        $newPath = $request->get('newPath');
        $singleFilename = basename($request->get('singleFilename'));

        if ($error = $this->isDenyExtension($request, $singleFilename)) {
            return $error;
        }

        foreach ($items as $item) {
            if (!$this->fm()->exists($item)) {
                continue;
            }

            if (!$this->fm()->copy($item, $newPath, $singleFilename)) {
                return $this->errorResponse($this->t('Copying failed'));
            }
        }

        return $this->successResponse();
    }

    /**
     * Change permissions
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function postChangePermissions(Request $request)
    {
        $this->validate($request, [
            'items' => 'required|array',
            'permsCode' => 'required|digits:3',
        ]);

        $items = $request->get('items');
        $permissions = octdec($request->get('permsCode'));
        $recursive = $request->get('recursive', false);

        if (!$this->fm()->chmod($items, $permissions, $recursive)) {
            return $this->errorResponse($this->t('Permissions change failed'));
        }

        return $this->successResponse();
    }

    /**
     * Compress files
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function postCompress(Request $request)
    {
        $this->validate($request, [
            'items' => 'required|array',
            'destination' => 'required',
            'compressedFilename' => 'required',
        ]);

        $items = $request->get('items');
        $destination = $request->get('destination') . DIRECTORY_SEPARATOR . $request->get('compressedFilename');

        if (!$this->fm()->compress($items, $destination)) {
            return $this->errorResponse($this->t('Compression failed'));
        }

        return $this->successResponse();
    }

    /**
     * Extract files
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \League\Flysystem\FileNotFoundException
     */
    public function postExtract(Request $request)
    {
        $this->validate($request, [
            'destination' => 'required',
            'item' => 'required',
            'folderName' => 'required'
        ]);

        $destination = $request->get('destination') . DIRECTORY_SEPARATOR . $request->get('folderName');
        $item = $request->get('item');

        if (!$this->fm()->extract($item, $destination)) {
            return $this->errorResponse($this->t('Extraction failed'));
        }

        return $this->successResponse();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function upload(Request $request)
    {
        $rules = [
            'destination' => 'required',
        ];

        $type = $request->get('type');

        $extensions = $type ? $this->fm()->getTypeExtensions($type, []) : [];
        $maxSize = null;

        if (!$request->user()->isSuperAdmin()) {
            if ($allowedExtensions = settings('files.extensions')) {
                if (!is_array($allowedExtensions)) {
                    $allowedExtensions = explode(',', $extensions);
                }
                $extensions = array_intersect($extensions, $allowedExtensions);
            }

            $maxSize = settings('files.max_size');
        }

        if ($extensions || $maxSize) {
            $filesCount = $request->files->count();
            $mimes = $extensions ? 'mimes:'.implode(',', $extensions) : null;

            for($i = 0; $i < $filesCount; $i++) {
                $rule = $mimes;
                if ($maxSize) {
                    $rule = ($rule ? $rule.'|':'') . 'max:'.($maxSize*1024);
                }

                if (!$rule) {
                    continue;
                }

                $rules['file-'.$i] = $rule;
            }
        }

        try {
            $this->validate($request, $rules);
        } catch (ValidationException $e) {
            return $this->errorResponse($e->validator->getMessageBag()->first());
        }

        $destination = $request->get('destination');

        /** @var \Illuminate\Http\UploadedFile $file */
        foreach ($request->allFiles() as $file) {
            $this->fm()->upload($file, $destination, $file->getClientOriginalName());
        }

        return $this->successResponse();
    }

    /**
     * Get file content
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function postGetContent(Request $request)
    {
        $this->validate($request, [
            'item' => 'required',
        ]);

        $item = $request->get('item');

        if (!$this->fm()->isFile($item)) {
            return $this->errorResponse($this->t('File not found'));
        }

        return $this->resultResponse($this->fm()->get($item));
    }

    /**
     * Save file content
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function postEdit(Request $request)
    {
        $this->validate($request, [
            'item' => 'required',
        ]);

        $item = $request->get('item');

        if (!$this->fm()->isFile($item)) {
            return $this->errorResponse($this->t('File not found'));
        }

        if (!$this->fm()->put($item, $request->get('content'))) {
            return $this->errorResponse($this->t('Saving failed'));
        }

        return $this->successResponse();
    }

    /**
     * Download file
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function download(Request $request)
    {
        $this->validate($request, [
            'path' => 'required'
        ]);

        $file = $request->get('path');

        return response()->download($this->fm()->getFile($file));
    }

    /**
     * Download multiple files
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function downloadMulti(Request $request)
    {
        $this->validate($request, [
            'items' => 'required|array',
            'toFilename' => 'required',
        ]);

        $items = $request->get('items');
        $filename = basename($request->get('toFilename'));

        $file = $this->fm()->compress($items);

        if (!$file) {
            return $this->errorResponse($this->t('Compression failed'));
        }

        return response()->download($file, $filename)->deleteFileAfterSend(true);
    }

    public function thumbnail(Request $request, $path)
    {
        $image = app(ImageService::class);

        $preset = $request->get('p', 'filemanager');
        if (!$preset) {
            $preset = 'filemanager';
        }

        return $image->response(
            $request,
            $path,
            ['p' => $preset]
        );
    }

    public function imageProxy(Request $request, ImageService $image, $path)
    {
        if (!starts_with($path, 'files/')) {
            $path = 'files/' . $path;
        }

        $mark = starts_with($path, 'files/public/') ? null : settings('files.watermark');

        if ($mark && $mark != $path) {
            $params = [
                'mark' => $mark,
                'fit' => 'max',
                'w' => settings('files.max_width'),
                'h' => settings('files.max_height'),
                'markpos' => settings('files.mark_pos', 'bottom-right'),
                'markalpha' => settings('files.mark_aplha', 50),
                'markw' => settings('files.mark_width'),
                'markh' => settings('files.mark_height', 50),
                'markfit' => 'max'
            ];
        } else {
            $params = [];
        }

        return $image->response(
            $request,
            $path,
            $params
        );
    }

    private function resultResponse($data, $status = 200)
    {
        return response()->json(['result' => $data], $status);
    }

    private function successResponse()
    {
        return $this->resultResponse(['success' => true]);
    }

    private function errorResponse($message)
    {
        return $this->resultResponse(['success' => false, 'error' => $message], 500);
    }

    private function isDenyExtension(Request $request, $path, array $extensions = null)
    {
        if ($request->user()->isSuperAdmin()) {
            return false;
        }

        if ($extensions === null) {
            $extensions = settings('files.extensions');
        }

        $extension = pathinfo($path, PATHINFO_EXTENSION);

        if ($extensions && !in_array($extension, $extensions)) {
            return $this->errorResponse(__('validation.mimes', ['attribute' => $path, 'values' => implode(', ', $extensions)]));
        }
    }

    /**
     * @param $name
     * @param array $params
     * @return string
     */
    private function t($name, array $params = [])
    {
        return __('filemanager.'.$name, $params);
    }
}
