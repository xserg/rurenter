<?php
/**
 * �������� ��������
 * @package web2matrix
 * $Id: Prices_Show.php 21 2010-10-28 06:25:35Z xxserg $
 */

require_once COMMON_LIB.'DVS/Dynamic.php';
require (PROJECT_ROOT.'/layout/activecalendar.php');


class Project_Prices_Show extends DVS_Dynamic
{
    // �����
    public $perms_arr = array('ar' => 1, 'oc' => 1);


    function getPageData()
    {
        $villa_id = DVS::getVar('villa_id');
        $villa_obj = DB_DataObject::factory('villa');
        $villa_obj->get($villa_id);

        if (!$villa_obj->N) {
            $this->show404();
            $this->nocache = true;
        }

        $this->villa_id = $villa_id;
        $this->currency = $villa_obj->currency;

        if ($_POST['__submit__']) {
            $this->savePrice();
        }

        $this->createTemplateObj();

        $this->template_obj->loadTemplateFile('price_show.tpl');
        $calendar = $this->getActiveCalendar();


        if ($calendar) {
            $form = $this->calendarForm($villa_obj->sleeps);
        } else {
            $calendar = "<b>�������� �� ��������� ���� �� ������ ������</b>";
        }

        $this->template_obj->setVariable(array(
                'BOOKING_TITLE' => '���� ��� ��� "'.$villa_obj->title.'"<br><br>',
                'BOOKING_FORM' => $form,
                'CALENDAR' => $calendar,
                'VILLA_NAV' => $nav,
            ));

        $page_arr['BODY_CLASS']   = 'property';
        //$page_arr['CENTER_TITLE']   = '������������ ����� "'.$this->db_obj->title.'"<br><br>';
        $page_arr['CENTER']         = $this->template_obj->get();
        //$page_arr['JSCRIPT']        = $this->page_arr['JSCRIPT'];
        return $page_arr;
    }

    function savePrice()
    {
        $start_date = DVS::getVar('start_date', 'word', 'post');
        $end_date = DVS::getVar('end_date', 'word', 'post');
        $this->db_obj->price = DVS::getVar('price', 'int', 'post');
        $this->db_obj->start_date = $this->db_obj->convertDate($start_date);
        $this->db_obj->end_date = $this->db_obj->convertDate($end_date);
        $this->db_obj->villa_id = $this->villa_id;
        
        if ($this->db_obj->insert()) {
            $this->msg = ADD_ROW;
        }
    }


    function getPrices()
    {
        $prices_obj = DB_DataObject::factory('prices');
        $ret = $prices_obj->getPricesArray($this->villa_id);
        return $ret;
    }

    function getActiveCalendar()
    {
        $this->src_files = array(
            'JS_SRC' => array(  '/js/pickdate_price.js',
                                        ),
            'CSS_SRC'  => array(
                                 '/css/calendar.css'
                               ),
            
        );

        $day_names_ru = array(
            '��','��','��','��','��','��','��',
        );
        $monthNamesArray_ru = array('������','�������','����','������','���','����','����','������','��������','�������','������','�������');
        $cal = new activeCalendar($yearID,$monthID,$dayID);
        $cal->enableDayLinks(false,"myDate"); 

//        $cal->enableDayLinks(''); 


        $cal->setDayNames($day_names_ru);
        $cal->setMonthNames($monthNamesArray_ru); 
        $cal->setFirstWeekDay(1); 
        
        //$date_arr = $this->showBook();
        //print_r($date_arr);
        /*
        if (is_array($date_arr)) {
            foreach ($date_arr as $k => $a) {
                $cal->setEvent($a[0], $a[1], $a[2], '', 'javascript:unavaildate(\''.$a[2].'/'.$a[1].'/'.$a[0].'\',\''.$a[2].'\',\''.$a[1].'\',\''.$a[0].'\')');
            }
        }
        */
        $date_arr = array();

        $date_colors_arr = $this->getPrices();
        $date_arr = $date_colors_arr[0];
        $colors_arr = $date_colors_arr[1];
        /*
        echo '<pre>';
        print_r($colors_arr);
        exit;
        */

        foreach ($colors_arr as $price => $class) {
        $this->template_obj->setVariable(array(
                'CLASS' => 'event '.$class,
                'PRICE' => $price,
            ));
        $this->template_obj->parse('COLORS');
        }
        if (is_array($date_arr)) {
            foreach ($date_arr as $k => $a) {
                $da = explode('-', $k);
                $cal->setEvent($da[0], $da[1], $da[2], 'event'.' '.$colors_arr[$a], 'javascript:unavaildate(\''.$da[2].'.'.$da[1].'.'.$da[0].'\')" title="���� (� ������): '.$a.' ""');
                
                /*
                if ($a[0] == 1) {
                    $cal->setEvent($da[0], $da[1], $da[2], '', 'javascript:unavaildate(\''.$da[2].'/'.$da[1].'/'.$da[0].'\',\''.$da[2].'\',\''.$da[1].'\',\''.$da[0].'\')" title="'.$a[1]);
                } else {
                
                    $cal->setEvent($da[0], $da[1], $da[2], 'price', 'javascript:myDate('.$da[0].','.$da[1].','.$da[2].')" onmouseover=temp_prices('.$a.') onmouseout=temp_prices(0) title="Weekly Price: '.$a.' ""');
               }

               $cal->setEvent($da[0], $da[1], $da[2], 'price', 'javascript:myDate('.$da[0].','.$da[1].','.$da[2].')" title="���� (� ������): '.$a.' ""');
*/
            }
            return $cal->showYear(4, date('m'));
        } else {
            return false;
        }
    }


    function calendarForm()
    {
        require_once 'HTML/QuickForm.php';
        $form =& new HTML_QuickForm('calendar', '', $_SERVER['REQUEST_URI']);
        $form->addElement('hidden', 'date1');
        $form->addElement('hidden', 'date2');
        $form->addElement('text', 'start_date', '������ �������:');
        $form->addElement('text', 'end_date', '�����:');
        $form->addElement('text', 'price', '���� � ������, '.$this->currency.' :');
        $form->addElement('submit', '__submit__', DVS_SAVE);
        $form->addRule('start_date', DVS_REQUIRED.' "���� 1"!', 'required', null, 'client');
        $form->addRule('end_date', DVS_REQUIRED.' "���� 2"!', 'required', null, 'client');
        $form->addRule('price', DVS_REQUIRED.' ����!', 'required', null, 'client');
        $form->setRequiredNote('');
       return $form->tohtml();
    }

}

?>