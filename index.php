<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 29.05.18
 * Time: 23:24
 */

require_once "vendor/autoload.php";
//use Strangeqargo\SSHParser\SSHParser;
use SSHParser\SSHParser;
use Strangeqargo\Contrib;

$class = new  SSHParser();


function expand_tilde($path) {
    if (function_exists('posix_getuid') && strpos($path, '~') !== false) {
        $info = posix_getpwuid(posix_getuid());
        $path = str_replace('~', $info['dir'], $path);
    }

    return $path;
}

//взять из домашнего каталога
//$configFile = expand_tilde("~/.ssh/config");

//взять пример из репки
$configFile = "config";

$parser = new \SSHParser\SSHParser(file_get_contents($configFile));
$config = $parser->parse();
echo $parser->writeTo("");
