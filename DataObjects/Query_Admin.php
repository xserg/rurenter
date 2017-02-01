<?php
require_once PROJECT_ROOT.'DataObjects/Query.php';

class DBO_Query_Admin extends DBO_Query
{
    public $role = 'aa';

    /**
     * Cтолбцы таблицы
     */
    public $listLabels   = array(
                    'id' => '',
                    // 'first_name'     =>  'class="td-name"',
                    // 'email'    =>  '',
                    // 'phone'    =>  '',
                    'body' => '',
                    'post_date'  => '',
                    'booking_id' => '',
                    'status_id' => '',
    );

    public function getForm()
    {
        $villa_id = DVS::getVar('villa_id');

        $villa_obj = DB_DataObject::factory('villa');
        $villa_obj->get($villa_id);
        if ($villa_obj->N) {
            $this->center_title .= ' по '.$villa_obj->title_rus.' ('.$villa_id.')';
        }
        $form =& new HTML_QuickForm('', '', $_SERVER['REQUEST_URI'], '', 'class="reg"');
        //$form->addElement('hidden', 'villa_id', $villa_id);
        $form->addElement('header', null, 'Контактная информация');
        $form->addElement('text', 'first_name', $this->fb_fieldLabels['first_name'].': ');
        $form->addElement('text', 'last_name', $this->fb_fieldLabels['last_name'].': ');
        $form->addElement('text', 'email', $this->fb_fieldLabels['email'].': ');
        $form->addElement('text', 'phone', $this->fb_fieldLabels['phone'].': ');

        $form->addElement('text', 'user_id', 'Автор: ');
        $form->addElement('text', 'user_id_to', 'Кому: ');
        $form->addElement('text', 'villa_id', 'Вилла: ');
        $form->addElement('text', 'booking_id', 'Букинг: ');
        $form->addElement('text', 'status_id', 'Статус: ');


        $form->addElement('static', null, '</div><div class="clear"></div></div>');
        $form->addElement('header', null, 'Вопрос');
        $form->addElement('textarea', 'body', '');

        $form->addElement('submit', '__submit__', DVS_SAVE);
        $form->addElement('static', null, '</div></div>');

/*
        $form->addRule('last_name', DVS_REQUIRED.' "'.$this->fb_fieldLabels['last_name'].'"!', 'required', null, 'client');
        $form->addRule('first_name', DVS_REQUIRED.' "'.$this->fb_fieldLabels['first_name'].'"!', 'required', null, 'client');
        $form->addRule('phone', DVS_REQUIRED.' "'.$this->fb_fieldLabels['phone'].'"!', 'required', null, 'client');
        $form->addRule('email', DVS_REQUIRED.' "'.$this->fb_fieldLabels['email'].'"!', 'required', null, 'client');
*/
        $form->setDefaults( array('body' => $this->center_title));
        return $form;
    }

    function tableRow()
    {
        return array(
             'id' =>  '<a href="?op=query&act=show&id='.$this->id.'">'.$this->id.'</a>',
             'first_name' => '<a href="?op=query&act=show&id='.$this->id.'">'.$this->first_name.'</a>',
             'email'    =>  $this->email,
             'phone'    =>  $this->phone,
             'body' => $this->body,
             'post_date'  => DVS_Dynamic::RusDate($this->post_date),
             //'status_id' => $this->status_arr[$this->status_id],
             'booking_id' => $this->booking_id ? '<a href="?op=booking&act=admincard&id='.$this->booking_id.'">'.$this->booking_id.'</a>' : '',
             'status_id' => $this->activeLink(),
            //'moderate' => $this->status_id ? $this->status_arr[$this->status_id] : '<a href="/office/?op=query&act=activate&id='.$this->id.'">Активировать</a>' ,
        );
    }

    function activeLink()
    {
        if ($this->booking_id) {
            $ret = $this->status_id == 0 ?  '<a href="?op=query&act=activate&id='.$this->id.'">Новый (Активировать)</a>' : $this->status_arr[$this->status_id];
        } else {
            $ret = 'Вопрос';
        }
        return $ret;
    }
}