<?php
/**
 * Created by PhpStorm.
 * User: Александр Пенко
 * Date: 26.05.2017
 * Time: 20:24
 */

class ParseController extends Zend_Controller_Action
{
    function init()
    {
        include 'simple_html_dom.php';
        $this->view->baseUrl = $this->_request->getBaseUrl();
        Zend_Loader::loadClass('Advert');
    }

    function indexAction()
    {
        $this->view->title = "Parsing";
        if ($this->_request->isPost())
        {
            $this->view->query = $this->_request->getPost('query');
        }
        //$site = "https://www.avito.ru/rossiya/muzykalnye_instrumenty?p=1&view=list&q=пианино";
    }

    function startParsingAction()
    {
        $this->_helper->viewRenderer->setNoRender();

        if ($this->_request->isPost())
        {
            $pubnub = Zend_Registry::get('pubnub');

            $pubnub->publish('parsing_status', 'Start parsing');


            $query = $this->_request->getPost('query');
            $sl = explode(',',$query);

            foreach($sl as $searchline)
            {
                $parsedate = date("Y-m-d H:i:s");

                $pubnub->publish('parsing_status', '&nbsp;&nbsp;&nbsp;&nbsp;<strong>' . $searchline . '</strong> is parsing');

                $this->view->currentsl = $searchline;

                $i = 0;
                do
                {
                    $i++;
                    $pubnub->publish('parsing_status', '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Page <strong>' . $i . '</strong> is parsing');
                    $site = "https://www.avito.ru/rossiya/muzykalnye_instrumenty?p=" . $i . "&view=list&q=" . $searchline;
                    $html = file_get_html($site);

                    foreach($html->find('div.item_list') as $element)
                    {
                        $data['name'] = $element->find('.title h3 a', 0)->title;
                        $data['date'] = $element->find('.title .date', 0)->plaintext;
                        $data['link'] = $element->find('.title h3 a', 0)->href;
                        $data['price'] = $element->find('.price p', 0)->plaintext;
                        $data['city'] = $element->find('.data .data-chunk', 0)->plaintext;
                        $data['searchline'] = $searchline;
                        $data['parsedate'] = $parsedate;

                        $advert = new Advert();
                        $advert->insert($data);
                        //$pubnub->publish('parsing_status', json_encode($data));
                    }

                    sleep(3);
                }
                while($html->find(".pagination-page") && $i != $html->find(".pagination-page", -1)->plaintext);


            }

            $pubnub->publish('parsing_status', 'End parsing');
            echo "Done";
        }
        else
            echo "There is a problem";



    }

    function getStatusAction()
    {
        $this->_helper->viewRenderer->setNoRender();

        $status = [];
        $status['currentsl'] = 'asd';

        echo json_encode($status);
    }
}
