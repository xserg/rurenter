<?php
/**
////////////////////////////////////////////////////////////////////////////
aservice
------------------------------------------------------------------------------
Отображение текстовых страниц сайта
------------------------------------------------------------------------------
$Id: Query_Show.php 195 2012-03-06 08:04:28Z xxserg@gmail.com $
////////////////////////////////////////////////////////////////////////////
*/

require_once COMMON_LIB.'DVS/Dynamic.php';

require_once COMMON_LIB.'DVS/ShowCard.php';

class Project_Query_Show extends DVS_Dynamic
{
    // Права
    var $perms_arr = array('oc' => 1, 'ar' => 1);

    function getPageData()
    {

       if (!$this->db_obj->N) {
            $this->show404();
            $this->nocache = true;
        }

        $villa_obj = DB_DataObject::factory('villa');
        $villa_obj->get($this->db_obj->villa_id);

        if (!$this->user_id_to != $_SESSION['_authsession']['data']['id'] && $villa_obj->user_id != $_SESSION['_authsession']['data']['id']) {
                $this->msg = 'error_forbidden3';
                return;
        }
        $users_obj = DB_DataObject::factory('users');
        $users_obj->get($this->db_obj->user_id);


        if ($_POST['__submit__']) {
            //DB_DataObject::DebugLevel(1);
                $this->saveQuery();
                $this->qs = '/office/?op=static&act=add_row';
                $this->msg = DVS_ADD_ROW;
                return;
 
        }
        if (!empty($this->db_obj->title)) {
            $page_arr['CENTER_TITLE'] = $this->db_obj->title;
        }

        $body =        'Имя: '.$this->db_obj->first_name."\n"
        .'Фамилия: '.$this->db_obj->last_name."\n"
        .'Е-mail: '.$this->db_obj->email."\n"
        .'Телефон: '.$this->db_obj->phone."\n\n"
        .'Дата: '.$this->db_obj->post_date."\n"
        .'IP: '.$this->db_obj->ip."\n\n"
        ."\n".$this->db_obj->body;

        //$page_arr['CENTER'] = '<p><br><br>'.nl2br($body).'<br><br>';
        //$page_arr['CENTER'] = $this->render($this->db_obj);
        $this->createTemplateObj();
        $this->template_obj->loadTemplateFile('query_show.tpl');

            $this->template_obj->setVariable(
                    array(
                        'id' => $this->db_obj->id,
                        'villa_id' => $this->db_obj->villa_id,
                        'villa_title' => $villa_obj->title,
                        'post_date' => DVS_Page::RusDate($this->db_obj->post_date),
                        'post_ip' => $this->db_obj->post_ip,
                        'book_status' => $this->db_obj->book_status_arr[$this->db_obj->book_status],
                        //'start_date' => DVS_Page::RusDate($this->db_obj->start_date),
                        //'end_date' => DVS_Page::RusDate($this->db_obj->end_date),
                        'user_id' => $this->db_obj->user_id,
                        'user_name' => $users_obj->name,
                        'body' => nl2br($this->db_obj->body),
            )
            );

        if ($this->db_obj->role == 'aa') {
            $this->template_obj->hideBlock('FEEDBACK');
            $this->template_obj->setVariable(
                    array(
                        'first_name' => $this->db_obj->first_name,
                        'last_name' => $this->db_obj->first_name,
                        'email' => $this->db_obj->email,
                        'phone' => $this->db_obj->phone,


            ));
 
        }


        $page_arr['CENTER_TITLE'] = 'Сообщение';
        $page_arr['CENTER'] = $this->template_obj->get();

        return $page_arr;
    }

    function saveQuery()
    {
        $this->db_obj->addAnswer($this->db_obj->user_id, $_SESSION['_authsession']['data']['id'], $this->db_obj->villa_id, $_POST['body']);
    }

    public function render($db_obj)
    {
        $this->template_obj->loadTemplateFile('card.tmpl',1,1);
        if (isset($db_obj->card_fields)) {
            $fields = $db_obj->card_fields;
        } else {
            $fields = $db_obj->toArray();
        }

        if (is_array($db_obj->fieldsToRender)) {
            unset($fields);
            foreach ($db_obj->fieldsToRender as $field) {
                $fields[$field] = $db_obj->$field;
            }
        }

        foreach ($fields as $key=>$val) {
            if ($db_obj->card_fields[$key] && is_array($db_obj->card_fields[$key])) {
                if (in_array($db_obj->card_fields[$key][0], $this->other_blocks)) {
                    $this->template_obj->setVariable(array($db_obj->card_fields[$key][0] => $db_obj->card_fields[$key][1]));
                } else {
                    $this->template_obj->setVariable(array('KEY' => $db_obj->card_fields[$key][0], 'VAL' => $db_obj->card_fields[$key][1]));
                }
            } else {
                $this->template_obj->setVariable(array('KEY' => ($db_obj->fb_fieldLabels[$key] ? $db_obj->fb_fieldLabels[$key] : $key), 'VAL' => $val));
            }
            $this->template_obj->parse('ROW');
        }
        if ($db_obj->show_edit) {
            $id_name = DVS_Dynamic::getPrimaryKey($db_obj);
            $this->template_obj->setVariable(array('LINK' => $db_obj->qs.'&act=edit&id='.$db_obj->$id_name, 'LINK_NAME' => 'Редактировать'));
        }
        return $this->template_obj->get();
    }

}

?>