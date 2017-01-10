<?php

namespace App\Services;

use App\Services\Message as MessageService;
use App\Repositories\ReplyRepository;
use Cache;

/**
 * 回复服务.
 *
 * @author rongyouyuan <rongyouyuan@163.com>
 */
class Server
{
    /**
     * 消息服务
     *
     * @var App\Services\Message
     */
    private $messageService;

    /**
     * replyRepository.
     *
     * @var App\Repositories\ReplyRepository
     */
    private $replyRepository;

    /**
     * constructer.
     *
     * @param MessageService $messageService 消息服务
     */
    public function __construct(MessageService $messageService, ReplyRepository $replyRepository)
    {
        $this->messageService = $messageService;

        $this->replyRepository = $replyRepository;
    }

    /**
     * 返回服务器.
     *
     * @param App\Models\Account $account account
     *
     * @return Response
     */
    public function make($account)
    {
        $server = CurentWex::getWex($account)->serve;

        $server->setMessageHandler(function ($message) {
            // $message->FromUserName // 用户的 openid
            // $message->MsgType // 消息类型：event, text....
            switch ($message->MsgType) {
                case 'event':
                    # 事件消息...
                    break;
                case 'text':
                    # 文字消息...
                    break;
                case 'image':
                    # 图片消息...
                    break;
                case 'voice':
                    # 语音消息...
                    break;
                case 'video':
                    # 视频消息...
                    break;
                case 'location':
                    # 坐标消息...
                    break;
                case 'link':
                    # 链接消息...
                    break;
                // ... 其它消息
                default:
                    # code...
                    break;
            }
            return "您好！欢迎关注我!";
        });

        $response = $server->serve();
        return $response;
    }

    /**
     * 处理事件.
     *
     * @param int                    $account 公众号
     * @param array                  $event   事件
     * @param Overtrue\Wechat\Server $server  server
     *
     * @return Response
     */
    private function handleEvent($account, $event, $server)
    {
        if ($event['Event'] == 'subscribe') {
            return $this->handleSubscribe($account);
        }
    }

    /**
     * 处理订阅时的消息.
     *
     * @return Response
     */
    private function handleSubscribe($account)
    {
        $event = $this->replyRepository->getFollowReply($account->id);

        $eventId = $event['content'][0];

        return $eventId ? $this->messageService->eventToMessage($eventId) : $this->messageService->emptyMessage();
    }

    /**
     * 处理未匹配时的回复.
     *
     * @return Response
     */
    private function handleNoMatch($account)
    {
        $event = $this->replyRepository->getNoMatchReply($account->id);

        $eventId = $event['content'][0];

        return $eventId ? $this->messageService->eventToMessage($eventId) : $this->messageService->emptyMessage();
    }

    /**
     * 处理消息.
     *
     * @param int                    $account 公众号
     * @param array                  $message 消息
     * @param Overtrue\Wechat\Server $server  server
     *
     * @return Response
     */
    private function handleMessage($account, $message, $server)
    {
        //存储消息
        $this->messageService->storeMessage($account, $message);
        //属于文字类型消息
        if ($message['MsgType'] == 'text') {
            $replies = (array) Cache::get('replies_'.$account->id);

            if (empty($replies)) {
                return $this->handleNoMatch($account);
            }

            foreach ($replies as $key => $reply) {
                //查找字符串
                if (str_contains($message['Content'], $key)) {
                    return $this->messageService->eventsToMessage($reply['content']);
                }
            }

            return $this->handleNoMatch($account);
        }
    }
}
