<?php
/**
 * @package villarenters.ru
 * @description Языковый файл Русский язык
 * $Id: Lang_ru.php 532 2015-03-27 07:54:54Z xxserg $
 */

$lang = array();

/**
 * Общие константы
 */
$lang['constant']['dvs_error'] = 'Ошибка!';
$lang['constant']['dvs_error_404'] = 'Нет такого файла на сервере...';
$lang['constant']['dvs_error_login'] = 'Неправильный логин или пароль';
$lang['constant']['dvs_error_not_exist'] = 'Запись не существует';
$lang['constant']['dvs_error_db_not_exist'] = 'БД не существует';
$lang['constant']['dvs_error_dynamic_not_exist'] =  'Ошибка! Не существует класса Dynamic!';
$lang['constant']['dvs_error_layout_not_exist'] = 'Ошибка! Не существует класса Layout!';
$lang['constant']['dvs_error_service'] = 'Сервис временно недоступен. Приносим свои извинения';
$lang['constant']['dvs_error_forbidden1'] = 'Операция запрещена! (#1)';
$lang['constant']['dvs_error_forbidden2'] = 'Операция запрещена! (#2)';
$lang['constant']['dvs_error_forbidden3'] = 'Операция запрещена! (#3)';
$lang['constant']['dvs_error_forbidden4'] = 'Операция запрещена! (#4)';
$lang['constant']['dvs_error_forbidden5'] = 'Операция запрещена! (#5)';
$lang['constant']['dvs_error_method'] = 'Метода не существует!';
$lang['constant']['dvs_error_href'] = 'Неправильная ссылка!';
$lang['constant']['dvs_error_help'] = 'Помощь отсутствует!';
$lang['constant']['dvs_error_exist'] = 'Внимание! Похожие или аналогичные записи в базе уже есть!';
$lang['constant']['dvs_add_row'] = 'Запись добавлена!';
$lang['constant']['dvs_delete_row'] = 'Запись удалена!';
$lang['constant']['dvs_update_row'] = 'Запись изменена!';
$lang['constant']['dvs_send_letter'] = 'Письмо отправлено!';
$lang['constant']['dvs_new'] = 'Добавить';
$lang['constant']['dvs_edit'] = 'Редактировать';
$lang['constant']['dvs_delete'] = 'Удалить';
$lang['constant']['dvs_save'] = 'Сохранить';
$lang['constant']['dvs_next'] = 'Дальше';
$lang['constant']['dvs_prev'] = 'Назад';
$lang['constant']['dvs_required'] = 'Заполните поле';
$lang['constant']['dvs_error_status'] = 'Непроверенный статус';
$lang['constant']['dvs_error_pay'] = 'Недостаточная сумма на счету!';
$lang['constant']['dvs_no_records'] = 'Записей нет';
$lang['constant']['dvs_cnt_records'] = 'Всего записей:';
$lang['constant']['dvs_confirm'] = 'Вы уверены, что хотите удалить эту запись?';
$lang['constant']['dvs_reg_email'] = "Вам был отправлен e-mail для подтверждения регистрации!";
$lang['constant']['dvs_error_activate'] = "Некорректный код активации!";
$lang['constant']['dvs_error_reactivate'] = "Ваша регистрация уже была активирована!";
$lang['constant']['dvs_activate'] = "Ваша регистрация активирована!!";
$lang['constant']['dvs_moder'] = 'Запись добавлена, ожидает модерации администратором';
$lang['constant']['dvs_pay_success'] = 'Заявка оплачена!';
$lang['constant']['dvs_error_pay'] = 'Ошибка платежа';
$lang['constant']['dvs_exit'] = 'Выход';
$lang['constant']['dvs_login'] = 'Вход';
$lang['constant']['dvs_login_alt'] = 'Вход в личный офис клиента rurenter.ru';
$lang['constant']['dvs_contact_us'] = 'Обратная связь';
$lang['constant']['CHARSET'] = 'windows-1251';
$lang['constant']['dvs_total'] = 'Всего записей';
$lang['constant']['dvs_search'] = 'Поиск';
$lang['constant']['error_contacts'] = 'Заполните реквизиты!';


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
//$lang['layout']['admin']['menu_text']['?op=options'] = 'Опции';
$lang['layout']['admin']['menu_text']['?op=query'] = 'Вопросы';
$lang['layout']['admin']['menu_text']['?op=booking&book_status=2'] = 'Заявки на бронирование';
$lang['layout']['admin']['menu_text']['?op=booking_log'] = 'Заявки villarenters.ru';
$lang['layout']['admin']['menu_text']['?op=pages'] = 'Страницы';
$lang['layout']['admin']['menu_text']['?op=villa'] = 'Виллы';
$lang['layout']['admin']['menu_text']['?op=villa&sale=1'] = 'Продажа';
$lang['layout']['admin']['menu_text']['?op=payments_in'] = 'Платежи';
$lang['layout']['admin']['menu_text']['?op=villa&act=import'] = 'Импорт';


/**
 * Редактор
 */

$lang['layout']['redactor']['page_title'] = 'Администратор';
$lang['layout']['redactor']['login_title'] = 'Модератор';
$lang['layout']['redactor']['menu_text']['zag2'] = 'Модерация';


/**
 * Владелец
 */
$lang['layout']['client']['page_title'] = 'Администратор';
$lang['layout']['client']['login_title'] = 'Владелец';
//$lang['layout']['client']['menu_text']['?op=booking'] = 'Заявки на бронирование';
//$lang['layout']['client']['menu_text']['?op=villa'] = 'Объекты';
$lang['layout']['client']['menu_text']['?op=villa'] = 'Аренда';
$lang['layout']['client']['menu_text']['?op=villa&sale=1'] = 'Продажа';

$lang['layout']['client']['menu_text']['?op=booking'] = 'Заявки';
//$lang['layout']['client']['menu_text']['?op=query'] = 'Сообщения';
$lang['layout']['client']['menu_text']['/lorem/'] = 'Форум';

$lang['layout']['client']['menu_text']['?op=users&act=edit'] = 'Реквизиты';
//$lang['layout']['client']['menu_text']['?op=users&act=show'] = 'Реквизиты';
$lang['layout']['client']['menu_text']['?op=transactions'] = 'Личный счет';
//$lang['layout']['client']['menu_text']['?op=pay_services&act=select'] = 'Пополнить счет';

/**
 * Арендатор
 */
$lang['layout']['loginuser']['page_title'] = 'Администратор';
$lang['layout']['loginuser']['login_title'] = 'Арендатор';
//$lang['layout']['loginuser']['menu_text']['?op=booking'] = 'Заявки на бронирование';
//$lang['layout']['loginuser']['menu_text']['?op=villa'] = 'Объекты';
$lang['layout']['loginuser']['menu_text']['/'] = 'Главная';
$lang['layout']['loginuser']['menu_text']['/office/?op=booking'] = 'Заявки';
//$lang['layout']['loginuser']['menu_text']['/office/?op=query'] = 'Сообщения';
$lang['layout']['loginuser']['menu_text']['/lorem/'] = 'Форум';

$lang['layout']['loginuser']['menu_text']['/office/?op=users&act=edit'] = 'Реквизиты';
//$lang['layout']['loginuser']['menu_text']['?op=users&act=show'] = 'Реквизиты';
$lang['layout']['loginuser']['menu_text']['/office/?op=transactions'] = 'Личный счет';
//$lang['layout']['loginuser']['menu_text']['?op=pay_services&act=select'] = 'Пополнить счет';

/**
 * Frontend
 */
$lang['layout']['user']['project_title'] = 'ruRenter.ru';


$lang['layout']['user']['keywords'] = 'аренда квартир без посредников';
$lang['layout']['user']['description'] = 'Предложения по аренда квартир без посредников. Прямая аренда квартир в Москве, Петербурге и по России.';
//$lang['layout']['user']['menu_text']['zag1'] = 'Villarenters';
//$lang['layout']['user']['menu_text']['/'] = 'Главная';
$lang['layout']['user']['menu_text']['/'] = 'Аренда';
$lang['layout']['user']['menu_text']['/sale/'] = 'Продажа';
$lang['layout']['user']['menu_text']['/pages/about/'] = 'О проекте';
$lang['layout']['user']['menu_text']['/pages/faq/'] = 'Вопрос-Ответ';
$lang['layout']['user']['menu_text']['/lorem/'] = 'Форум';
//$lang['layout']['user']['menu_text']['?op=pages&act=avia'] = 'Авиабилеты';
$lang['layout']['user']['menu_text']['/reg/'] = 'Регистрация';
$lang['layout']['user']['menu_bottom_text']['/'] = 'Главная';
$lang['layout']['user']['menu_bottom_text']['/pages/about/'] = 'О проекте';
$lang['layout']['user']['menu_bottom_text']['/pages/faq/'] = 'Вопрос-ответ';
$lang['layout']['user']['menu_bottom_text']['/pages/contacts/'] = 'Контакты';
$lang['layout']['user']['menu_bottom_text']['/contact/'] = 'Задать вопрос';
$lang['layout']['user']['menu_bottom_text']['/?versm=mob'] = 'Мобильная версия';
/*
			<li><a href="/">Главная</a></li>
			<li><a href="/pages/about/">О проекте</a></li>
			<li><a href="/pages/faq/">Вопрос-ответ</a></li>
			<li><a href="/">Карта сайта</a></li>
			<li><a href="/pages/contacts/">Контакты</a></li>
*/

/**
 * DBO
 */

/**
 * Bankinfo
 */
$lang['dbo']['bankinfo']['center_title'] = 'Банковские реквизиты';
$lang['dbo']['bankinfo']['head_form'] = 'Добавить реквизиты';
$lang['dbo']['bankinfo']['fb_fieldLabels']['user_id'] = 'Пользователь';
$lang['dbo']['bankinfo']['fb_fieldLabels']['account_name']  =  'Имя владельца счета';
$lang['dbo']['bankinfo']['fb_fieldLabels']['bank_name']  =  'Название банка';
$lang['dbo']['bankinfo']['fb_fieldLabels']['bank_address']  =  'Адрес банка';
$lang['dbo']['bankinfo']['fb_fieldLabels']['bank_city']  =  'Город банка';
$lang['dbo']['bankinfo']['fb_fieldLabels']['bank_country']  =  'Страна банка';
$lang['dbo']['bankinfo']['fb_fieldLabels']['bank_phone']  =  'Телефон банка';
$lang['dbo']['bankinfo']['fb_fieldLabels']['postcode']  =  'Индекс банка';
$lang['dbo']['bankinfo']['fb_fieldLabels']['account_number']  =  'Номер счета';
$lang['dbo']['bankinfo']['fb_fieldLabels']['swift']  =  'SWIFT';
$lang['dbo']['bankinfo']['fb_fieldLabels']['bic']  =  'BIC';
$lang['dbo']['bankinfo']['fb_fieldLabels']['iban']  =  'IBAN';


/**
 * Booking
 */
$lang['dbo']['booking']['center_title'] = 'Заявки на бронирование';
$lang['dbo']['booking']['head_form'] = 'Добавить бронь';
$lang['dbo']['booking']['fb_fieldLabels']['villa_title'] = 'Объект';
$lang['dbo']['booking']['fb_fieldLabels']['start_date']  =  'Начало периода';
$lang['dbo']['booking']['fb_fieldLabels']['end_date']  =  'Конец';
$lang['dbo']['booking']['fb_fieldLabels']['book_status']  =  'Статус';
$lang['dbo']['booking']['fb_fieldLabels']['price']  =  'Цена';
$lang['dbo']['booking']['fb_fieldLabels']['currency']  =  'Валюта';
$lang['dbo']['booking']['fb_fieldLabels']['post_date']  =  'Дата добавления';
$lang['dbo']['booking']['book_status_arr'][1] = 'Бронь владельца';
$lang['dbo']['booking']['book_status_arr'][2] = 'Зарезервировано';
$lang['dbo']['booking']['book_status_arr'][3] = 'Выставлен счет';
$lang['dbo']['booking']['book_status_arr'][4] = 'Оплачено';
$lang['dbo']['booking']['book_status_arr'][5] = 'Отменена';
$lang['dbo']['booking']['book_status_arr'][6] = 'Счет к оплате';

/**
 * Countries
 */
$lang['dbo']['countries']['center_title'] = 'Регионы';
$lang['dbo']['countries']['head_form'] = 'Добавить регион';
$lang['dbo']['countries']['fb_fieldLabels']['name'] = 'Название';
$lang['dbo']['countries']['fb_fieldLabels']['rus_name'] = 'Рус.';

/**
 * Images
 */
$lang['dbo']['images']['center_title'] = 'Фото';
$lang['dbo']['images']['head_form'] = 'фото';
$lang['dbo']['images']['fb_fieldLabels']['image']            = 'Фото';
$lang['dbo']['images']['fb_fieldLabels']['main']             = 'Главное';
$lang['dbo']['images']['fb_fieldLabels']['alternative_text'] = 'Подпись';




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
$lang['dbo']['villa']['center_title'] = 'Объекты';
$lang['dbo']['villa']['head_form'] = 'Добавить объект';
$lang['dbo']['villa']['fb_fieldLabels']['id'] = '#';
$lang['dbo']['villa']['fb_fieldLabels']['main_image'] = '&nbsp;';
$lang['dbo']['villa']['fb_fieldLabels']['title'] = 'Название';
$lang['dbo']['villa']['fb_fieldLabels']['user_id'] = 'Владелец';
$lang['dbo']['villa']['fb_fieldLabels']['summary'] = 'Описание';
$lang['dbo']['villa']['fb_fieldLabels']['address'] = 'Адрес';
$lang['dbo']['villa']['fb_fieldLabels']['rooms'] = 'Комнат';
$lang['dbo']['villa']['fb_fieldLabels']['sleeps'] = 'Кол-во спальных мест';
$lang['dbo']['villa']['fb_fieldLabels']['proptype'] = 'Тип объекта';
$lang['dbo']['villa']['fb_fieldLabels']['price'] = 'Цена от и до<br>(с учетом комиссии<br> rurenter.ru <b>10%</b>)';
$lang['dbo']['villa']['fb_fieldLabels']['breakage'] = 'Залог за ущерб';
$lang['dbo']['villa']['fb_fieldLabels']['title_name'] = 'Рус.';
$lang['dbo']['villa']['fb_fieldLabels']['city'] = 'Город';

$lang['dbo']['villa']['words']['rent'] = 'аренда';
$lang['dbo']['villa']['words']['villa_rentals'] = 'Аренда вилл';
$lang['dbo']['villa']['words']['keyword'] = 'Ключевое слово';
$lang['dbo']['villa']['words']['price_per_week'] = 'Цена (в неделю)';
$lang['dbo']['villa']['words']['min'] = 'Мин.';
$lang['dbo']['villa']['words']['max'] = 'Макс.';
$lang['dbo']['villa']['words']['sleeps'] = 'Мест';
$lang['dbo']['villa']['words']['type'] = 'Тип';
$lang['dbo']['villa']['words']['price_rule'] = 'Цена мин. должна быть меньше макс.';
$lang['dbo']['villa']['words']['any'] = 'Все';

$lang['dbo']['villa']['form_help'][0] = 'Введите название ближайшего населенного пункта';
$lang['dbo']['villa']['form_help'][1] = 'Введите адрес или название ближайшего населенного пункта, нажмите "Искать"';
$lang['dbo']['villa']['form_help'][2] = 'Искать';
$lang['dbo']['villa']['form_help'][3] = 'Указать курсором';
$lang['dbo']['villa']['form_help'][4] = 'Если это Ваш объект, нажмите \"Далее\", если нет, уточните адрес и еще раз нажмите \"Искать\".<br>Если Вы хотите указать точное положение с помощью курсора, нажмите \"Указать курсором\"';
$lang['dbo']['villa']['form_help'][5] = 'Для указания точного места нажмите \"Указать курсором\"';


$lang['dbo']['villa']['form_headers'][1] = 'Расположение';
$lang['dbo']['villa']['form_headers'][2] = 'Описание';
$lang['dbo']['villa']['form_headers'][3] = 'Услуги';
$lang['dbo']['villa']['form_headers'][4] = 'Фото';
$lang['dbo']['villa']['form_headers'][5] = 'Цены';
$lang['dbo']['villa']['form_headers'][6] = 'Бронь';

$lang['dbo']['villa']['pay_period_arr'][1] = 'в неделю';
$lang['dbo']['villa']['pay_period_arr'][2] = 'в сутки';


/**
 * query
 */
$lang['dbo']['query']['center_title'] = 'Вопрос';
$lang['dbo']['query']['head_form'] = 'Вопрос';
$lang['dbo']['query']['header1'] = 'Контактная информация';

$lang['dbo']['query']['fb_fieldLabels']['first_name'] = 'Имя';
$lang['dbo']['query']['fb_fieldLabels']['last_name'] = 'Фамилия';
$lang['dbo']['query']['fb_fieldLabels']['email'] = 'E-mail';
$lang['dbo']['query']['fb_fieldLabels']['phone'] = 'Телефон (+7(495)711-2233)';
$lang['dbo']['query']['fb_fieldLabels']['body'] = 'Текст';
$lang['dbo']['query']['fb_fieldLabels']['post_date'] = 'Дата добавления';
$lang['dbo']['query']['fb_fieldLabels']['status_id'] = 'Статус';
$lang['dbo']['query']['fb_fieldLabels']['booking_id'] = 'Бронь';


$lang['dbo']['query']['fb_fieldLabels']['title_name'] = 'Рус.';

/**
 * Users
 */
$lang['dbo']['users']['center_title'] = 'Пользователи';

$lang['dbo']['users']['words']['form_title'] = 'Реквизиты';
$lang['dbo']['users']['words']['form_title2'] = 'Контактная информация';
$lang['dbo']['users']['words']['agree'] = ' Я прочитал и согласен с условиями предоставления сервиса ';
$lang['dbo']['users']['words']['agreement'] = 'условия предоставления сервиса';
$lang['dbo']['users']['words']['smsinfo'] = 'настройки sms оповещений';
$lang['dbo']['users']['words']['smsinfo_activate'] = 'ввести sms-код активации';
$lang['dbo']['users']['words']['send_new'] = 'высылать sms уведомления';


$lang['dbo']['users']['words']['error_format'] = 'Непроавильный формат поля';
$lang['dbo']['users']['words']['disagree'] = 'Вы не согласны с условиями сервиса!';
$lang['dbo']['users']['words']['bank_details'] = 'Банковские реквизиты';

$lang['dbo']['users']['head_form'] = 'Добавить пользователя';
$lang['dbo']['users']['fb_fieldLabels']['email'] = 'E-mail';
$lang['dbo']['users']['fb_fieldLabels']['password'] = 'Пароль';
$lang['dbo']['users']['fb_fieldLabels']['confirm_password'] = 'Повторите пароль';

$lang['dbo']['users']['fb_fieldLabels']['last_ip'] = 'Ip';
$lang['dbo']['users']['fb_fieldLabels']['name'] = 'Имя';
$lang['dbo']['users']['fb_fieldLabels']['lastname'] = 'Фамилия';
$lang['dbo']['users']['fb_fieldLabels']['address'] = 'Адрес';
$lang['dbo']['users']['fb_fieldLabels']['company'] = 'Компания';

$lang['dbo']['users']['fb_fieldLabels']['home_phone'] = 'Домашний телефон';
$lang['dbo']['users']['fb_fieldLabels']['work_phone'] = 'Рабочий телефон';
$lang['dbo']['users']['fb_fieldLabels']['mobile_phone'] = 'Мобильный телефон (Формат: 74957112233)';
$lang['dbo']['users']['fb_fieldLabels']['reg_date'] = 'Дата регистрации';
$lang['dbo']['users']['fb_fieldLabels']['last_date'] = 'Последняя дата';
$lang['dbo']['users']['fb_fieldLabels']['role_id'] = 'Роль';
$lang['dbo']['users']['fb_fieldLabels']['status_id'] = 'Статус';
$lang['dbo']['users']['fb_fieldLabels']['nick'] = 'Псевдоним для форума';

$lang['dbo']['users']['fb_fieldLabels']['note'] = 'Доп. информация';
$lang['dbo']['users']['rules']['email'][] = 'Недопустипый формат поля';
$lang['dbo']['users']['rules']['email'][] = 'Такой E-mail уже зарегистрирован!';
$lang['dbo']['users']['rules']['nick'][] = 'Такой псевдоним уже зарегистрирован!';
$lang['dbo']['users']['rules']['phone'][] = 'Введите телефон в формате 74951112233" !';
$lang['dbo']['users']['rules']['mobile_phone'][] = 'Такой телефон уже зарегистрирован!';

$lang['dbo']['users']['words']['reg_title'] = 'Регистрация нового пользователя';
$lang['dbo']['users']['words']['reg_help'] = 'Введите действующий адрес электронной почты.<br>Вам будет выслан код для подтверждения регистрации';
$lang['dbo']['users']['fb_fieldLabels']['role_id_title'] = 'Вы хотите';
$lang['dbo']['users']['fb_fieldLabels']['role_id_label1'] = 'Сдать / Продать (для владельцев)';
$lang['dbo']['users']['fb_fieldLabels']['role_id_label2'] = 'Снять (для арендаторов)';
$lang['dbo']['users']['rules']['password'][] = 'Длина пароля должна быть не менее 5 символов!';
$lang['dbo']['users']['rules']['password'][] = 'Пароли не совпадают!';


/**
 * smsinfo
 */
$lang['dbo']['smsinfo']['center_title'] = 'Мобильный телефон';
$lang['dbo']['smsinfo']['head_form'] = 'телефон';
$lang['dbo']['smsinfo']['fb_fieldLabels']['phone'] = 'телефон';
$lang['dbo']['smsinfo']['fb_fieldLabels']['code'] = 'код подтверждения';
$lang['dbo']['smsinfo']['fb_fieldLabels']['send_new'] = 'высылать уведомления';

/**
 * Dynamic Countries_Index
 */
//$lang['dynamic']['countries_index']['center_title'] = 'Опций';


?>
