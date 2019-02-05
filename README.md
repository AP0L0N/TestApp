# PHP Test App
Simple Bank app that saves and displays users transactions

## Setup
1. import the database/structure.sql to your database
1. copy the .env.example to .env and add your database settings

## Run
example run; using this command in terminal: php -r 'include "index.php";'
either update index.php to use specific methods of the Bank object or update the terminal command.

## Available commands are:
* Bank::randomize()
* Bank::balance()
* Bank::transactions()
* Bank::dailyTransactions(4)
* Bank::negativeBalance()

# HTML Post Message
Simple test app that handles dispatching and handling of events between the iframe and parent document.
1. The iframe dispatches the event when the hover over DIV elements with class .square occur,
1. parent document listens for that same events and handles it

## Run
1. Update the targetOrigin variable in the iframe.html accordingly