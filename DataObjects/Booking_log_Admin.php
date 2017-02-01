<?php
//require_once PROJECT_ROOT.'DataObjects/Users.php';

class DBO_Booking_log_Admin extends DBO_Booking_log
{
    public $role = 'aa';

    public $listLabels   = array(
                     'id'         => 'width=80',
                    
                    'villa_title'         => '',
                     'user_id' => '',
                     'post_date'        => '',
                    'period' => '',
                        'people' => '',
                     //'start_date'       =>  '',
                     //'end_date'         =>  '',
                     //'book_status'      =>  '',
                     //'price'            =>  '',
                     //'currency'         =>  '',
    );

    public $book_status_arr = array(0 => 'не обработана', 1 => 'обработана', 2 => 'нет цен', 3 => 'объект удален', 4 => 'запрос отправлен', 5 => 'отклонен владельцем', 6 => 'оплачена');

    function tableRow()
    {
        if ($this->book_status && $this->user_id) {
            $this->listLabels['user_id'] = 'bgcolor=97FF7A';
        } else if($this->user_id){
            $this->listLabels['user_id'] = 'bgcolor=FF4B44';
        } else {
            $this->listLabels['user_id'] = '';
        }

        return array(
            //'id'    => '<a href="?op=booking&act=admincard&id='.$this->id.'">Бронь '.$this->id.'</a>',
            'id'    => '<a href="?op=booking_log&act=admincard&id='.$this->id.'">'.$this->id.'</a>',
            //'villa_title' =>  '<a href="/villa/'.$this->villa_id.'.html" target=_blank>'.$this->villa_title.'</a>',

            'villa_title' =>  '<a href="http://villarenters.ru/villa/'.$this->villa_id.'.html" target=_blank>'.$this->villa_id.'</a>',


            'user_id' => '<a href="?op=users&act=card&id='.$this->user_id.'">'.$this->user_id.'</a>',

            'period' => DVS_Page::RusDate($this->start_date).' - '.DVS_Page::RusDate($this->end_date),
            'people' => '<a href="?op=guests&booking_log_id='.$this->id.'">'.$this->people.'</a>',
            //'start_date'       =>  $this->start_date,
             //'end_date'         =>  $this->end_date,
             //'book_status'      =>  $this->book_status_arr[$this->book_status],
             'post_date'        => DVS_Page::RusDate($this->post_date),
             //'price'            =>  $this->price,
             //'currency'         =>  $this->currency,
        );
    }

    function preGenerateList()
    {
        if (isset($this->villa_id) && $this->villa_id == '') {
            unset($this->villa_id);
        }
        if (isset($this->book_status) && $this->book_status == '') {
            unset($this->book_status);
        }
        if (isset($this->book_status) && $this->book_status == 0) {
            $this->whereAdd("user_id > 0");
        }
    }


    //Форма поиска для страницы администрирования
    function getSearchForm()
    {
        require_once('HTML/QuickForm.php');
        $form =& new HTML_QuickForm('search_form', 'get', $this->qs, '', 'class="search"');
        $form->addElement('hidden', 'op', 'booking_log');
        $vi = $form->createElement('text', 'villa_id', '');
        $st = $form->createElement('select', 'book_status', 'Статус :', array('' => 'Все') + $this->book_status_arr);
        $submit = HTML_QuickForm::createElement('submit', 'search', '>>');
        $form->addGroup(array($vi, $st, $submit), '', '');
        return $form->toHtml();
    }
}