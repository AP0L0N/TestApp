<?php
/**
 * Created by PhpStorm.
 * User: apolon
 * Date: 4.2.2019
 * Time: 17:40
 */

namespace IPROMTestApp;

use IPROMTestApp\User;
use IPROMTestApp\Transaction;
use cli\Table;

/***
 * Class Bank
 */
class Bank
{

    /***
     * Populate users and transactions table with test data
     */
    public static function randomize() {

        User::seedTestData();
    }

    /***
     * Check the current balance of all the users
     */
    public static function balance() {

        $table = new Table();
        $table->setHeaders([
            "ID",
            "name",
            "balance"
        ]);

        foreach(User::getAllUsers() as $user) {

            $table->addRow([
                "ID" => $user->id,
                "name" => $user->getName(),
                "balance" => $user->getCurrentBankBalance()
            ]);
        }

        $table->display();
    }

    /***
     * Get all the user transactions grouped by months
     */
    public static function transactions() {

        foreach(User::getAllUsers() as $user) {

            $table = new Table();
            $table->setHeaders([
                "ID: " . $user->id . " (" .$user->getName(). ")",
                "transaction ID",
                "amount",
                "type",
                "date"
            ]);

            $transactions = Transaction::getAllUserTransactions($user);

            foreach ($transactions as $transaction) {

                $table->addRow([
                    "" => "------------------------------",
                    "transaction ID" => $transaction->id,
                    "amount" => $transaction->amount,
                    "type" => $transaction->type,
                    "date" => $transaction->date
                ]);
            }

            $table->display();
        }

    }

    /***
     * Get all the user transactions by specific month
     * @param int $month
     */
    public static function dailyTransactions(int $month) {

        foreach(User::getAllUsers() as $user) {

            $table = new Table();
            $table->setHeaders([
                "ID: " . $user->id . " (" .$user->getName(). ") .. (".\DateTime::createFromFormat("m", $month)->format("M").")",
                "transaction ID",
                "amount",
                "type",
                "date"
            ]);

            $transactions_by_months = Transaction::getAllUserTransactionsByMonth($user, $month);

            foreach ($transactions_by_months as $transaction) {

                $table->addRow([
                    "" => "------------------------------",
                    "transaction ID" => $transaction->id,
                    "amount" => $transaction->amount,
                    "type" => $transaction->type,
                    "date" => $transaction->date
                ]);
            }

            $table->display();
        }
    }

    /***
     * Get the days the users had negative balance on their account
     */
    public static function negativeBalance() {

        foreach(User::getAllUsers() as $user) {

            $table = new Table();
            $table->setHeaders([
                "ID: " . $user->id . " (" .$user->getName(). ")",
                "balance",
                "date"
            ]);

            $negative_balances = $user->listAllUserNegativeDailyBalances();

            foreach($negative_balances as $balance) {

                $table->addRow([
                    "" => "------------------------------",
                    "balance" => $balance->balance,
                    "date" => $balance->date
                ]);
            }

            $table->display();
        }
    }
}