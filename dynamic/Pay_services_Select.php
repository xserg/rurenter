<?php
/**
 * Оплата кампании
 * @package web2matrix
 * $Id: Pay_services_Select.php 21 2010-10-28 06:25:35Z xxserg $
 */

require_once COMMON_LIB.'DVS/Dynamic.php';

class Project_Pay_services_Select extends DVS_Dynamic
{
    // Права
    public $perms_arr = array('oc' => 1, 'aa' => 0, 'ar' => 0);

    function __construct()
    {
        $this->advertiser_id = $_SESSION['_authsession']['data']['advertiser_id'];
    }

    function getPageData()
    {

        $this->advertisers_obj = DB_DataObject::factory('advertisers');
        $this->advertisers_obj->getAdvertiser($this->advertiser_id);
        //print_r($pay_services);
        $this->createTemplateObj();
        if ($type = DVS::getVar('type') && $this->advertisers_obj->f_shortname == 'PHL') {
            $adv_properties_obj = DB_DataObject::factory($this->advertisers_obj->f_shortname.'_properties');
            $adv_properties_obj->get('advertiser_id', $this->advertiser_id);
            $this->template_obj->loadTemplateFile('kvit.tpl');
            $this->getPay_serviceProperties();
            //echo '<pre>';
            //print_r($this->db_obj->toArray());
            //print_r($this->advertisers_obj);
            //print_r($adv_properties_obj->toArray());
            //print_r($this->properties_obj->toArray());
            $page_arr = $this->properties_obj->toArray();
            $page_arr += $this->advertisers_obj->toArray();
            $page_arr += $adv_properties_obj->toArray();
            $page_arr['ammount'] = DVS::getVar('ammount');
            $page_arr['purpose'] = 'Пополнение счета';
            $page_arr['kvit_title'] = 'ИЗВЕЩЕНИЕ';
            $this->template_obj->setVariable($page_arr);
            $this->template_obj->parse('KVIT');
            $page_arr['kvit_title'] = 'КВИТАНЦИЯ';
            $this->template_obj->setVariable($page_arr);
            $this->template_obj->parse('KVIT');
            echo $this->template_obj->get();
            exit;
        }


        $this->template_obj->loadTemplateFile('pay_service_form.tpl');

        if ($this->advertisers_obj->face_type_id == 1) {
            $this->template_obj->touchBlock('ADV_NO_PAY');
        } else {
            if (!$_POST['pay_service_id']) {
               $form = $this->getForm();
               //$form_html = $form->tohtml();
               $this->template_obj->setVariable(array('FORM' => $form->tohtml()));

                $this->template_obj->loadTemplateFile('form.tmpl');
                require_once 'HTML/QuickForm/Renderer/ITDynamic.php';
                $this->db_obj->renderer_obj = new HTML_QuickForm_Renderer_ITDynamic($this->template_obj);
                $form->accept($this->db_obj->renderer_obj);
                /*
                if ($form->validate()) {
                    //$form->freeze();
                }
                */
            } else {
                $this->confirmPay();
            }
        }

        $page_arr['CENTER_TITLE']   = 'Пополнение счета';
        $page_arr['CENTER']         = $this->template_obj->get();
        //$page_arr['CENTER']         = $form->tohtml();

        return $page_arr;

    }

    function serviceForm()
    {
        $pay_services = $this->db_obj->selArray();
        $this->template_obj->loadTemplateFile('pay_service_form.tpl');
        foreach ($pay_services as $id => $name) {
            $this->template_obj->setVariable(array('pay_service_id' => $id, 'name' => $name));
            $this->template_obj->parse('SERVICE');
        }
    }

    function confirmPay()
    {
        $pay_service_id = DVS::getVar('pay_service_id', 'int', 'post');
        $ammount = DVS::getVar('ammount', 'int', 'post');
        $this->db_obj->get($pay_service_id);
        $this->template_obj->loadTemplateFile('pay_service_form.tpl');
        $this->template_obj->setVariable(array(
            'pay_id' => $pay_service_id,
            'pay_name' => $this->db_obj->name, 'ammount' => $ammount));
        $this->template_obj->touchBlock('KVIT');

    }

    function getForm()
    {
        
        require_once 'HTML/QuickForm.php';
        $form =& new HTML_QuickForm('form', 'post', $_SERVER['REQUEST_URI']);
        //$form->addElement('header', null, 'Пополнение счета');
        //$pay_services = $this->db_obj->selArray();

        $this->service_type_obj = DB_DataObject::factory('pay_service_type');
        $this->db_obj->joinAdd($this->service_type_obj);
        $this->db_obj->selectAs();
        $this->db_obj->selectAs($this->service_type_obj, 'type_%s');

        $this->db_obj->orderBy('name');
        $this->db_obj->find();
        $i = 0;
        //foreach ($pay_services as $id => $name) {
        while ($this->db_obj->fetch()) {
            if ($this->db_obj->type_shortname != 'nal' && $this->db_obj->status > 1) {
                $form->addElement(
                    'radio',
                    'pay_service_id',
                    $i ? '' : 'Платежная система:', $this->db_obj->name,
                    $this->db_obj->pay_service_id,
                    array(
                        ($this->db_obj->status == 1 ? 'disabled' : ''),
                        ($this->db_obj->pay_service_id == 1 ? 'checked=checked' : '')
                    )
                    );
                $i++;
            }
            //$form->setChecked()

        }
        $form->addElement('text', 'ammount', 'Сумма, руб.:');
        $form->addElement('submit', '__submit__', 'Сохранить');
        $form->addRule('pay_service_id', 'Выберите способ оплаты!', 'required', null, 'client');
        $form->addRule('ammount', 'Укажите сумму!', 'required', null, 'client');
        $form->setRequiredNote('<span style="font-size:80%; color:#ff0000;">*</span> Поля для обязательного заполнения');
        $form->setJsWarnings('Ошибка!','');
        //$form->freeze();
        return $form;
    }

    function getPay_serviceProperties()
    {
        $service_type_obj = DB_DataObject::factory('pay_service_type');
        $service_type_obj->get($this->db_obj->service_type_id);
        $this->properties_obj = DB_DataObject::factory($service_type_obj->shortname.'_properties');
        $this->properties_obj->get('pay_service_id', $this->db_obj->pay_service_id);
    }
}

?>