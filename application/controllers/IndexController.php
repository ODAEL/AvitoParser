<?php
/**
 * Created by PhpStorm.
 * User: Александр Пенко
 * Date: 26.05.2017
 * Time: 20:24
 */

class IndexController extends Zend_Controller_Action
{
    function init()
    {
        include 'simple_html_dom.php';
        $this->view->baseUrl = $this->_request->getBaseUrl();
        Zend_Loader::loadClass('Advert');
    }

    function indexAction()
    {
        $this->view->title = "AvitoParser";
        //$advert = new Advert();
        //$this->view->adverts = $advert->fetchAll();
    }
}
