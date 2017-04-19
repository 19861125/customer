<?php
/**
 * Server.php
 * 单进程版listen
 * 作者: 李红生 (dannyzml@qq.com)
 * 创建日期: 17/4/18 下午10:39
 * 修改记录:
 *
 * $Id$
 */

namespace Cli\Model;

use customer\Lib\Config;
use customer\Lib\PcntlModel;
use customer\Lib\RedisQueue;
use customer\Lib\WebSocket;
use customer\Lib\Timer;
use Cli\Model\Event;
use customer\Lib\Queue;

class Server extends Event
{
    private   $_PID = ''; //自身进程ID
    protected $ser;

    public function __construct()
    {
        $this->beforeWork();
        $this->pcntlModel  = new PcntlModel(1);
    }

    public function run()
    {
        $this->pcntlModel->setWork($this,'work');
        $this->pcntlModel->start();
    }

    public function work()
    {
        $this->_PID = posix_getpid();
        $this->ser = new WebSocket();
        $this->ser->setEvent($this);
        $this->ser->createAndListen('127.0.0.1','9701');
        while(true) {
            $this->ser->accept();
        }

    }

    protected function beforeWork(){

    }

    public function onConnect($id) {

    }

    public function onMessage($msg,$id) {
        $jsonMsg = json_decode($msg);
        switch($jsonMsg['type']) {
            case '1'://uid与clentid绑定
            case '2'://解绑uid与clentid
        }
    }

    public function onClose($id) {

    }
}