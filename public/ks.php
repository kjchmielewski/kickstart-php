<?php
/**
 * Created by PhpStorm.
 * User: krzysztofc
 * Date: 22.11.17
 * Time: 13:37
 */

define('APPLICATION_PATH', realpath(__DIR__ . '/../app/'));

require_once(APPLICATION_PATH . '/class/View.class.php');

$os = filter_input(INPUT_GET, 'os', FILTER_DEFAULT);

$view = new View(APPLICATION_PATH . '/views');

header('Content-Type: text/plain; charset=UTF-8');
$os = strtolower($os);
switch ($os) {
    case 'deb' :
        $ip = filter_input(INPUT_GET, 'ip', FILTER_VALIDATE_IP);
        if ($ip) {
            $view->set('IP', $ip);
            echo $view->render('d89');
        }
        break;
    case 'deb2' :
        $ip = filter_input(INPUT_GET, 'ip', FILTER_VALIDATE_IP);
        $hn = filter_input(INPUT_GET, 'hn', FILTER_DEFAULT);
        if ($ip && $hn) {
            $view->set('IP', $ip);
            $view->set('HN', $hn);
            echo $view->render('d89_2');
        }
        break;
    case 'sl7' :
    case 'centos' :
        $ip = filter_input(INPUT_GET, 'ip', FILTER_VALIDATE_INT);
        $hn = filter_input(INPUT_GET, 'hn', FILTER_DEFAULT);
        if ($ip && $hn) {
            $view->set('IP', $ip);
            $view->set('HN', $hn);
            $ver = filter_input(INPUT_GET, 'ver', FILTER_DEFAULT);
            $view->set('VER', $ver ? $ver : '7.0');
            echo $view->render($os);
        }
        break;
    default:
        echo "[ERROR] undefined os";
        break;
}
