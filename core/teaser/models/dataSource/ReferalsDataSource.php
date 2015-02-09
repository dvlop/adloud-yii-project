<?php
/**
 * Created by JetBrains PhpStorm.
 * User: JanGolle
 * Date: 28.07.14
 * Time: 14:58
 * To change this template use File | Settings | File Templates.
 */

namespace models\dataSource;

use application\modules\admin\controllers\MoneyController;
use core\MoneyTransaction;
use core\TransactionManager;
use models\MoneyPayouts;

class ReferalsDataSource extends DataSourceLayer {

    public function getRequestList($params) {
        $sql = 'SELECT *
                FROM referal_stats';

        if(isset($params['moderation']) && $params['moderation']){
            $sql .= ' WHERE moderation = :moderation';
            $keys = [
                ':moderation' => $params['moderation']
            ];
        } else {
            $sql .= ' WHERE moderation IS NULL';
            $keys = [];
        }

        $sql .= ' ORDER BY id DESC';

        $data = $this->runQuery($sql, $this->pdoPersistent, $keys);

        foreach($data as $key => $request) {
            if(!$request['moderation']){
                $data[$key]['showActivateLink'] = true;
                $data[$key]['showDeactivateLink'] = true;
            } else {
                $data[$key]['showActivateLink'] = false;
                $data[$key]['showDeactivateLink'] = false;
            }
        }

        return $data;
    }

    public function acceptRequest($id){
        $this->referalTransaction($id);

        $sql = 'UPDATE referal_stats SET status = TRUE, moderation = \'accepted\' WHERE id = :id';

        return $this->runQuery($sql, $this->pdoPersistent,[
           ':id' => $id
        ]);
    }

    public function denyRequest($id){
        $sql = 'UPDATE referal_stats SET moderation = \'denied\' WHERE id = :id';

        return $this->runQuery($sql, $this->pdoPersistent,[
            ':id' => $id
        ]);
    }

    private function referalTransaction($id){
        $sql = 'SELECT * FROM referal_stats WHERE id = :id';

        $data = $this->runQuery($sql, $this->pdoPersistent, [
           ':id' => $id
        ]);

        if($data[0]['referer_id'])
            $recipient = $data[0]['referer_id'];
        if($data[0]['sum'])
            $sum = $data[0]['sum'];

        $toWebmaster = new MoneyTransaction();
        $toWebmaster->setType(MoneyTransaction::SYSTEM_TO_WEBMASTER);
        $toWebmaster->setRecipient($recipient);
        $toWebmaster->setAmount($sum);
        $toWebmaster->setDescription('referal payment');

        $transactionManager = new TransactionManager();
        $transactionManager->register($toWebmaster);
        $transactionManager->execute();
    }

}