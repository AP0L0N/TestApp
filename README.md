# Test App

## Setup
- import the database/structure.sql to your database
- copy the .env.example to .env and add your database settings

## Run
example run; using this command in terminal: php -r 'include "index.php";'
either update index.php to use specific methods of the Bank object or update the terminal command.

## Available commands are:
Bank::randomize();
Bank::balance();
Bank::transactions();
Bank::dailyTransactions(4);
Bank::negativeBalance();