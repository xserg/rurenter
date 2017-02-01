<?php
/**
 * @package rurenter.ru
 * @description Языковый файл English
 * $Id: Lang_ru.php 122 2011-03-15 08:53:19Z xxserg@gmail.com $
 */

$lang = array();

/**
 * Общие константы
 */
$lang['constant']['dvs_error'] = 'Error!';
$lang['constant']['dvs_error_404'] = 'Page not found';
$lang['constant']['dvs_error_login'] = 'Wrong login or password';
$lang['constant']['dvs_error_not_exist'] = 'Record not found';
$lang['constant']['dvs_error_db_not_exist'] = 'Db not found';
$lang['constant']['dvs_error_dynamic_not_exist'] =  'Error Dynamic!';
$lang['constant']['dvs_error_layout_not_exist'] = 'Error Layout!';
$lang['constant']['dvs_error_service'] = 'Service is temporary not availiable';
$lang['constant']['dvs_error_forbidden1'] = 'Operation not permited (#1)';
$lang['constant']['dvs_error_forbidden2'] = 'Operation not permited (#2)';
$lang['constant']['dvs_error_forbidden3'] = 'Operation not permited (#3)';
$lang['constant']['dvs_error_forbidden4'] = 'Operation not permited (#4)';
$lang['constant']['dvs_error_forbidden5'] = 'Operation not permited (#5)';
$lang['constant']['dvs_error_method'] = 'Error method';
$lang['constant']['dvs_error_href'] = 'Wrong reference';
$lang['constant']['dvs_error_help'] = 'Error help';
$lang['constant']['dvs_error_exist'] = 'Error Already exist';
$lang['constant']['dvs_add_row'] = 'Added';
$lang['constant']['dvs_delete_row'] = 'Deleted';
$lang['constant']['dvs_update_row'] = 'Edited';
$lang['constant']['dvs_send_letter'] = 'Sended';
$lang['constant']['dvs_new'] = 'Add';
$lang['constant']['dvs_edit'] = 'Edit';
$lang['constant']['dvs_delete'] = 'Delete';
$lang['constant']['dvs_save'] = 'Save';
$lang['constant']['dvs_next'] = 'Next';
$lang['constant']['dvs_prev'] = 'Prev.';
$lang['constant']['dvs_required'] = 'Fill field';
$lang['constant']['dvs_error_status'] = 'Wrong status';
$lang['constant']['dvs_error_pay'] = 'Not enoth money';
$lang['constant']['dvs_no_records'] = 'No records';
$lang['constant']['dvs_cnt_records'] = 'Count records';
$lang['constant']['dvs_confirm'] = 'Are you sure to delete this?';
$lang['constant']['dvs_reg_email'] = 'The e-mail was send to you';
$lang['constant']['dvs_error_activate'] = 'Wrong code';
$lang['constant']['dvs_error_reactivate'] = 'Already active';
$lang['constant']['dvs_activate'] = 'Now active';
$lang['constant']['dvs_moder'] = 'Waiting moderation';
$lang['constant']['dvs_pay_success'] = 'Payed';
$lang['constant']['dvs_error_pay'] = 'Error payed';
$lang['constant']['dvs_exit'] = 'Exit';
$lang['constant']['dvs_login'] = 'Sign in';
$lang['constant']['dvs_login_alt'] = 'Sign in';
$lang['constant']['dvs_contact_us'] = 'Feedback';
$lang['constant']['CHARSET'] = 'windows-1251';
//$lang['constant']['CHARSET'] = 'ansi';
$lang['constant']['dvs_total'] = 'Total';
$lang['constant']['dvs_search'] = 'Search';
$lang['constant']['error_contacts'] = 'Enter contacts!';


/**
 * Администратор
 */
$lang['layout']['admin']['page_title'] = 'Администратор';
$lang['layout']['admin']['login_title'] = 'Администратор';
$lang['layout']['admin']['menu_text']['?op=users'] = 'Пользователи';
//$lang['layout']['admin']['menu_text']['?op=users&role_id=1'] = 'Администраторы';
//$lang['layout']['admin']['menu_text']['?op=users&role_id=2'] = 'Редакторы';
//$lang['layout']['admin']['menu_text']['?op=users&role_id=3'] = 'Владельцы';
//$lang['layout']['admin']['menu_text']['?op=users&role_id=4'] = 'Туристы';

//$lang['layout']['admin']['menu_text']['zag5'] = 'Настройки';
$lang['layout']['admin']['menu_text']['?op=countries'] = 'Страны';
$lang['layout']['admin']['menu_text']['?op=option_groups'] = 'Группы опций';
$lang['layout']['admin']['menu_text']['?op=options'] = 'Опции';
$lang['layout']['admin']['menu_text']['?op=query'] = 'Вопросы';
//$lang['layout']['admin']['menu_text']['?op=pay_services'] = 'Платежные системы';
$lang['layout']['admin']['menu_text']['?op=pages'] = 'Страницы';
$lang['layout']['admin']['menu_text']['?op=villa'] = 'Виллы';
$lang['layout']['admin']['menu_text']['?op=payments_in'] = 'Платежи';


/**
 * Редактор
 */

$lang['layout']['redactor']['page_title'] = 'Администратор';
$lang['layout']['redactor']['login_title'] = 'Модератор';
$lang['layout']['redactor']['menu_text']['zag2'] = 'Модерация';


/**
 * Владелец
 */
$lang['layout']['client']['page_title'] = 'Office';
$lang['layout']['client']['login_title'] = 'Owner';
$lang['layout']['client']['menu_text']['/en/office/?op=villa'] = 'Properties';
$lang['layout']['client']['menu_text']['/en/office/?op=booking'] = 'Bookings';
$lang['layout']['client']['menu_text']['/en/office/?op=query'] = 'Messages';
$lang['layout']['client']['menu_text']['/en/office/?op=users&act=edit'] = 'Personal';
$lang['layout']['client']['menu_text']['/en/office/?op=transactions'] = 'Payments';

/**
 * Арендатор
 */
$lang['layout']['loginuser']['page_title'] = 'Office';
$lang['layout']['loginuser']['login_title'] = 'Renter';
//$lang['layout']['loginuser']['menu_text']['?op=booking'] = 'Заявки на бронирование';
//$lang['layout']['loginuser']['menu_text']['?op=villa'] = 'Объекты';
$lang['layout']['loginuser']['menu_text']['/en/office/?op=booking'] = 'Bookings';
$lang['layout']['loginuser']['menu_text']['/en/office/?op=query'] = 'Messages';
$lang['layout']['loginuser']['menu_text']['/en/office/?op=users&act=edit'] = 'Personal';
//$lang['layout']['loginuser']['menu_text']['?op=users&act=show'] = 'Реквизиты';
$lang['layout']['loginuser']['menu_text']['/en/office/?op=transactions'] = 'Payments';
//$lang['layout']['loginuser']['menu_text']['?op=pay_services&act=select'] = 'Пополнить счет';

/**
 * Frontend
 */
$lang['layout']['user']['project_title'] = 'ruRenter.ru';
$lang['layout']['user']['login_title'] = 'Sign in';

$lang['layout']['user']['exit'] = 'exit';


$lang['layout']['user']['keywords'] = 'ruRenter.ru – choose holiday home for rent worlwide';
$lang['layout']['user']['description'] = 'Self catering villas, apartments and cottages for rent over the world';

$lang['layout']['user']['menu_text']['/en/'] = 'Main';
$lang['layout']['user']['menu_text']['/en/pages/about/'] = 'About';
$lang['layout']['user']['menu_text']['/en/pages/faq/'] = 'FAQ';
$lang['layout']['user']['menu_text']['/en/reg/'] = 'Registration';

$lang['layout']['user']['menu_bottom_text']['/en/'] = 'Main';
$lang['layout']['user']['menu_bottom_text']['/en/pages/about/'] = 'About';
$lang['layout']['user']['menu_bottom_text']['/en/pages/faq/'] = 'FAQ';
$lang['layout']['user']['menu_bottom_text']['/en/pages/contacts/'] = 'Contacts';



/**
 * DBO
 */

/**
 * Bankinfo
 */
$lang['dbo']['bankinfo']['center_title'] = 'Bank details';
$lang['dbo']['bankinfo']['head_form'] = 'details';
$lang['dbo']['bankinfo']['fb_fieldLabels']['user_id'] = 'User';
$lang['dbo']['bankinfo']['fb_fieldLabels']['account_name']  =  'Account name';
$lang['dbo']['bankinfo']['fb_fieldLabels']['bank_name']  =  'Bank name';
$lang['dbo']['bankinfo']['fb_fieldLabels']['bank_address']  =  'Bank address';
$lang['dbo']['bankinfo']['fb_fieldLabels']['bank_city']  =  'Bank city';
$lang['dbo']['bankinfo']['fb_fieldLabels']['bank_country']  =  'Bank country';
$lang['dbo']['bankinfo']['fb_fieldLabels']['bank_phone']  =  'Bank phone';
$lang['dbo']['bankinfo']['fb_fieldLabels']['postcode']  =  'Bank zip';
$lang['dbo']['bankinfo']['fb_fieldLabels']['account_number']  =  'Accoun number';
$lang['dbo']['bankinfo']['fb_fieldLabels']['swift']  =  'SWIFT';
$lang['dbo']['bankinfo']['fb_fieldLabels']['bic']  =  'BIC';
$lang['dbo']['bankinfo']['fb_fieldLabels']['iban']  =  'IBAN';


/**
 * Booking
 */
$lang['dbo']['booking']['center_title'] = 'Bookings';
$lang['dbo']['booking']['head_form'] = 'booking';
$lang['dbo']['booking']['fb_fieldLabels']['villa_title'] = 'Property';
$lang['dbo']['booking']['fb_fieldLabels']['start_date']  =  'Date 1';
$lang['dbo']['booking']['fb_fieldLabels']['end_date']  =  'Date 2';
$lang['dbo']['booking']['fb_fieldLabels']['book_status']  =  'Status';
$lang['dbo']['booking']['fb_fieldLabels']['price']  =  'Price';
$lang['dbo']['booking']['fb_fieldLabels']['currency']  =  'Currency';
$lang['dbo']['booking']['fb_fieldLabels']['post_date']  =  'Add date';
$lang['dbo']['booking']['book_status_arr'][1] = 'Booked by owner';
$lang['dbo']['booking']['book_status_arr'][2] = 'Reserved';
$lang['dbo']['booking']['book_status_arr'][3] = 'Invoice sended';
$lang['dbo']['booking']['book_status_arr'][4] = 'Payed';
$lang['dbo']['booking']['book_status_arr'][5] = 'Canceled';
$lang['dbo']['booking']['book_status_arr'][6] = 'Invoice for payment';


/**
 * Countries
 */
$lang['dbo']['countries']['center_title'] = 'Регионы';
$lang['dbo']['countries']['head_form'] = 'регион';
$lang['dbo']['countries']['fb_fieldLabels']['name'] = 'Название';
$lang['dbo']['countries']['fb_fieldLabels']['rus_name'] = 'Рус.';
$lang['dbo']['countries']['words']['rent'] = 'rent';

/**
 * Images
 */
$lang['dbo']['images']['center_title'] = 'Photo';
$lang['dbo']['images']['head_form'] = 'photo';
$lang['dbo']['images']['fb_fieldLabels']['image']            = 'Photo';
$lang['dbo']['images']['fb_fieldLabels']['main']             = 'Main';
$lang['dbo']['images']['fb_fieldLabels']['alternative_text'] = 'Caption';


/**
 * option_groups
 */
$lang['dbo']['option_groups']['center_title'] = 'Группы опций';
$lang['dbo']['option_groups']['head_form'] = 'Добавить группу';
$lang['dbo']['option_groups']['fb_fieldLabels']['name'] = 'Название';
$lang['dbo']['option_groups']['fb_fieldLabels']['rus_name'] = 'Рус.';


/**
 * options
 */
$lang['dbo']['options']['center_title'] = 'Опции';
$lang['dbo']['options']['head_form'] = 'Добавить опцию';
$lang['dbo']['options']['fb_fieldLabels']['group_id'] = 'Группа';
$lang['dbo']['options']['fb_fieldLabels']['name'] = 'Название';
$lang['dbo']['options']['fb_fieldLabels']['rus_name'] = 'Рус.';

/**
 * villa
 */
$lang['dbo']['villa']['center_title'] = 'Properties';
$lang['dbo']['villa']['head_form'] = 'Add property';
$lang['dbo']['villa']['fb_fieldLabels']['id'] = '#';
$lang['dbo']['villa']['fb_fieldLabels']['main_image'] = '&nbsp;';
$lang['dbo']['villa']['fb_fieldLabels']['title'] = 'Title';
$lang['dbo']['villa']['fb_fieldLabels']['user_id'] = 'Owner';
$lang['dbo']['villa']['fb_fieldLabels']['summary'] = 'Information';
$lang['dbo']['villa']['fb_fieldLabels']['address'] = 'address';
$lang['dbo']['villa']['fb_fieldLabels']['rooms'] = 'rooms';
$lang['dbo']['villa']['fb_fieldLabels']['sleeps'] = 'sleeps';
$lang['dbo']['villa']['fb_fieldLabels']['proptype'] = 'type';
$lang['dbo']['villa']['fb_fieldLabels']['price'] = 'price per week<br>(include rurenter.ru commission <b>10%</b>)';
$lang['dbo']['villa']['fb_fieldLabels']['breakage'] = 'Breakage waiver';
$lang['dbo']['villa']['fb_fieldLabels']['title_name'] = 'Rus';
$lang['dbo']['villa']['fb_fieldLabels']['city'] = 'City';
$lang['dbo']['villa']['words']['rent'] = 'rent';
$lang['dbo']['villa']['words']['villa_rentals'] = 'Villa rentals';
$lang['dbo']['villa']['words']['keyword'] = 'Keyword';
$lang['dbo']['villa']['words']['price_per_week'] = 'Price per week';
$lang['dbo']['villa']['words']['min'] = 'Min.';
$lang['dbo']['villa']['words']['max'] = 'Max.';
$lang['dbo']['villa']['words']['sleeps'] = 'Sleeps';
$lang['dbo']['villa']['words']['type'] = 'Type';
$lang['dbo']['villa']['words']['price_rule'] = 'Price min could be lower than max';
$lang['dbo']['villa']['words']['any'] = 'Any';
$lang['dbo']['villa']['form_help'][1] = 'Enter address or location name and click "Find"';
$lang['dbo']['villa']['form_help'][2] = "Find";
$lang['dbo']['villa']['form_help'][3] = 'point with cursor';
$lang['dbo']['villa']['form_help'][4] = 'If it your place, click \"Next\", if not, enter more accurate address and click \"Find\".<br>If you want point the place with cursor, click \"point with cursor\"';

$lang['dbo']['villa']['form_headers'][1] = 'Location';
$lang['dbo']['villa']['form_headers'][2] = 'Description';
$lang['dbo']['villa']['form_headers'][3] = 'Facilities';
$lang['dbo']['villa']['form_headers'][4] = 'Photos';
$lang['dbo']['villa']['form_headers'][5] = 'Prices';
$lang['dbo']['villa']['form_headers'][6] = 'Bookings';

$lang['dbo']['villa']['pay_period_arr'][1] = 'per week';
$lang['dbo']['villa']['pay_period_arr'][2] = 'per night';
$lang['dbo']['villa']['currency_arr']['RUR'] = 'RUR';
$lang['dbo']['villa']['currency_arr']['USD'] = 'USD';
$lang['dbo']['villa']['currency_arr']['EUR'] = 'EUR';

/**
 * query
 */
$lang['dbo']['query']['center_title'] = 'Query';
$lang['dbo']['query']['head_form'] = 'Query';
$lang['dbo']['query']['header1'] = 'Contacts';

$lang['dbo']['query']['fb_fieldLabels']['first_name'] = 'First name';
$lang['dbo']['query']['fb_fieldLabels']['last_name'] = 'Second name';
$lang['dbo']['query']['fb_fieldLabels']['email'] = 'E-mail';
$lang['dbo']['query']['fb_fieldLabels']['phone'] = 'Phone';
$lang['dbo']['query']['fb_fieldLabels']['body'] = 'Text';
$lang['dbo']['query']['fb_fieldLabels']['post_date'] = 'Date';
$lang['dbo']['query']['fb_fieldLabels']['status_id'] = 'Status';



$lang['dbo']['query']['fb_fieldLabels']['title_name'] = 'Рус.';

/**
 * Users
 */
$lang['dbo']['users']['center_title'] = 'Users';

$lang['dbo']['users']['words']['form_title'] = 'Personal Information';
$lang['dbo']['users']['words']['form_title2'] = 'Contact information';
$lang['dbo']['users']['words']['agree'] = ' I agree with rules ';
$lang['dbo']['users']['words']['agreement'] = 'user agreements';
$lang['dbo']['users']['words']['error_format'] = 'wrong field format';
$lang['dbo']['users']['words']['disagree'] = 'You are not agree with the rules!';
$lang['dbo']['users']['words']['bank_details'] = 'Bank details';
$lang['dbo']['users']['head_form'] = 'user';
$lang['dbo']['users']['fb_fieldLabels']['email'] = 'E-mail';
$lang['dbo']['users']['fb_fieldLabels']['password'] = 'Password';
$lang['dbo']['users']['fb_fieldLabels']['confirm_password'] = 'Confirm password';

$lang['dbo']['users']['fb_fieldLabels']['last_ip'] = 'Ip';
$lang['dbo']['users']['fb_fieldLabels']['name'] = 'Name';
$lang['dbo']['users']['fb_fieldLabels']['lastname'] = 'Last name';
$lang['dbo']['users']['fb_fieldLabels']['address'] = 'Address';
$lang['dbo']['users']['fb_fieldLabels']['company'] = 'Company';

$lang['dbo']['users']['fb_fieldLabels']['home_phone'] = 'Home phone';
$lang['dbo']['users']['fb_fieldLabels']['work_phone'] = 'Work phone';
$lang['dbo']['users']['fb_fieldLabels']['mobile_phone'] = 'Mobile phone';
$lang['dbo']['users']['fb_fieldLabels']['reg_date'] = 'Registration date';
$lang['dbo']['users']['fb_fieldLabels']['last_date'] = 'Last date';
$lang['dbo']['users']['fb_fieldLabels']['role_id'] = 'who are you';
$lang['dbo']['users']['fb_fieldLabels']['status_id'] = 'Status';
$lang['dbo']['users']['fb_fieldLabels']['nick'] = 'Forum nickname';
$lang['dbo']['users']['fb_fieldLabels']['note'] = 'Additional';
$lang['dbo']['users']['rules']['email'][] = 'Wrong field format';
$lang['dbo']['users']['rules']['email'][] = 'This E-mail already registered!';
$lang['dbo']['users']['rules']['phone'][] = 'Enter phone in format +7(495)111-22-33" !';

$lang['dbo']['users']['words']['reg_title'] = 'New user';
$lang['dbo']['users']['words']['reg_help'] = 'Enter e-mail. Activation code will be sent you.';
$lang['dbo']['users']['fb_fieldLabels']['role_id_title'] = 'Who are you?';
$lang['dbo']['users']['fb_fieldLabels']['role_id_label1'] = 'owner';
$lang['dbo']['users']['fb_fieldLabels']['role_id_label2'] = 'renter';
$lang['dbo']['users']['rules']['password'][] = 'Password is not less than 5 chars!';
$lang['dbo']['users']['rules']['password'][] = 'Passwords not match!';


?>
