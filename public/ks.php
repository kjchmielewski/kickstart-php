<?php
/**
 * Created by PhpStorm.
 * User: krzysztofc
 * Date: 22.11.17
 * Time: 13:37
 */


define('APPLICATION_PATH',  realpath(__DIR__ . '/../app/'));

require_once (APPLICATION_PATH . '/class/View.class.php');

$hn = filter_input(INPUT_GET, 'hn', FILTER_VALIDATE_INT);

if ($hn) {
    $view = new View(APPLICATION_PATH . '/views/');
    $view->set('HN', $hn);
    header('Content-Type: text/plain; charset=UTF-8');
    echo $view->render('ks');
}
