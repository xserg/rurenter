<?php
/**
 * @package villarenters.ru
 * @description �������� ���� ������� ����
 * $Id: Lang_ru.php 532 2015-03-27 07:54:54Z xxserg $
 */

$lang = array();

/**
 * ����� ���������
 */
$lang['constant']['dvs_error'] = '������!';
$lang['constant']['dvs_error_404'] = '��� ������ ����� �� �������...';
$lang['constant']['dvs_error_login'] = '������������ ����� ��� ������';
$lang['constant']['dvs_error_not_exist'] = '������ �� ����������';
$lang['constant']['dvs_error_db_not_exist'] = '�� �� ����������';
$lang['constant']['dvs_error_dynamic_not_exist'] =  '������! �� ���������� ������ Dynamic!';
$lang['constant']['dvs_error_layout_not_exist'] = '������! �� ���������� ������ Layout!';
$lang['constant']['dvs_error_service'] = '������ �������� ����������. �������� ���� ���������';
$lang['constant']['dvs_error_forbidden1'] = '�������� ���������! (#1)';
$lang['constant']['dvs_error_forbidden2'] = '�������� ���������! (#2)';
$lang['constant']['dvs_error_forbidden3'] = '�������� ���������! (#3)';
$lang['constant']['dvs_error_forbidden4'] = '�������� ���������! (#4)';
$lang['constant']['dvs_error_forbidden5'] = '�������� ���������! (#5)';
$lang['constant']['dvs_error_method'] = '������ �� ����������!';
$lang['constant']['dvs_error_href'] = '������������ ������!';
$lang['constant']['dvs_error_help'] = '������ �����������!';
$lang['constant']['dvs_error_exist'] = '��������! ������� ��� ����������� ������ � ���� ��� ����!';
$lang['constant']['dvs_add_row'] = '������ ���������!';
$lang['constant']['dvs_delete_row'] = '������ �������!';
$lang['constant']['dvs_update_row'] = '������ ��������!';
$lang['constant']['dvs_send_letter'] = '������ ����������!';
$lang['constant']['dvs_new'] = '��������';
$lang['constant']['dvs_edit'] = '�������������';
$lang['constant']['dvs_delete'] = '�������';
$lang['constant']['dvs_save'] = '���������';
$lang['constant']['dvs_next'] = '������';
$lang['constant']['dvs_prev'] = '�����';
$lang['constant']['dvs_required'] = '��������� ����';
$lang['constant']['dvs_error_status'] = '������������� ������';
$lang['constant']['dvs_error_pay'] = '������������� ����� �� �����!';
$lang['constant']['dvs_no_records'] = '������� ���';
$lang['constant']['dvs_cnt_records'] = '����� �������:';
$lang['constant']['dvs_confirm'] = '�� �������, ��� ������ ������� ��� ������?';
$lang['constant']['dvs_reg_email'] = "��� ��� ��������� e-mail ��� ������������� �����������!";
$lang['constant']['dvs_error_activate'] = "������������ ��� ���������!";
$lang['constant']['dvs_error_reactivate'] = "���� ����������� ��� ���� ������������!";
$lang['constant']['dvs_activate'] = "���� ����������� ������������!!";
$lang['constant']['dvs_moder'] = '������ ���������, ������� ��������� ���������������';
$lang['constant']['dvs_pay_success'] = '������ ��������!';
$lang['constant']['dvs_error_pay'] = '������ �������';
$lang['constant']['dvs_exit'] = '�����';
$lang['constant']['dvs_login'] = '����';
$lang['constant']['dvs_login_alt'] = '���� � ������ ���� ������� rurenter.ru';
$lang['constant']['dvs_contact_us'] = '�������� �����';
$lang['constant']['CHARSET'] = 'windows-1251';
$lang['constant']['dvs_total'] = '����� �������';
$lang['constant']['dvs_search'] = '�����';
$lang['constant']['error_contacts'] = '��������� ���������!';


/**
 * �������������
 */
$lang['layout']['admin']['page_title'] = '�������������';
$lang['layout']['admin']['login_title'] = '�������������';
$lang['layout']['admin']['menu_text']['?op=users'] = '������������';
//$lang['layout']['admin']['menu_text']['?op=users&role_id=1'] = '��������������';
//$lang['layout']['admin']['menu_text']['?op=users&role_id=2'] = '���������';
//$lang['layout']['admin']['menu_text']['?op=users&role_id=3'] = '���������';
//$lang['layout']['admin']['menu_text']['?op=users&role_id=4'] = '�������';

//$lang['layout']['admin']['menu_text']['zag5'] = '���������';
$lang['layout']['admin']['menu_text']['?op=countries'] = '������';
$lang['layout']['admin']['menu_text']['?op=option_groups'] = '������ �����';
//$lang['layout']['admin']['menu_text']['?op=options'] = '�����';
$lang['layout']['admin']['menu_text']['?op=query'] = '�������';
$lang['layout']['admin']['menu_text']['?op=booking&book_status=2'] = '������ �� ������������';
$lang['layout']['admin']['menu_text']['?op=booking_log'] = '������ villarenters.ru';
$lang['layout']['admin']['menu_text']['?op=pages'] = '��������';
$lang['layout']['admin']['menu_text']['?op=villa'] = '�����';
$lang['layout']['admin']['menu_text']['?op=villa&sale=1'] = '�������';
$lang['layout']['admin']['menu_text']['?op=payments_in'] = '�������';
$lang['layout']['admin']['menu_text']['?op=villa&act=import'] = '������';


/**
 * ��������
 */

$lang['layout']['redactor']['page_title'] = '�������������';
$lang['layout']['redactor']['login_title'] = '���������';
$lang['layout']['redactor']['menu_text']['zag2'] = '���������';


/**
 * ��������
 */
$lang['layout']['client']['page_title'] = '�������������';
$lang['layout']['client']['login_title'] = '��������';
//$lang['layout']['client']['menu_text']['?op=booking'] = '������ �� ������������';
//$lang['layout']['client']['menu_text']['?op=villa'] = '�������';
$lang['layout']['client']['menu_text']['?op=villa'] = '������';
$lang['layout']['client']['menu_text']['?op=villa&sale=1'] = '�������';

$lang['layout']['client']['menu_text']['?op=booking'] = '������';
//$lang['layout']['client']['menu_text']['?op=query'] = '���������';
$lang['layout']['client']['menu_text']['/lorem/'] = '�����';

$lang['layout']['client']['menu_text']['?op=users&act=edit'] = '���������';
//$lang['layout']['client']['menu_text']['?op=users&act=show'] = '���������';
$lang['layout']['client']['menu_text']['?op=transactions'] = '������ ����';
//$lang['layout']['client']['menu_text']['?op=pay_services&act=select'] = '��������� ����';

/**
 * ���������
 */
$lang['layout']['loginuser']['page_title'] = '�������������';
$lang['layout']['loginuser']['login_title'] = '���������';
//$lang['layout']['loginuser']['menu_text']['?op=booking'] = '������ �� ������������';
//$lang['layout']['loginuser']['menu_text']['?op=villa'] = '�������';
$lang['layout']['loginuser']['menu_text']['/'] = '�������';
$lang['layout']['loginuser']['menu_text']['/office/?op=booking'] = '������';
//$lang['layout']['loginuser']['menu_text']['/office/?op=query'] = '���������';
$lang['layout']['loginuser']['menu_text']['/lorem/'] = '�����';

$lang['layout']['loginuser']['menu_text']['/office/?op=users&act=edit'] = '���������';
//$lang['layout']['loginuser']['menu_text']['?op=users&act=show'] = '���������';
$lang['layout']['loginuser']['menu_text']['/office/?op=transactions'] = '������ ����';
//$lang['layout']['loginuser']['menu_text']['?op=pay_services&act=select'] = '��������� ����';

/**
 * Frontend
 */
$lang['layout']['user']['project_title'] = 'ruRenter.ru';


$lang['layout']['user']['keywords'] = '������ ������� ��� �����������';
$lang['layout']['user']['description'] = '����������� �� ������ ������� ��� �����������. ������ ������ ������� � ������, ���������� � �� ������.';
//$lang['layout']['user']['menu_text']['zag1'] = 'Villarenters';
//$lang['layout']['user']['menu_text']['/'] = '�������';
$lang['layout']['user']['menu_text']['/'] = '������';
$lang['layout']['user']['menu_text']['/sale/'] = '�������';
$lang['layout']['user']['menu_text']['/pages/about/'] = '� �������';
$lang['layout']['user']['menu_text']['/pages/faq/'] = '������-�����';
$lang['layout']['user']['menu_text']['/lorem/'] = '�����';
//$lang['layout']['user']['menu_text']['?op=pages&act=avia'] = '����������';
$lang['layout']['user']['menu_text']['/reg/'] = '�����������';
$lang['layout']['user']['menu_bottom_text']['/'] = '�������';
$lang['layout']['user']['menu_bottom_text']['/pages/about/'] = '� �������';
$lang['layout']['user']['menu_bottom_text']['/pages/faq/'] = '������-�����';
$lang['layout']['user']['menu_bottom_text']['/pages/contacts/'] = '��������';
$lang['layout']['user']['menu_bottom_text']['/contact/'] = '������ ������';
$lang['layout']['user']['menu_bottom_text']['/?versm=mob'] = '��������� ������';
/*
			<li><a href="/">�������</a></li>
			<li><a href="/pages/about/">� �������</a></li>
			<li><a href="/pages/faq/">������-�����</a></li>
			<li><a href="/">����� �����</a></li>
			<li><a href="/pages/contacts/">��������</a></li>
*/

/**
 * DBO
 */

/**
 * Bankinfo
 */
$lang['dbo']['bankinfo']['center_title'] = '���������� ���������';
$lang['dbo']['bankinfo']['head_form'] = '�������� ���������';
$lang['dbo']['bankinfo']['fb_fieldLabels']['user_id'] = '������������';
$lang['dbo']['bankinfo']['fb_fieldLabels']['account_name']  =  '��� ��������� �����';
$lang['dbo']['bankinfo']['fb_fieldLabels']['bank_name']  =  '�������� �����';
$lang['dbo']['bankinfo']['fb_fieldLabels']['bank_address']  =  '����� �����';
$lang['dbo']['bankinfo']['fb_fieldLabels']['bank_city']  =  '����� �����';
$lang['dbo']['bankinfo']['fb_fieldLabels']['bank_country']  =  '������ �����';
$lang['dbo']['bankinfo']['fb_fieldLabels']['bank_phone']  =  '������� �����';
$lang['dbo']['bankinfo']['fb_fieldLabels']['postcode']  =  '������ �����';
$lang['dbo']['bankinfo']['fb_fieldLabels']['account_number']  =  '����� �����';
$lang['dbo']['bankinfo']['fb_fieldLabels']['swift']  =  'SWIFT';
$lang['dbo']['bankinfo']['fb_fieldLabels']['bic']  =  'BIC';
$lang['dbo']['bankinfo']['fb_fieldLabels']['iban']  =  'IBAN';


/**
 * Booking
 */
$lang['dbo']['booking']['center_title'] = '������ �� ������������';
$lang['dbo']['booking']['head_form'] = '�������� �����';
$lang['dbo']['booking']['fb_fieldLabels']['villa_title'] = '������';
$lang['dbo']['booking']['fb_fieldLabels']['start_date']  =  '������ �������';
$lang['dbo']['booking']['fb_fieldLabels']['end_date']  =  '�����';
$lang['dbo']['booking']['fb_fieldLabels']['book_status']  =  '������';
$lang['dbo']['booking']['fb_fieldLabels']['price']  =  '����';
$lang['dbo']['booking']['fb_fieldLabels']['currency']  =  '������';
$lang['dbo']['booking']['fb_fieldLabels']['post_date']  =  '���� ����������';
$lang['dbo']['booking']['book_status_arr'][1] = '����� ���������';
$lang['dbo']['booking']['book_status_arr'][2] = '���������������';
$lang['dbo']['booking']['book_status_arr'][3] = '��������� ����';
$lang['dbo']['booking']['book_status_arr'][4] = '��������';
$lang['dbo']['booking']['book_status_arr'][5] = '��������';
$lang['dbo']['booking']['book_status_arr'][6] = '���� � ������';

/**
 * Countries
 */
$lang['dbo']['countries']['center_title'] = '�������';
$lang['dbo']['countries']['head_form'] = '�������� ������';
$lang['dbo']['countries']['fb_fieldLabels']['name'] = '��������';
$lang['dbo']['countries']['fb_fieldLabels']['rus_name'] = '���.';

/**
 * Images
 */
$lang['dbo']['images']['center_title'] = '����';
$lang['dbo']['images']['head_form'] = '����';
$lang['dbo']['images']['fb_fieldLabels']['image']            = '����';
$lang['dbo']['images']['fb_fieldLabels']['main']             = '�������';
$lang['dbo']['images']['fb_fieldLabels']['alternative_text'] = '�������';




/**
 * option_groups
 */
$lang['dbo']['option_groups']['center_title'] = '������ �����';
$lang['dbo']['option_groups']['head_form'] = '�������� ������';
$lang['dbo']['option_groups']['fb_fieldLabels']['name'] = '��������';
$lang['dbo']['option_groups']['fb_fieldLabels']['rus_name'] = '���.';


/**
 * options
 */
$lang['dbo']['options']['center_title'] = '�����';
$lang['dbo']['options']['head_form'] = '�������� �����';
$lang['dbo']['options']['fb_fieldLabels']['group_id'] = '������';
$lang['dbo']['options']['fb_fieldLabels']['name'] = '��������';
$lang['dbo']['options']['fb_fieldLabels']['rus_name'] = '���.';

/**
 * villa
 */
$lang['dbo']['villa']['center_title'] = '�������';
$lang['dbo']['villa']['head_form'] = '�������� ������';
$lang['dbo']['villa']['fb_fieldLabels']['id'] = '#';
$lang['dbo']['villa']['fb_fieldLabels']['main_image'] = '&nbsp;';
$lang['dbo']['villa']['fb_fieldLabels']['title'] = '��������';
$lang['dbo']['villa']['fb_fieldLabels']['user_id'] = '��������';
$lang['dbo']['villa']['fb_fieldLabels']['summary'] = '��������';
$lang['dbo']['villa']['fb_fieldLabels']['address'] = '�����';
$lang['dbo']['villa']['fb_fieldLabels']['rooms'] = '������';
$lang['dbo']['villa']['fb_fieldLabels']['sleeps'] = '���-�� �������� ����';
$lang['dbo']['villa']['fb_fieldLabels']['proptype'] = '��� �������';
$lang['dbo']['villa']['fb_fieldLabels']['price'] = '���� �� � ��<br>(� ������ ��������<br> rurenter.ru <b>10%</b>)';
$lang['dbo']['villa']['fb_fieldLabels']['breakage'] = '����� �� �����';
$lang['dbo']['villa']['fb_fieldLabels']['title_name'] = '���.';
$lang['dbo']['villa']['fb_fieldLabels']['city'] = '�����';

$lang['dbo']['villa']['words']['rent'] = '������';
$lang['dbo']['villa']['words']['villa_rentals'] = '������ ����';
$lang['dbo']['villa']['words']['keyword'] = '�������� �����';
$lang['dbo']['villa']['words']['price_per_week'] = '���� (� ������)';
$lang['dbo']['villa']['words']['min'] = '���.';
$lang['dbo']['villa']['words']['max'] = '����.';
$lang['dbo']['villa']['words']['sleeps'] = '����';
$lang['dbo']['villa']['words']['type'] = '���';
$lang['dbo']['villa']['words']['price_rule'] = '���� ���. ������ ���� ������ ����.';
$lang['dbo']['villa']['words']['any'] = '���';

$lang['dbo']['villa']['form_help'][0] = '������� �������� ���������� ����������� ������';
$lang['dbo']['villa']['form_help'][1] = '������� ����� ��� �������� ���������� ����������� ������, ������� "������"';
$lang['dbo']['villa']['form_help'][2] = '������';
$lang['dbo']['villa']['form_help'][3] = '������� ��������';
$lang['dbo']['villa']['form_help'][4] = '���� ��� ��� ������, ������� \"�����\", ���� ���, �������� ����� � ��� ��� ������� \"������\".<br>���� �� ������ ������� ������ ��������� � ������� �������, ������� \"������� ��������\"';
$lang['dbo']['villa']['form_help'][5] = '��� �������� ������� ����� ������� \"������� ��������\"';


$lang['dbo']['villa']['form_headers'][1] = '������������';
$lang['dbo']['villa']['form_headers'][2] = '��������';
$lang['dbo']['villa']['form_headers'][3] = '������';
$lang['dbo']['villa']['form_headers'][4] = '����';
$lang['dbo']['villa']['form_headers'][5] = '����';
$lang['dbo']['villa']['form_headers'][6] = '�����';

$lang['dbo']['villa']['pay_period_arr'][1] = '� ������';
$lang['dbo']['villa']['pay_period_arr'][2] = '� �����';


/**
 * query
 */
$lang['dbo']['query']['center_title'] = '������';
$lang['dbo']['query']['head_form'] = '������';
$lang['dbo']['query']['header1'] = '���������� ����������';

$lang['dbo']['query']['fb_fieldLabels']['first_name'] = '���';
$lang['dbo']['query']['fb_fieldLabels']['last_name'] = '�������';
$lang['dbo']['query']['fb_fieldLabels']['email'] = 'E-mail';
$lang['dbo']['query']['fb_fieldLabels']['phone'] = '������� (+7(495)711-2233)';
$lang['dbo']['query']['fb_fieldLabels']['body'] = '�����';
$lang['dbo']['query']['fb_fieldLabels']['post_date'] = '���� ����������';
$lang['dbo']['query']['fb_fieldLabels']['status_id'] = '������';
$lang['dbo']['query']['fb_fieldLabels']['booking_id'] = '�����';


$lang['dbo']['query']['fb_fieldLabels']['title_name'] = '���.';

/**
 * Users
 */
$lang['dbo']['users']['center_title'] = '������������';

$lang['dbo']['users']['words']['form_title'] = '���������';
$lang['dbo']['users']['words']['form_title2'] = '���������� ����������';
$lang['dbo']['users']['words']['agree'] = ' � �������� � �������� � ��������� �������������� ������� ';
$lang['dbo']['users']['words']['agreement'] = '������� �������������� �������';
$lang['dbo']['users']['words']['smsinfo'] = '��������� sms ����������';
$lang['dbo']['users']['words']['smsinfo_activate'] = '������ sms-��� ���������';
$lang['dbo']['users']['words']['send_new'] = '�������� sms �����������';


$lang['dbo']['users']['words']['error_format'] = '������������� ������ ����';
$lang['dbo']['users']['words']['disagree'] = '�� �� �������� � ��������� �������!';
$lang['dbo']['users']['words']['bank_details'] = '���������� ���������';

$lang['dbo']['users']['head_form'] = '�������� ������������';
$lang['dbo']['users']['fb_fieldLabels']['email'] = 'E-mail';
$lang['dbo']['users']['fb_fieldLabels']['password'] = '������';
$lang['dbo']['users']['fb_fieldLabels']['confirm_password'] = '��������� ������';

$lang['dbo']['users']['fb_fieldLabels']['last_ip'] = 'Ip';
$lang['dbo']['users']['fb_fieldLabels']['name'] = '���';
$lang['dbo']['users']['fb_fieldLabels']['lastname'] = '�������';
$lang['dbo']['users']['fb_fieldLabels']['address'] = '�����';
$lang['dbo']['users']['fb_fieldLabels']['company'] = '��������';

$lang['dbo']['users']['fb_fieldLabels']['home_phone'] = '�������� �������';
$lang['dbo']['users']['fb_fieldLabels']['work_phone'] = '������� �������';
$lang['dbo']['users']['fb_fieldLabels']['mobile_phone'] = '��������� ������� (������: 74957112233)';
$lang['dbo']['users']['fb_fieldLabels']['reg_date'] = '���� �����������';
$lang['dbo']['users']['fb_fieldLabels']['last_date'] = '��������� ����';
$lang['dbo']['users']['fb_fieldLabels']['role_id'] = '����';
$lang['dbo']['users']['fb_fieldLabels']['status_id'] = '������';
$lang['dbo']['users']['fb_fieldLabels']['nick'] = '��������� ��� ������';

$lang['dbo']['users']['fb_fieldLabels']['note'] = '���. ����������';
$lang['dbo']['users']['rules']['email'][] = '������������ ������ ����';
$lang['dbo']['users']['rules']['email'][] = '����� E-mail ��� ���������������!';
$lang['dbo']['users']['rules']['nick'][] = '����� ��������� ��� ���������������!';
$lang['dbo']['users']['rules']['phone'][] = '������� ������� � ������� 74951112233" !';
$lang['dbo']['users']['rules']['mobile_phone'][] = '����� ������� ��� ���������������!';

$lang['dbo']['users']['words']['reg_title'] = '����������� ������ ������������';
$lang['dbo']['users']['words']['reg_help'] = '������� ����������� ����� ����������� �����.<br>��� ����� ������ ��� ��� ������������� �����������';
$lang['dbo']['users']['fb_fieldLabels']['role_id_title'] = '�� ������';
$lang['dbo']['users']['fb_fieldLabels']['role_id_label1'] = '����� / ������� (��� ����������)';
$lang['dbo']['users']['fb_fieldLabels']['role_id_label2'] = '����� (��� �����������)';
$lang['dbo']['users']['rules']['password'][] = '����� ������ ������ ���� �� ����� 5 ��������!';
$lang['dbo']['users']['rules']['password'][] = '������ �� ���������!';


/**
 * smsinfo
 */
$lang['dbo']['smsinfo']['center_title'] = '��������� �������';
$lang['dbo']['smsinfo']['head_form'] = '�������';
$lang['dbo']['smsinfo']['fb_fieldLabels']['phone'] = '�������';
$lang['dbo']['smsinfo']['fb_fieldLabels']['code'] = '��� �������������';
$lang['dbo']['smsinfo']['fb_fieldLabels']['send_new'] = '�������� �����������';

/**
 * Dynamic Countries_Index
 */
//$lang['dynamic']['countries_index']['center_title'] = '�����';


?>
