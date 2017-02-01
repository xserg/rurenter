<?php
/**
 * 
 * Активация нового виллы
 * $Id: $
*/

require_once COMMON_LIB.'DVS/Dynamic.php';
require (PROJECT_ROOT.'/layout/getDataHA.php');
require (PROJECT_ROOT.'/layout/saveData.php');

//define('DEBUG', 1);

class Project_Villa_Import extends DVS_Dynamic
{
    /**
     * Права
     */
    //var $perms_arr = array('iu' => 1);

    function getPageData()
    {
        $villa_id = DVS::getVar('villa_id', 'word', 'post');
        
        if (!$villa_id) {
            $form = $this->getImportForm();
            $page_arr['CENTER_TITLE']   = 'Импорт объектов';
            $page_arr['CENTER']         = $form;
        /*    
            $this->createTemplateObj();
            $this->template_obj->loadTemplateFile('form.tmpl');
            require_once 'HTML/QuickForm/Renderer/ITDynamic.php';
            $this->db_obj->renderer_obj = new HTML_QuickForm_Renderer_ITDynamic($this->template_obj);
            $form->accept($this->db_obj->renderer_obj);
          */ 
        } else {
        
            $match = $this->getVilla($villa_id);
            
            //echo '<pre>';
            //print_r($match);


            $save_obj = new saveData;
            //$db_obj = DVS_Dynamic::createDbObj('villa');
            //DB_DataObject::DebugLevel(1);
            $res = $save_obj->insertVilla($match, $this->db_obj, 1);

            if ($res) {
                $page_arr['CENTER']         = 'FINISH';
            }
            

                    if(!$match) {
                        //$this->show404();
                        $this->nocache = true;
                        return;
                    }


        }

        return $page_arr;

    }

    function getImportForm()
    {
        require_once 'HTML/QuickForm.php';
        $form =& new HTML_QuickForm('import', '', $_SERVER['REQUEST_URI']);
        //$form->addElement('hidden', 'date1');
        $form->addElement('select', 'site', 'site:', array(
            'homeaway' => 'homeaway', 
            //'clickstay' => 'clickstay'
        ));
        $form->addElement('text', 'villa_id', 'id:');
        $form->addElement('submit', '__submit__', DVS_SAVE);
        //return $form;
        return $form->tohtml();
    }


    /**
     Получение виллы с homeaway.com
    */
    function getVilla($villa_id)
    {
        $data_obj = new GetDataHA;
        if (preg_match("/^[0-9]+/", $villa_id)) {
            $villa_id = 'p'.$villa_id;
        }
        $data_obj->data_url['Search']['url'] = $villa_id;
        //$body = file_get_contents('2027063.htm');
        //$body = file_get_contents('2292962.htm');
        $body = $data_obj->requestData('Search');
        //echo $body;
        if (strlen($body) > 1024) {
            if ($match = $data_obj->parseVilla2($body)) {
                return $match;
            }
        }
        //echo  $data_obj->error;
        return false;
    }
}

?>