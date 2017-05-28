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

    function parseAction()
    {
        $this->view->title = "Parsing...";
        $site = "https://www.avito.ru/rossiya/muzykalnye_instrumenty?p=1&view=list&q=пианино";

        if ($this->_request->isPost()) {
            $query = $this->_request->getPost('query');
            $sl = explode(',',$query);

            foreach($sl as $searchline)
            {

            }

            // Create DOM from URL or file
            $html = file_get_html($site);

            // Find all images
            foreach($html->find('div.item_list') as $element)
            {
                echo $element->find('.title h3 a', 0)->title . "->";
                echo $element->find('.title .date', 0)->plaintext . "->";
                echo "<br>";
            }

        }
    }
}
