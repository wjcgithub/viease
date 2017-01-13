<?php

namespace App\Console\Commands;

use App\Services\CurentWex;
use Illuminate\Console\Command;
use App\Models\Fan as FanModel;
use App\Models\Account;
use App\Repositories\AccountRepository;

class Fans extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:fans {account_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '通过account_id同步线上粉丝列表';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $accountId = $this->argument('account_id');
        /*
         * 1 获取Account
         */
        $account = $this->getAccount($accountId);

        $fanModel = new FanModel();

        /*
         * 2 初始化 SDK Config, 构建 SDK 对象
         */

        $userService = CurentWex::getWex($account)->user;

        $nextOpenId = null;

        /*
         * 3.1 先取消关注(设置为当前时间)
         */
        $fanModel->where('account_id', $accountId)->delete();

        $fans = $userService->lists();

        $total = $fans['total'];

        $this->info("\n获取粉丝列表...");

        $this->output->progressStart($total);

        while (!empty($fans['data'])) {
            $inserts = [];

            $openId = null;

            /*
             * 3.2 插入数据
             */
            foreach ($fans['data']['openid'] as $openId) {
                $input['openid'] = $openId;
                $input['account_id'] = $accountId;
                $input['created_at'] = date('Y-m-d H:i:s');
                $input['updated_at'] = date('Y-m-d H:i:s');
                $input['deleted_at'] = null;

                $inserts[] = $input;

                if (count($inserts) >= 200) {
                    $fanModel->insert($inserts);
                    $inserts = [];
                }

                $this->output->progressAdvance();
            }

            $fanModel->insert($inserts);

            $fans = $userService->lists($openId);
        }

        $this->call('sync:fan_details', array('account_id' => $account->id));

        $this->output->progressFinish();
    }

    /**
     * 获取Account.
     *
     * @param Int $id AccountID
     */
    private function getAccount($accountId)
    {
        $accountRepository = new AccountRepository(new Account());

        return $accountRepository->getById($accountId);
    }
}
