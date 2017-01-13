<?php

namespace App\Console\Commands;

use App\Services\CurentWex;
use EasyWeChat\Support\Log;
use Illuminate\Console\Command;
use App\Models\Fan as FanModel;
use App\Models\Account;
use App\Services\Fan as FanService;
use App\Repositories\AccountRepository;

class FanDetails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:fan_details {account_id}';

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

        $total = $fanModel->where('account_id', $accountId)->count();

        /*
         * 3.3 获取粉丝详细信息并更新本地
         */
        $this->performUpdateUserDetail($total, $fanModel, $account);
    }

    /**
     * 分段更新本地用户资料.
     *
     * @param int      $total
     * @param FanModel $fanModel
     * @param Account  $accountId
     */
    public function performUpdateUserDetail($total, $fanModel, $account)
    {
        $this->info("\n开始更新用户资料...");

        $this->output->progressStart($total);

        $userService = CurentWex::getWex($account)->user;
        $fanService = new FanService();

        $accountId = $account->id;

        $fanModel->where('account_id', $accountId)
                 ->orderBy('id', 'desc')
                 ->chunk(100,
                    function ($fans) use ($userService, $accountId, $fanModel, $fanService) {

                        Log::info('----------------------------');
                        Log::info(json_encode($fans));
                        Log::info(json_encode($fans->lists('openid')));
                        Log::info(json_encode($fans->lists('openid')->toArray()));
                        Log::info('----------------------------');

                        $fans = $userService->batchGet($fans->lists('openid')->toArray());

                        foreach ($fans as $fan) {
                            Log::info('$fan-info'. json_decode($fan));
                            $fan = $fanService->formatFromWeChat($fan);
                            $fanModel->where('openid', $fan['openid'])->update($fan);
                            $this->output->progressAdvance();
                        }
                    });

        $this->output->progressFinish();
        $this->info("\n同步完成。");
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
