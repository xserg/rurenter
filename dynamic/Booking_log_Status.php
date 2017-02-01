<?php
/**
 * 
 * Изменение статуса заявки
 * $Id: $
*/

require_once COMMON_LIB.'DVS/Dynamic.php';


class Project_Booking_log_Status extends DVS_Dynamic
{
    /**
     * Права
     */
    //var $perms_arr = array('iu' => 1);

    function getPageData()
    {
        if (!$this->db_obj->N) {
            $this->show404();
            $this->nocache = true;
        }

        if ($this->db_obj->N) {
            // Сохранение статуса
            if (isset($_POST['book_status'])) {
                //if ($this->db_obj->book_status == 0) {
                    $this->db_obj->book_status = $_POST['book_status'];
                    $this->db_obj->update();
                    
                    if ($this->db_obj->user_id_to) {
                        //$this->userLetter($this->db_obj->user_id_to, $this->db_obj->booking_id);
                    }
                    $this->msg = DVS_UPDATE_ROW;
                    //$this->adminLetter();
                    $this->db_obj->qs = '?op=booking_log';
                    $this->goLocation();
                /*
                } else {
                    $this->msg = DVS_ERROR_REACTIVATE;
                }
                */
            } else {// Выбор статуса
                $page_arr['CENTER_TITLE']         =  'Изменить статус';
                $page_arr['CENTER']         =  $this->statusForm();
                return $page_arr;
            }
        } else {
                $this->msg = DVS_ERROR_ACTIVATE;
        }
    }

    function statusForm()
    {
        require_once 'HTML/QuickForm.php';
        $form =& new HTML_QuickForm('', '', $_SERVER['REQUEST_URI']);
        $form->addElement('select', 'book_status', 'Статус:', $this->db_obj->book_status_arr);
        $form->addElement('submit', '__submit__', DVS_SAVE);
        $form->setRequiredNote('');
       return $form->tohtml();
    }


}

?>