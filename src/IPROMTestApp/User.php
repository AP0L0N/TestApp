<?php
/**
 * Created by PhpStorm.
 * User: apolon
 * Date: 4.2.2019
 * Time: 17:40
 */

namespace IPROMTestApp;

use IPROMTestApp\Transaction;
use IPROMTestApp\DAL;

/***
 * Class User
 * @package IPROMTestApp
 */
class User
{
    use Helper;

    public $id;
    public $name;
    public $surname;
    public $birth_date;

    /***
     * User constructor.
     * @param array|NULL $data
     */
    public function __construct(array $data = NULL)
    {
        if(isset($data)) {

            $data = (object) $data;
            $this->id = $data->id ?? NULL;
            $this->name = $data->name ?? NULL;
            $this->surname = $data->surname ?? NULL;
            $this->birth_date = $data->birth_date ?? NULL;
        }
    }

    /***
     * Get the compiled name from name and surname
     * @return string
     */
    public function getName() : string {

        return ucfirst(strtolower($this->name)) . " " . ucfirst(strtolower($this->surname));
    }

    /***
     * Seed the database with test data
     *
     * @param int $number_of_users - Number of users you want to add to the database
     */
    public static function seedTestData(int $number_of_users = 5) : void {

        try {

            $users = [];

            for($i = 0; $i < $number_of_users; $i++) {

                $user = new User();
                $user->name = Helper::returnRandomName();
                $user->surname = Helper::returnRandomSurname();
                $user->birth_date = Helper::returnRandomDate()->format("Y-m-d H:i:s");
                $user->id = DAL::Instance()->db->insert('users', (array) $user);

                $transactions_start_date = new \DateTime("2018-01-01");
                $transactions_end_date = new \DateTime("2018-06-30");

                // Create test transactions for this user
                Transaction::seedTestData(rand(0,100), $user, $transactions_start_date, $transactions_end_date);

                array_push($users, $user);
            }

        } catch (\Exception $e) {

            echo $e->getMessage();
        }
    }

    /***
     * Get the user current bank account balance
     *
     * @return float|false
     */
    public function getCurrentBankBalance() : float {

        try {

            $sum_amount = DAL::Instance()->db->fetchColumn("SELECT SUM(t.amount) FROM transactions as t WHERE t.user_id = :user_id", ["user_id" => $this->id]);

            return $sum_amount ?? 0;

        } catch (\Exception $e) {

            echo $e->getMessage();
            return FALSE;
        }
    }

    /***
     * List all the days the users had negative bank account balance
     *
     * @return array
     */
    public function listAllUserNegativeDailyBalances() : array {

        $days_with_negative_balance = [];

        $last_negative_balance = [
            "balance" => 0,
            "date" => NULL
        ];

        $transactions = Transaction::getAllUserTransactions($this);

        foreach($transactions as $transaction) {

            $last_negative_balance["balance"] += $transaction->amount;

            if($last_negative_balance["balance"] < 0) {

                if(isset($last_negative_balance["date"])) {

                    $date1 = new \DateTime($last_negative_balance["date"]);
                    $date2 = new \DateTime($transaction->date);
                    $diff = $date2->diff($date1);
                }

                if(!isset($last_negative_balance["date"]) || (isset($diff) && (int) $diff->format('%a') > 0)) {

                    $last_negative_balance["date"] = $transaction->date;

                    array_push($days_with_negative_balance, (object) [
                        "balance" => $last_negative_balance["balance"],
                        "date" => $last_negative_balance["date"]
                    ]);
                }
            }
        }

        return $days_with_negative_balance;
    }

    /***
     * Fetch all the users from the database
     *
     * @return User[]
     */
    public static function getAllUsers() {

        try {

            $result = DAL::Instance()->db->fetchRowMany("SELECT * FROM users");
            $users = [];

            foreach($result as $data) {

                array_push($users, new User($data));
            }

            return $users;

        } catch (\Exception $e) {

            echo $e->getMessage();
            return [];
        }
    }

}