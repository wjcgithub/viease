<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Material\ArticleRequest;
use App\Http\Requests\Material\VideoRequest;
use App\Http\Requests\Material\voiceRequest;
use App\Repositories\MaterialRepository;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Account;

/**
 * 素材管理.
 *
 * @author rongyouyuan <rongyouyuan@163.com>
 */
class MaterialController extends Controller
{
    /**
     * 分页数目.
     *
     * @var int
     */
    private $pageSize = 12;

    /**
     * materialRepository.
     *
     * @var app\Repositories\MaterialRepository
     */
    private $materialRepository;

    /**
     * construct.
     */
    public function __construct(MaterialRepository $materialRepository)
    {
        $this->materialRepository = $materialRepository;
    }

    /**
     * 取得素材列表.
     */
    public function getIndex()
    {
        return admin_view('material.index');
    }

    public function getImglib()
    {
        if(isset($_REQUEST['ajax'])){
            $data = $this->materialRepository->getList($this->account()->id, 'image', 10)->toArray();
            $count = $this->materialRepository->countImage($this->account()->id);
            $data['total'] =$count;
            return $data;
        }
        return admin_view('material.imglib');
    }

    /**
     * 取得素材列表.
     *
     * @param Request $request request
     */
    public function getLists(Request $request)
    {
        $pageSize = $request->get('page_size', $this->pageSize);

        return $this->materialRepository->getList($this->account()->id, $request->get('type'), $pageSize);
    }

    /**
     * 获取素材.
     *
     * @param Request $request request
     *
     * @return Response
     */
    public function getShow(Request $request)
    {
        if ($request->has('media_id')) {
            return $this->materialRepository->getMediaByMediaId($request->media_id);
        } else {
            return $this->materialRepository->getMediaById($request->id);
        }
    }

    /**
     * 统计素材数量.
     *
     * @return array
     */
    public function getSummary()
    {
        return [
            'image' => $this->materialRepository->countImage($this->account()->id),
            'video' => $this->materialRepository->countVoide($this->account()->id),
            'voice' => $this->materialRepository->countVoice($this->account()->id),
            'article' => $this->materialRepository->countArticle($this->account()->id),
        ];
    }

    /**
     * 创建新文章.
     *
     * @param string $value value
     */
    public function getNewArticle($value = '')
    {
        return  admin_view('material.new-article');
    }

    /**
     * 创建新图文.
     *
     * @param ArticleRequest $request request
     */
    public function postNewArticle(ArticleRequest $request)
    {
        return $this->materialRepository->storeArticle($request->get('article'));
    }

    /**
     * 创建声音.
     *
     * @param voiceRequest $request request
     */
    public function postVoice(voiceRequest $request)
    {
        return $this->materialRepository->storeVoice($this->account()->id, $request);
    }

    /**
     * 创建视频.
     *
     * @param VideoRequest $request request
     */
    public function postVideo(VideoRequest $request)
    {
        return $this->materialRepository->storeVideo($this->account()->id, $request);
    }
}
