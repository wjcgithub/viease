<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Menu\CreateRequest;
use App\Services\Menu as MenuService;
use App\Repositories\MenuRepository;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * 菜单管理.
 *
 * @author rongyouyuan <rongyouyuan@163.com>
 */
class MenuController extends Controller
{
    /**
     * MenuRepository.
     *
     * @var App\Repositories\MenuRepository;
     */
    private $menuRepository;

    /**
     * construct.
     *
     * @param MenuRepository $menu
     */
    public function __construct(MenuRepository $menuRepository)
    {
        $this->menuRepository = $menuRepository;
    }

    /**
     * 菜单.
     */
    public function getIndex()
    {
        return admin_view('menu.index');
    }

    /**
     * 获取菜单列表.
     *
     * @return Response
     */
    public function getLists()
    {
        $menus = $this->menuRepository->lists($this->account()->id)->toArray();

        return $this->menuRepository->withMaterials($menus);
    }

    /**
     * 保存菜单.
     *
     * @param CreateRequest $request request
     */
    public function postStore(CreateRequest $request)
    {
        $accountId = $this->account()->id;

        $this->menuRepository->destroyMenu($accountId);

        $menus = $this->menuRepository->parseMenus($accountId, $request->get('menus'));

        $this->menuRepository->storeMulti($accountId, $menus);

        return response()->json(['status' => true]);
    }
}
