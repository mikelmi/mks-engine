<?php

namespace App\Http\Controllers;


use App\Services\FileManager;
use App\Services\Image;
use Illuminate\Http\Request;
use League\Glide\Responses\LaravelResponseFactory;
use League\Glide\ServerFactory;

class FileManagerController extends Controller
{
    public function index()
    {
        return view('filemanager.index');
    }

    /**
     * Handle angular-filemanager api
     * 
     * @param Request $request
     * @param FileManager $fm
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, FileManager $fm)
    {
        $this->validate($request, [
            'action' => 'required|alpha_dash'
        ]);

        $method = strtolower($request->method()) . ucfirst($request->get('action'));

        if (!method_exists($this, $method)) {
            abort(500, 'Method ' . $method . ' not found');
        }

        return $this->callAction($method, [$request, $fm]);
    }

    /**
     * List files
     * 
     * @param Request $request
     * @param FileManager $fm
     * @return \Illuminate\Http\JsonResponse
     * @throws \League\Flysystem\FileNotFoundException
     */
    public function postList(Request $request, FileManager $fm)
    {
        $this->validate($request, [
            'path' => 'required'
        ]);

        return $this->resultResponse($fm->getList($request->get('path')));
    }

    /**
     * Create new folder
     * 
     * @param Request $request
     * @param FileManager $fileManager
     * @return \Illuminate\Http\JsonResponse
     */
    public function postCreateFolder(Request $request, FileManager $fileManager)
    {
        $this->validate($request, [
            'newPath' => 'required'
        ]);

        $path = $request->get('newPath');

        if ($fileManager->exists($path)) {
           return $this->errorResponse($this->t('Folder already exists'));
        }

        if (!$fileManager->makeDirectory($path)) {
            return $this->errorResponse($this->t('Folder creation failed'));
        }

        return $this->successResponse();
    }

    /**
     * Remove files
     * 
     * @param Request $request
     * @param FileManager $fileManager
     * @return \Illuminate\Http\JsonResponse
     */
    public function postRemove(Request $request, FileManager $fileManager)
    {
        $this->validate($request, [
            'items' => 'required|array',
        ]);

        $items = $request->get('items');

        if (!$fileManager->remove($items)) {
            return $this->errorResponse($this->t('Removing failed'));
        }

        return $this->successResponse();
    }

    /**
     * Rename file
     *
     * @param Request $request
     * @param FileManager $fileManager
     * @return \Illuminate\Http\JsonResponse
     */
    public function postRename(Request $request, FileManager $fileManager)
    {
        $this->validate($request, [
            'item' => 'required',
            'newItemPath' => 'required'
        ]);

        $old = $request->get('item');
        $new = $request->get('newItemPath');

        if (!$fileManager->exists($old)) {
            return $this->errorResponse($this->t('File not found'));
        }

        if (!$fileManager->rename($old, $new)) {
            return $this->errorResponse($this->t('Renaming failed'));
        }

        return $this->successResponse();
    }

    /**
     * Move files
     *
     * @param Request $request
     * @param FileManager $fileManager
     * @return \Illuminate\Http\JsonResponse
     */
    public function postMove(Request $request, FileManager $fileManager)
    {
        $this->validate($request, [
            'items' => 'required|array',
            'newPath' => 'required'
        ]);

        $items = $request->get('items');
        $newPath = $request->get('newPath');

        foreach ($items as $item) {
            if (!$fileManager->exists($item)) {
                continue;
            }

            if (!$fileManager->move($item, $newPath)) {
                return $this->errorResponse($this->t('Moving failed'));
            }
        }

        return $this->successResponse();
    }

    /**
     * Copy files
     *
     * @param Request $request
     * @param FileManager $fileManager
     * @return \Illuminate\Http\JsonResponse
     */
    public function postCopy(Request $request, FileManager $fileManager)
    {
        $this->validate($request, [
            'items' => 'required|array',
            'newPath' => 'required'
        ]);

        $items = $request->get('items');
        $newPath = $request->get('newPath');
        $singleFilename = basename($request->get('singleFilename'));

        foreach ($items as $item) {
            if (!$fileManager->exists($item)) {
                continue;
            }

            if (!copy($item, $newPath, $singleFilename)) {
                return $this->errorResponse($this->t('Copying failed'));
            }
        }

        return $this->successResponse();
    }

    /**
     * Change permissions
     *
     * @param Request $request
     * @param FileManager $fileManager
     * @return \Illuminate\Http\JsonResponse
     */
    public function postChangePermissions(Request $request, FileManager $fileManager)
    {
        $this->validate($request, [
            'items' => 'required|array',
            'permsCode' => 'required|digits:3',
        ]);

        $items = $request->get('items');
        $permissions = octdec($request->get('permsCode'));
        $recursive = $request->get('recursive', false);

        if (!$fileManager->chmod($items, $permissions, $recursive)) {
            return $this->errorResponse($this->t('Permissions change failed'));
        }

        return $this->successResponse();
    }

    /**
     * Compress files
     * 
     * @param Request $request
     * @param FileManager $fileManager
     * @return \Illuminate\Http\JsonResponse
     */
    public function postCompress(Request $request, FileManager $fileManager)
    {
        $this->validate($request, [
            'items' => 'required|array',
            'destination' => 'required',
            'compressedFilename' => 'required',
        ]);

        $items = $request->get('items');
        $destination = $request->get('destination') . DIRECTORY_SEPARATOR . $request->get('compressedFilename');

        if (!$fileManager->compress($items, $destination)) {
            return $this->errorResponse($this->t('Compression failed'));
        }

        return $this->successResponse();
    }

    /**
     * Extract files
     *
     * @param Request $request
     * @param FileManager $fileManager
     * @return \Illuminate\Http\JsonResponse
     * @throws \League\Flysystem\FileNotFoundException
     */
    public function postExtract(Request $request, FileManager $fileManager)
    {
        $this->validate($request, [
            'destination' => 'required',
            'item' => 'required',
            'folderName' => 'required'
        ]);

        $destination = $request->get('destination') . DIRECTORY_SEPARATOR . $request->get('folderName');
        $item = $request->get('item');

        if (!$fileManager->extract($item, $destination)) {
            return $this->errorResponse($this->t('Extraction failed'));
        }

        return $this->successResponse();
    }

    /**
     * @param Request $request
     * @param FileManager $fileManager
     * @return \Illuminate\Http\JsonResponse
     */
    public function upload(Request $request, FileManager $fileManager)
    {
        $this->validate($request, [
            'destination' => 'required',
        ]);

        $destination = $request->get('destination');

        /** @var \Illuminate\Http\UploadedFile $file */
        foreach ($request->allFiles() as $file) {
            $fileManager->upload($file, $destination, $file->getClientOriginalName());
        }

        return $this->successResponse();
    }

    /**
     * Get file content
     * 
     * @param Request $request
     * @param FileManager $fileManager
     * @return \Illuminate\Http\JsonResponse
     */
    public function postGetContent(Request $request, FileManager $fileManager)
    {
        $this->validate($request, [
            'item' => 'required',
        ]);

        $item = $request->get('item');

        if (!$fileManager->isFile($item)) {
            return $this->errorResponse($this->t('File not found'));
        }

        return $this->resultResponse($fileManager->get($item));
    }

    /**
     * Save file content
     * 
     * @param Request $request
     * @param FileManager $fileManager
     * @return \Illuminate\Http\JsonResponse
     */
    public function postEdit(Request $request, FileManager $fileManager)
    {
        $this->validate($request, [
            'item' => 'required',
        ]);

        $item = $request->get('item');

        if (!$fileManager->isFile($item)) {
            return $this->errorResponse($this->t('File not found'));
        }

        if (!$fileManager->put($item, $request->get('content'))) {
            return $this->errorResponse($this->t('Saving failed'));
        }

        return $this->successResponse();
    }

    /**
     * Download file
     *
     * @param Request $request
     * @param FileManager $fileManager
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function download(Request $request, FileManager $fileManager)
    {
        $this->validate($request, [
            'path' => 'required'
        ]);

        $file = $request->get('path');

        return response()->download($fileManager->getFile($file));
    }

    /**
     * Download multiple files
     *
     * @param Request $request
     * @param FileManager $fileManager
     * @return \Illuminate\Http\JsonResponse
     */
    public function downloadMulti(Request $request, FileManager $fileManager)
    {
        $this->validate($request, [
            'items' => 'required|array',
            'toFilename' => 'required',
        ]);

        $items = $request->get('items');
        $filename = basename($request->get('toFilename'));

        $file = $fileManager->compress($items);

        if (!$file) {
            return $this->errorResponse($this->t('Compression failed'));
        }

        return response()->download($file, $filename)->deleteFileAfterSend(true);
    }

    public function thumbnail(Request $request, Image $image, $path)
    {
        return $image->response(
            $request,
            $path,
            ['p' => $request->get('p', 'filemanager')]
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

    /**
     * @param $name
     * @param array $params
     * @return string
     */
    private function t($name, array $params = [])
    {
        return trans('filemanager.'.$name, $params);
    }
}