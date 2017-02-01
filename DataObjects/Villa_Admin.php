<?php
require_once PROJECT_ROOT.'DataObjects/Villa_Form2.php';

class DBO_Villa_Admin extends DBO_Villa_Form
{

    public $default_sort = 'add_date DESC';

    public $qs_arr = array('status_id', '_p');
    
    public $listLabels   = array(
            'id' => '',
            'add_date' => 'align=center',
            'main_image' => '',
            'user_id' => '',
            'title' =>  '',
    'countries_name' => '',        
    'status_id' =>'',
            //'summary' =>  '',
            //'prices' => '',
            //'book' => '',
    );

    function tableRow()
    {

        return array(
            'id' =>  '<a href="/villa/'.$this->id.'.html" target=_blank>'.$this->id.'</a>',
            'add_date' => $this->add_date,
            'main_image' => '<a href="/villa/'.$this->id.'.html" target=_blank><img src='.self::imageURL().$this->main_image.'></a>',
            'user_id' => '<a href="?op=users&act=card&id='.$this->user_id.'">'.$this->user_id.'</a>',
            'title'      => '<a href="/villa/'.$this->id.'.html" target=_blank>'.$this->title.'</a><br>'.substr($this->summary, 0, 64).'...'
        .(preg_match("/homeaway.com/", $this->src_site) ? $this->src_site : ''),
            'countries_name' => $this->countries_name,
            'status_id' => $this->activeLink(),
        );
    }

    function activeLink()
    {
        return $this->status_id != 2 ?  '<a href="?op=villa&act=activate&id='.$this->id.($this->sale ? '&sale=1' : '').'">'.$this->status_arr[$this->status_id].' (Активировать)</a>' : $this->status_arr[$this->status_id];
    }

    //Форма поиска для страницы администрирования

    function getSearchForm()
    {
        require_once('HTML/QuickForm.php');
        $form =& new HTML_QuickForm('search_form', 'get', $this->qs, '', 'class="search"');
        $form->addElement('hidden', 'op', $this->__table);
        $form->addElement('hidden', 'sale', $this->sale);
        $text = HTML_QuickForm::createElement('text', 'st', 'Искать: ', array('size' => '30'));
        $submit = HTML_QuickForm::createElement('submit', 'search', '>>');
        $status = HTML_QuickForm::createElement('select', 'status_id', '', array('' => '') + $this->status_arr);
        $src = HTML_QuickForm::createElement('select', 'src_site', '', array('' => '') + array('rurenter.ru' => 'rurenter.ru', 'villarenters.com' => 'villarenters.com', 'homeaway.com' => 'homeaway.com'));
        $form->addGroup(array($text,  $status, $src, $submit), '', DVS_SEARCH.' &nbsp;', array(' &nbsp; ', '&nbsp;', '&nbsp;'));
        
        return $form->toHtml().'<br><br>';
    }

    
    function preGenerateList()
    {
        parent::preGenerateList();
        $countries_obj = DB_DataObject::factory('countries');
        $this->selectAs();
        $this->joinAdd($countries_obj, 'LEFT');
        $this->selectAs(array('name','id'), 'countries_%s', 'countries');
    }
    
}