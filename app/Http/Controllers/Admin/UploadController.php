<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\MaterialRepository;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception;

class UploadController extends Controller
{
    /**
     * 识别 key.
     */
    const UPLOAD_KEY = 'file';

    /**
     * materialRepository.
     *
     * @var app\Repositories\MaterialRepository
     */
    private $materialRepository;

    public function __construct(MaterialRepository $materialRepository)
    {
        $this->materialRepository = $materialRepository;
    }

    /**
     * 上传文件.
     *
     * @return json
     */
    public function postIndex(Request $request)
    {
        if (!$request->get('type')) {
            throw new Exception('file type error.', 422);
        }

        if (!$request->hasFile(self::UPLOAD_KEY)) {
            throw new Exception('no file found.', 422);
        }

        $type = $request->get('type');
        $file = $request->file(self::UPLOAD_KEY);

        $filesize = $file->getSize();

        $mime = 'image/jpeg';

        //$mime = $file->mimeType();

        $ext = $this->checkMime($mime, $type);

        $this->checkSize($filesize, $type);

        $originalName = $file->getClientOriginalName();

        $filename = md5_file($file->getRealpath()).'.'.$ext;

        $dir = config('material.'.$type.'.storage_path');

        is_dir($dir) || mkdir($dir, 0755, true);

        if (!file_exists($dir.$filename)) {
            $file->move($dir, $filename);
        }

        if ('image' == $type) {
            return $this->saveImageMaterial($filename);
        }

        if ('voice' == $type) {
            return $this->saveVoiceMaterial($filename);
        }

        $response = [
            'originalName' => $originalName,
            'name' => $originalName,
            'size' => $filesize,
            'type' => ".{$ext}",
            'path' => $filename,
            'url' => config('material.'.$type.'.prefix').'/'.$filename,
            'state' => 'SUCCESS',
        ];

        return json_encode($response);
    }

    /**
     * 检查大小.
     *
     * @param int    $size
     * @param string $type 上传文件类型
     *
     * @throws Exception If too big.
     */
    protected function checkSize($size, $type)
    {
        if ($size > config('material.'.$type.'.upload_max_size')) {
            throw new Exception('To big file.', 422);
        }
    }

    /**
     * 检测Mime类型.
     *
     * @param string $mime mime类型
     * @param string $type 文件上传类型
     *
     * @return bool
     */
    protected function checkMime($mime, $type)
    {
        $allowTypes = config('material.'.$type.'.allow_types');

        if (!$ext = array_search($mime, $allowTypes)) {
            throw new Exception('Error file type', 422);
        }

        return $ext;
    }

    /**
     * 保存图片到素材.
     *
     * @param string $imagePath 图片路径
     *
     * @return Response
     */
    protected function saveImageMaterial($imagePath)
    {
        $resourceUrl = config('app.url').config('material.image.prefix').'/'.$imagePath;

        return $this->materialRepository->storeImage($this->account()->id, $resourceUrl);
    }

    public function anyWexImg(Request $request)
    {
        if($request->hasFile('upfile')){
            echo 1;
        }else{
            echo 2;
        }

        die;
    }
}
