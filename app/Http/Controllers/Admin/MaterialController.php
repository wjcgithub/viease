<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Material\ArticleRequest;
use App\Http\Requests\Material\VideoRequest;
use App\Http\Requests\Material\voiceRequest;
use App\Models\Material;
use App\Repositories\MaterialRepository;
use App\Http\Controllers\Controller;
use App\Services\CurentWex;
use EasyWeChat\Broadcast\Broadcast;
use Illuminate\Http\Request;
use App\Models\Account;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
//        DB::beginTransaction();
//        $affected = DB::update('update left2 set name = "lisi" where size in (10,20)');
//        echo "update->".$affected."\r\n";
//        $affected = DB::delete('delete from left2 where size not in (10)');
//        echo "delete->".$affected;
//        DB::commit();
//        die();
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
     * 获取指定的素材
     * @param Request $request
     * @return \App\Repositories\Response
     */
    public function getMaterialById(Request $request, $id)
    {
        $articles = $this->materialRepository->getByid($id);
        return json_encode($articles);
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
        try{
//            $broadcast = CurentWex::getWex()->broadcast;
//            $messageType = Broadcast::MSG_TYPE_NEWS;
//            $media_id = 'sSUvROGbJHhPwch-6RuuqmSp9zwFjhVXIFCQRvtamAs';
//            $openidArr = ['ob43SwB_v59vBhVkLpT0lQdYgQKk','ob43SwHjEhm8Ksd7gAEuqntLAiFY','ob43SwJdR_RCIfdFqfJYiGgxlybE'];
//            $broadcast->send($messageType, $media_id, $openidArr);
//            die;
        }catch (\Exception $e){
            Log::info($e->getTraceAsString());
        }
        $articleId = $request->get('article_id');
        $rest = $this->materialRepository->storeArticle($this->account()->id,$request->get('article'),NULL,Material::CREATED_FROM_SELF);
        return response(['media_id'=>$rest]);
    }

    /**
     * 展示修改文章.
     *
     * @param int $id id
     */
    public function getArticleUpdate(Request $request)
    {
        $account = $this->materialRepository->getById($request->get('id'));

        return admin_view('material.new-article', compact('account'));
    }

    /**
     * 提交文章修改.
     *
     * @param int           $id      id
     * @param UpdateRequest $request request
     *
     * @return Redirect
     */
    public function postArticleUpdate(UpdateRequest $request, $id)
    {
        $this->accountRepository->update($id, $request);

        return redirect(admin_url('account'))->withMessage('修改成功！');
    }

    /**
     * 删除公众号.
     *
     * @param ineger $id 公众号iD
     */
    public function getDestroy($id)
    {
        $this->accountRepository->destroy($id);

        return redirect(admin_url('account'))->withMessage('删除成功！');
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
