<?php
/**
 * Created by PhpStorm.
 * User: apolon
 * Date: 4.2.2019
 * Time: 17:39
 */

namespace IPROMTestApp;

abstract class TransactionType
{
    const Withdraw = "Withdraw";
    const Deposit = "Deposit";
    // .. add other types (eg. Transfer)

    /***
     * Get transaction type based on transaction amount.
     *
     * @param float $transaction_amount
     * @return string
     * @throws \Exception Throw exception in case transaction amount is incorrect.
     */
    public static function getTransactionTypeBasedOnAmount(float $transaction_amount) {

        if($transaction_amount > 0) return self::Deposit;
        else if ($transaction_amount < 0) return self::Withdraw;
        else throw new \Exception("Invalid transaction amount.");
    }
}

class Transaction
{
    use Helper;

    public $id;
    public $user_id;
    public $date;
    public $amount;
    public $type;

    public function __construct(array $data = NULL)
    {
        if(isset($data)) {

            $data = (object) $data;
            $this->id = $data->id ?? NULL;
            $this->user_id = $data->user_id ?? NULL;
            $this->date = $data->date ?? NULL;
            $this->amount = $data->amount ?? NULL;
            $this->type = $data->type ?? NULL;
        }
    }

    public static function seedTestData(int $number_of_transactions, User $user, \DateTime $transactions_start_date, \DateTime $transactions_end_date) {

        try {

            $transactions = [];

            for($i = 0; $i < $number_of_transactions; $i++) {

                $random_transaction_amount = Helper::generateRandomFloatNumber(-10000, 10000);

                $transaction = new Transaction();
                $transaction->user_id = $user->id;
                $transaction->date = Helper::returnRandomDate($transactions_start_date, $transactions_end_date)->format("Y-m-d H:i:s");
                $transaction->amount = $random_transaction_amount;
                $transaction->type = TransactionType::getTransactionTypeBasedOnAmount($random_transaction_amount);
                $transaction->id = DAL::Instance()->db->insert('transactions', (array) $transaction);

                array_push($transactions, $transaction);
            }

        } catch (\Exception $e) {

            echo "\nWarning: " . $e->getMessage() . "\n";
        }

    }

    /***
     * @param User $user
     * @return Transaction[]
     */
    public static function getAllUserTransactions(User $user) {

        try {

            $result = DAL::Instance()->db->fetchRowMany("SELECT t.* FROM transactions as t where t.user_id = :user_id ORDER BY t.date asc", ["user_id" => $user->id]);
            $transactions = [];

            if(isset($result) && is_array($result) && count($result) > 0) {

                foreach($result as $data) {

                    array_push($transactions, new Transaction($data));
                }
            }

            return $transactions;

        } catch (\Exception $e) {

            echo $e->getMessage();
            return [];
        }
    }

    /***
     * Get all user transactions by months for specific year
     *
     * @param User $user
     * @param int $month if provided - display only transactions within this month
     * @return array
     */
    public static function getAllUserTransactionsByMonth(User $user, int $month = NULL) {

        try {

            $monthly_transactions = [];

            for($i = $month ?? 1; $i < 13; $i++) {

                $result = DAL::Instance()->db->fetchRowMany("SELECT t.* FROM transactions as t WHERE MONTH(t.date) = :month ORDER BY t.date ASC", ["month" => $i]);

                if(isset($result) && is_array($result) && count($result) > 0) {

                    foreach($result as $data) {

                        array_push($monthly_transactions, new Transaction($data));
                    }

                    if(isset($month)) break;
                }
            }

            return $monthly_transactions;

        } catch (\Exception $e) {

            echo $e->getMessage();
            return [];
        }

    }

}