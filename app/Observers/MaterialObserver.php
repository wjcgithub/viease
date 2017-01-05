<?php

namespace App\Observers;

use App\Services\Material as MaterialService;
use App\Models\Material;
use Illuminate\Support\Facades\Log;

/**
 * Material模型观察者.
 *
 * @author rongyouyuan <rongyouyuan@163.com>
 */
class MaterialObserver
{
    /**
     * 素材服务
     *
     * @var App\Services\Material
     */
    private $materialService;

    public function __construct(MaterialService $materialService)
    {
        $this->materialService = $materialService;
    }

    public function saving(Material $material)
    {
        if (!$material->media_id) {
            $material->media_id = $this->materialService->buildMaterialMediaId();
        }
    }

    public function created(Material $material)
    {
        Log::info('created事件触发完成：'.json_encode($material));
        //artile类型不可放到监听中
        if ($material->type != 'article' && !$material->original_id && $material->parent_id) {
            Log::info('查询original_id：'.'obj is'.json_encode($material));
            $material->original_id = $this->materialService->postToRemote($material);
        }else{
            Log::info('上传缩略图：'.'obj is'.json_encode($material));
            $material->original_id = $this->materialService->postToRemote($material);
        }
    }
}
