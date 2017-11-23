<?php
/**
 * Created by PhpStorm.
 * User: krzysztofc
 * Date: 22.11.17
 * Time: 13:37
 */


define('APPLICATION_PATH',  realpath(__DIR__ . '/../app/'));

require_once (APPLICATION_PATH . '/class/View.class.php');

$i = filter_input(INPUT_GET, 'i', FILTER_VALIDATE_INT);
$n = filter_input(INPUT_GET, 'n', FILTER_DEFAULT);
echo $n;
if ($i && $n) {
    $view = new View(APPLICATION_PATH . '/views/');
    $view->set('I', $i);
    $view->set('N', $n);
    header('Content-Type: text/plain; charset=UTF-8');
    echo $view->render('ks');
}
