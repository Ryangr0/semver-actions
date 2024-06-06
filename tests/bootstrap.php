<?php

# Don't echo notices
error_reporting(-1);

# Stop notices in PHPUnit and throw nice exceptions
set_error_handler(function ($level, $message, $file = '', $line = 0, $context = []) {
    if (error_reporting() & $level) {
        throw new ErrorException($message, 0, $level, $file, $line);
    }
});


if (file_exists('.env')) {
    if (preg_match('/\n{2}/', file_get_contents('.env'))) {
        die("There are empty lines right after each other in your .env file, this makes the file unparseable.");
    }
}
