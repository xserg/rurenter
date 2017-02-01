<?php
/*////////////////////////////////////////////////////////////////////////////
lib2/DVS
------------------------------------------------------------------------------
Класс обработки форм
использует DB_DataObject_FormBuilder версий 1.0 и выше
formCaptcha
------------------------------------------------------------------------------
$Id: ShowForm.php 527 2014-10-13 13:10:05Z xxserg@gmail.com $
////////////////////////////////////////////////////////////////////////////
*/

require_once COMMON_LIB.'DVS/Dynamic.php';

class DVS_ShowForm extends DVS_Dynamic
{
    /* Объект HTML_QuickForm */
    public $form_obj;
    /* Объект DB_DataObject_FormBuilder */
    private $formBuilder_obj;
    private $form_template_name = 'form.tmpl';
    private $form_template_name_smarty = 'form_smarty.tmpl';

    /* Не использовать renderer smarty */
    private $use_renderer_smarty = false;


    function getPageData()
    {
        //$this->words = $this->{'lang_'.$this->lang};
        //$this->initUseRenderSmarty();
        $this->db_obj->act = $this->act;
        $this->db_obj->lang = $this->lang;

        //Создаем объекты
        $this->createTemplateObj();
        $this->createRendererObj();
        $this->createFormBuilderObj();

        $this->form_obj =& $this->formBuilder_obj->getForm($_SERVER['REQUEST_URI']);
        if ($this->lang == 'ru') {
            $this->form_obj->setRequiredNote('<span style="font-size:80%; color:#ff0000;">*</span> Поля для обязательного заполнения');
        }
        $this->form_obj->setJsWarnings(DVS_ERROR,'');

        //Установка дефолтных значений в форме
        $this->setDefaults();

        //Добавляем элемент формы для сортировки
        if ($this->db_obj->field_order) {
            /* Добавить sorder в $fb_fieldsToRender, если определен, для того, чтобы можно было обновить поле */
            if (isset($this->db_obj->fb_fieldsToRender) && !in_array($this->db_obj->field_order, $this->db_obj->fb_fieldsToRender)) {
                $this->formBuilder_obj->fieldsToRender[] = $this->db_obj->field_order;
            }

            $this->addOrderElement(&$this->form_obj);
        }

        // Сохранение в базе, происходит если не указан шаг, или указан последний шаг
        //if ((!$_REQUEST['step'] || ($_REQUEST['step'] && $this->db_obj->final_step && $_REQUEST['step'] == $this->db_obj->final_step)) && $this->form_obj->validate()) {
        if ($_POST['__submit__'] && $this->form_obj->validate()) {
            return $this->saveForm();
        }

        /* Отображение формы */
        $page_arr['CENTER_TITLE'] = ($this->db_obj->center_title) ? $this->db_obj->center_title : $this->db_obj->__table;
        $page_arr['CENTER'] = $this->showForm();
        $page_arr['CENTER_SUB_TITLE'] = $page_arr['CENTER_TITLE'];
        if ($this->db_obj->back_navigation) {
            $page_arr['BACK_NAVIGATION'] = $this->db_obj->back_navigation;
        }
        return $page_arr;
    }

    function initUseRenderSmarty()
    {
        if (defined('USE_FORM_RENDERER_SMARTY')) {
            $this->use_renderer_smarty = USE_FORM_RENDERER_SMARTY;
        }
        if (isset($this->db_obj->use_renderer_smarty)) {
            $this->use_renderer_smarty = $this->db_obj->use_renderer_smarty;
        }
    }

    function showForm()
    {
        $this->form_obj->accept($this->db_obj->renderer_obj);
        if ($this->use_renderer_smarty) {
            $this->db_obj->template_obj->assign('form', $this->db_obj->renderer_obj->toArray());
            $this->db_obj->template_obj->assign('image_url', IMAGE_URL);
            return $this->db_obj->template_obj->fetch($this->form_template_name_smarty);
        } else {
            $this->template_obj->setVariable(array('IMGS' => IMAGE_URL));
            $this->template_obj->hideBlock('qf_header');
            return $this->template_obj->get();
        }
    }

    function setDefaults()
    {
        if(method_exists($this->db_obj, 'setDefaults')) {
            $this->db_obj->setDefaults(&$this->form_obj);
        }else{
            if (!isset($this->db_obj->id) && $keys = $this->db_obj->keys()) {
                $id = $keys[0];
            } else {
                $id = 'id';
            }
            if ($this->db_obj->$id) {
                $this->form_obj->setDefaults($this->db_obj->toArray());
            }
        }
    }

    function addOrderElement($form_obj)
    {
        if ($this->act == 'new') {
            $db_obj = DB_DataObject::factory($this->db_obj->__table);

            if (method_exists($db_obj, 'whereOrder')) {
                $db_obj->whereOrder();
            }

            $db_obj->orderBy($this->db_obj->field_order.' DESC');
            $db_obj->limit(0,1);
            $db_obj->find(true);

            $form_obj->addElement('hidden', $this->db_obj->field_order, ($db_obj->{$this->db_obj->field_order} + 1));
        }

        if ($this->act == 'edit') {
            $form_obj->addElement('hidden', $this->db_obj->field_order, $this->db_obj->{$this->db_obj->field_order});
        }
    }

    function createRendererObj()
    {
        if(method_exists($this->db_obj, 'getFormRenderer')) {
            $this->db_obj->renderer_obj = $this->db_obj->getFormRenderer(&$this->template_obj);
        } else {
            if ($this->use_renderer_smarty) {
                $this->createRenderer_ArraySmarty();
            } else {
                $this->createRenderer_ITDynamic();
            }
        }
    }

    function createRenderer_Tableless()
    {
        require_once 'HTML/QuickForm/Renderer/Tableless.php';
        $renderer =& new HTML_QuickForm_Renderer_Tableless();
        return $renderer;
    }

    function createRenderer_ITDynamic()
    {
        if (isset($this->db_obj->use_project_form_tmpl)) {
            //$this->template_obj->setRoot(PROJECT_ROOT.'tmpl/');
            $this->template_obj->loadTemplateFile($this->db_obj->form_template_name ? $this->db_obj->form_template_name : $this->form_template_name, 1, 1);
            //$this->template_obj->setRoot(COMMON_LIB.'tmpl/');
        } else {
            $this->template_obj->loadTemplateFile($this->form_template_name, 1, 1);
        }

        require_once 'HTML/QuickForm/Renderer/ITDynamic.php';
        $this->db_obj->renderer_obj = new HTML_QuickForm_Renderer_ITDynamic($this->template_obj);
    }

    function createRenderer_ArraySmarty()
    {
        require_once 'Smarty/Smarty.class.php';
        $this->db_obj->template_obj =& new Smarty;
        $this->db_obj->template_obj->template_dir = COMMON_LIB.'tmpl/';
        $this->db_obj->template_obj->compile_dir  = PROJECT_ROOT.CACHE_FOLDER.'tmpl_smarty/';

        if ($this->iface == 'a' && !file_exists($this->db_obj->template_obj->compile_dir)) {
            mkdir($this->db_obj->template_obj->compile_dir);
            chmod($this->db_obj->template_obj->compile_dir, 0775);
        }

        require_once 'HTML/QuickForm/Renderer/Array.php';
        $this->db_obj->renderer_obj =& new HTML_QuickForm_Renderer_Array(true, true);
    }

    function createFormBuilderObj()
    {
        require_once 'DB/DataObject/FormBuilder.php';

        $this->formBuilder_obj =& DB_DataObject_FormBuilder::create(&$this->db_obj);
        //Отключим проверку в FormBuilder
        $this->formBuilder_obj->validateOnProcess = false;
        //Заголовки полей
        $this->formBuilder_obj->formHeaderText = strtoupper(($_GET['act'] == 'new' ? 'Добавить' : 'Редактировать').' '.$this->db_obj->head_form);
        /* Текст ошибки при обязательном заполнении по умолчанию */
        $this->formBuilder_obj->requiredRuleMessage = 'Заполните поле!';
        /* Текст кнопки submit */
        $this->formBuilder_obj->submitText = 'Сохранить';
    }

    //Сохранение
    function saveForm()
    {
        $this->form_obj->applyFilter('__ALL__', 'trim');
        if (!$this->db_obj->noStripTags) {
            $this->form_obj->applyFilter('__ALL__', 'strip_tags');
        }
        if ($this->db_obj->htmlCharsDecode) {
            if (is_array($this->db_obj->htmlCharsDecode)) {
                $element = $this->db_obj->htmlCharsDecode;
            } else {
                $element = '__ALL__';
            }
            $this->form_obj->applyFilter($element, array(&$this, 'htmlCharsDecode'));
        }

        $qs = $this->db_obj->qs;

        if ($return_process = $this->form_obj->process(array(&$this->formBuilder_obj,'processForm'), false)) {
            //Здесь должна быть обработка ошибок и сообщений
            if ($this->db_obj->msg) {
                $this->msg = $this->db_obj->msg;
            } else if ($_SESSION['message']) {
                $this->msg = $_SESSION['message'];
                unset($_SESSION['message']);
            } else {
                $this->msg = ($this->act == 'edit') ? 'UPDATE_ROW' : 'ADD_ROW';
            }
        }
        
       //$this->db_obj->qs = $qs;
       $this->goLocation();
    }

    // Заменяются при вводе в базу данных
    function htmlCharsDecode($str)
    {
        $input = array(
        '&lt;'       => '<',
        '&gt;'       => '>',
        '&quot;'     => '"',
        '&laquo;'    => '"',
        '&raquo;'    => '"',
        '«'          => '"',
        '»'          => '"',
        '&nbsp'      => ' ',
        '&amp;'      => '&',
        '&copy;'     => '©',
        '&reg;'      => '®',
        '&sup1;'     => '№',
        '&shy;'      => '­',
        '–'          => '-',
        '’'          => "'",
        '‘'          => "'",
        '…'          => '...',
        '“'          => '"',
        '”'          => '"',
        '&#171;'     => '"',
        '&#187;'     => '"',
        '&#8211;'    => '-',
        '&#8212;'    => '-',
        '&#8216;'    => "'",
        '&#8217;'    => "'",
        '&#8230;'    => '...',
        '&#8220;'    => '"',
        '&#8221;'    => '"',
        );
        return preg_replace('/\&#[^;]+;/','',strtr($str,$input));
    }

    /**
    * Генерация визуального WYSIWYG HTML редактора вместо поля ввода textarea
    * Пример подключения
    * $form->addElement(DVS_ShowForm::createFCK('text'));
    * @param $field - название поля формы
    * @param $label - значение поля
    * @param $options - настройки редактора
    * @return string
    */
    function createFCK($field, $table, $id = false, $label = '', $toolbar = 'Custom', $width = '100%', $height = '500', $options = null)
    {
        HTML_Quickform::registerElementType('fckeditor', COMMON_LIB.'DVS/HTML_Quickform_fckeditor.php', 'HTML_Quickform_fckeditor');
        $oQFElement = HTML_Quickform::createElement ('fckeditor', $field, $label);
        $sFCKBasePath = '/fck/FCKeditor/';
        $default_options = array(
            'DefaultLanguage'           => 'ru',
            'CustomConfigurationsPath'  => '/fck/fckconfig.js?'.time(),
            'ResourceFieldName'         => $field,
            'ResourceTableName'         => $table,
        );
        if ($id !== false) {
            $default_options['ResourceRecordId'] = $id ? $id : '0';
        }

        if (is_array($options)) {
            $default_options = array_merge($default_options, $options);
        }
        $oQFElement->setFCKProps($sFCKBasePath, $toolbar, $width, $height, $default_options);
        return $oQFElement;
    }


    public static function formCaptcha( & $form, $lang)
    {
       $lang_ru = array(
            'change_text' => 'Если вы не уверены, кликните на картинку для смены',
            'enter_text' => 'Введите текст с картинки',
            'error_text' => '<br>Текст не соответствует картинке!'
        );

       $lang_en = array(
            'change_text' => 'Click picture to refresh',
            'enter_text' => 'Enter text from the picture',
            'error_text' => '<br>Wrong text from the picture!'

         );

        $words = ${'lang_'.$lang};

        $fonts_arr = array(
            'arial.ttf', 
            'arialbd.ttf',
            'arialbi.ttf',
            'ariali.ttf',
            'ariblk.ttf',
            'cour.ttf',
            'courbd.ttf',
            'courbi.ttf',
            'couri.ttf',
        );
        require_once 'HTML/QuickForm/CAPTCHA/Image.php';
        $options = array(
            'width'        => 250,
            'height'       => 90,
            'callback'     => '/captcha.php?var='.basename(__FILE__, '.php'),
            'sessionVar'   => basename(__FILE__, '.php'),
            //'sessionVar'   => basename($_SERVER['PHP_SELF'], '.php'),
            'imageOptions' => array(
                'font_size' => mt_rand(18,35),
                'font_path' => COMMON_LIB.'fonts/',
                'font_file' => $fonts_arr[array_rand($fonts_arr)]
            )
        );
        if (!$_SESSION) {
            //session_destroy();
            //session_start();
            //session_destroy();
            session_start();
        }
        //echo $options['sessionVar'];
        $captcha_question =  $form->addElement('CAPTCHA_Image', 'captcha_question', '', $options);
        //print_r($captcha_question);
        $form->addElement('static', null, null, $words['change_text']);
        $form->addElement('text', 'captcha', $words['enter_text']);
        $form->addRule('captcha', $words['enter_text'].'!', 'required', null, 'client');
        $form->addRule('captcha', '<br>'.$words['error_text'].'!', 'CAPTCHA', $captcha_question);
    }
}
?>
