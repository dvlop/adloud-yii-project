<?php
namespace core;
use core\MoneyTransaction;
use core\PostgreSQL;
use core\RedisConnection;
use core\Session;
use Exception;
use PDO;

/**
 * Created by t0m
 * Date: 05.01.14
 * Time: 14:50
 *
 * @property $transactions MoneyTransaction[]
 */
class TransactionManager
{
    /**
     * @var MoneyTransaction[]
     */
    private $transactions;

    public function register(MoneyTransaction $transaction)
    {
        $this->transactions[] = $transaction;
    }

    public function execute()
    {
        $pdo = PostgreSQL::getInstance()->getConnection(Session::getInstance(), 'actual_data');

        try {
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();
            $transactionResult = array();

            foreach ($this->transactions as $key => $transaction) {
                $transactionResult[] = $transaction->execute();
            }

            $pdo->commit();
        }catch(Exception $e){
            $pdo->rollBack();
            throw $e;
        }

        return $transactionResult;
    }
}