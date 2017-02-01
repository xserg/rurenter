<?php
/**
 * Table Definition for images
 */


require_once 'DB/DataObject.php';



class DBO_Images extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'images';                          // table name
    public $_database = 'rurenter';                          // database name (used with database_{*} config)
    public $id;                              // int(4)  primary_key not_null
    public $villa_id;                        // int(4)   not_null
    public $file_name;                       // varchar(255)  
    public $size;                            // varchar(32)   not_null
    public $caption;                         // varchar(255)  

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DBO_Images',$k,$v); }

    function table()
    {
         return array(
             'id' =>  DB_DATAOBJECT_INT + DB_DATAOBJECT_NOTNULL,
             'villa_id' =>  DB_DATAOBJECT_INT + DB_DATAOBJECT_NOTNULL,
             'file_name' =>  DB_DATAOBJECT_STR,
             'size' =>  DB_DATAOBJECT_STR + DB_DATAOBJECT_NOTNULL,
             'caption' =>  DB_DATAOBJECT_STR,
         );
    }

    function keys()
    {
         return array('id');
    }

    function sequenceKey() // keyname, use native, native name
    {
         return array('id', true, false);
    }

    function defaults() // column default values 
    {
         return array(
             '' => null,
         );
    }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE

    public $main_image;

    private $fileTypes = array(
                    'image/pjpeg'                   => 'jpg',
                    'image/jpeg'                    => 'jpg',
                    'image/gif'                     => 'gif',
                    'image/png'                     => 'png',
                    'application/x-shockwave-flash' => 'swf',
                );

    public $fb_fieldLabels  = array(
            'image'          => 'Фото',
            'main'          => 'Главное',
            'size'          => 'Размер',
            'alternative_img_path'  => 'Альтернативное изображение',
            'alternative_text'      => 'Подпись',
    );


    private $num_files = 5;

    function preDelete($path)
    {
        unlink($path);
    }

    function getFormContent($form)
    {
        
        for ($i = 1; $i < $this->num_files; $i++) {
            $form->addElement('file', 'image_'.$i, $this->fb_fieldLabels['image'].' '.$i.': ');
            $form->addElement('text', 'caption_'.$i, $this->fb_fieldLabels['alternative_text'].': ');
            $form->addElement('radio', 'main', $this->fb_fieldLabels['main'].': ', '', $i);
            $form->addElement('static', '', '<hr>');
        }
        //$form->addRule('content_path', 'Ошибка загрузки файла', 'uploadedfile', null);
        /*
        $form->addRule('content_path', 'Превышен максимальный размер файла!', 'maxfilesize', MAX_CONTENT_SIZE);
        $form->addRule('content_path', 'Неправильный тип файла!', 'mimetype', array_keys($this->fileTypes));
        $form->addRule('content_path', 'Неправильный имя файла!', 'filename', '/('.implode('|', $this->fileTypes).')$/i');

        $img_file_types = $this->fileTypes;
        array_pop($img_file_types);
        //$form->addRule('alternative_img_path', 'Ошибка загрузки файла', 'uploadedfile', null);
        $form->addRule('alternative_img_path', 'Превышен максимальный размер файла!', 'maxfilesize', MAX_CONTENT_SIZE);
        $form->addRule('alternative_img_path', 'Неправильный тип файла!', 'mimetype', array_keys($img_file_types));
        $form->addRule('alternative_img_path', 'Неправильный имя файла!', 'filename', '/('.implode('|', $img_file_types).')$/i');
        */
        //$form->setMaxFileSize(MAX_CONTENT_SIZE);
    }

    /**
     * Сохранение файла контента
     * Генерирует имя файла, копирует в заданное место и возвращает имя файла
     * @param string $name - название поля ввода формы
     * @return string - имя сохраненного файла
     */
    function saveFile($name, $path)
    {
        if (isset($this->fileTypes[$_FILES[$name]['type']])) {
            $extension = $this->fileTypes[$_FILES[$name]['type']];
        }
        $file_name = substr(md5(uniqid(rand())), 0, 8).'.'.$extension;
        if (copy($_FILES[$name]["tmp_name"], $path.$file_name)) {
            return $file_name;
        }
        return false;
    }

    function saveContent($villa_id, $vals)
    {
        //print_r($_FILES);
        $path = PROJECT_ROOT.HTDOCS_FOLDER.CONTENT_FOLDER.$villa_id.'/';
        @mkdir($path);
        $this->villa_id = $villa_id;
        //print_r($vals);
        for ($i = 1; $i < $this->num_files; $i++) {
            if ($_FILES['image_'.$i]['name']) {
                $file_name = $this->saveFile('image_'.$i, $path);
                $this->saveImage($file_name, $path, $i, $vals['caption_'.$i], $vals['main']);
            }
        }
        return false;
    }

    function saveImage($file_name, $path, $k, $caption, $main=1)
    {
        //$file_name = $this->saveFile('image_'.$k, $path);
        if ($file_name) {
            //$this->file_name = $this->villa_id.'/'.$file_name;
            $this->caption = $caption;
            $this->size = filesize($path.$file_name);
            $big = $this->resizeImage($path, $file_name, 400, 300);
            $small = $this->resizeImage($path, $file_name, 200, 150);
            if ($big && $small) {
                unlink($path.$file_name);
            }
            $this->file_name = $this->villa_id.'/'.$big;
            //$this->file_name = $this->villa_id.'/'.$this->resizeImage($path, $file_name, 400, 300);
            $this->insert();

            if ($k == $main) {
                //$this->resizeImage($path, $file_name, 200, 150);
                $this->main_image = $this->villa_id.'/'.$small;
                //$this->main_image = $this->villa_id.'/'.$this->resizeImage($path, $file_name, 200, 150);
            }
            return true;
        }
    }

    /**
    354_255
    150_113
    
    HomeAway
    400 300
    200 150
    */
    function resizeImage($path, $file_name, $w, $h)
    {
        list($width, $height) = getimagesize($path.$file_name);
        $image = imagecreatefromjpeg($path.$file_name);
        // Resample
        $image_p = imagecreatetruecolor($w, $h);
        imagecopyresampled($image_p, $image, 0, 0, 0, 0, $w, $h, $width, $height);
        $file_name = preg_replace("/.([a-z]+)$/", '_'.$w.'_'.$h.".$1", $file_name);
        imagejpeg($image_p, $path.$file_name);
        imagedestroy($image_p);
        return $file_name;
    }


    function showImages($tpl, $villa_id)
    {
        $this->villa_id = $villa_id;
        $this->orderBy('id');
        $this->find();
        $i = 1;
        while ($this->fetch()) {
            if ($i == 1) {
                    $tpl->setVariable(array(
                                'M_PHOTO_SRC' => VILLARENTERS_IMAGE_URL.$this->file_name,
                                'M_ALT_TEXT' => $this->caption,
                                'M_CAPTION' => $this->caption,
                                ));
            }
            $tpl->setVariable(array(
                'PHOTO_SRC' => VILLARENTERS_IMAGE_URL.$this->file_name,
                'ALT_TEXT' => $this->caption,
                'CAPTION' => $this->caption,
            ));
            $tpl->parse('GALLERY_PHOTO');
            $i++;
            if ($i%2) {
                $tpl->parse('GALLERY_ROW');
            }
        }
    }

    function imagesForm($tpl, $villa_id, $main_image='')
    {
        $this->villa_id = $villa_id;
        $this->orderBy('id');
        $this->find();
        $i = 1;
        while ($this->fetch()) {
            /*
            $c[0] = $form->createElement('static', 'image_'.$i, $this->file_name);
            $c[1] = $form->createElement('text', 'caption_'.$i, '', 'value='.$this->caption);
            $c[2] = $form->createElement('checkbox', 'del['.$this->id.']', null, 'Удалить');
            //if (!($i % 2)) {
                $form->addGroup($c, 'images', '', '', false);
            //}
            */
            $tpl->setVariable(array(
                'PHOTO_SRC' => VILLARENTERS_IMAGE_URL.$this->file_name,
                'ALT_TEXT' => $this->caption,
                'CAPTION' => $this->caption,
                'CAPTION_NAME' => 'editCaption['.$this->id.']',
                'CHK_NAME' => 'del['.$this->id.']',
                'DVS_DELETE' => DVS_DELETE,
                'MAIN_NAME' => 'editMain',
                'MAIN_VALUE' => $this->file_name,
                'MAIN_LABEL' => $this->fb_fieldLabels['main'],
                'CAPTION_LABEL' => $this->fb_fieldLabels['alternative_text'],
                'CHECKED' => $this->isMain($main_image) ? ' checked="checked"' : '',
            ));
            $tpl->parse('GALLERY_PHOTO');
            $i++;
            if ($i%2) {
                $tpl->parse('GALLERY_ROW');
            }
            
        }
    }

    function editImages($vals)
    {
        //echo '<pre>';
        //print_r($vals);
        if ($vals['editMain']) {
            $this->main_image = str_replace("400_300", "200_150", $vals['editMain']);
        }
        //DB_DataObject::DebugLevel(1);
        $path = PROJECT_ROOT.HTDOCS_FOLDER.CONTENT_FOLDER;
        foreach ($vals['editCaption'] as $id => $caption) {
            //echo "$id => $caption ".$vals['del'][$id]."<br>";
            $images_obj = DB_DataObject::factory('images');
            $images_obj->get($id);
            if ($vals['del'][$id] == 'on') {
                //echo 'Удаление '.$id.'<br>';
                if ($vals['editMain'] == $this->file_name) {
                    continue;
                }
                if ($images_obj->delete()) {
                    unlink(str_replace("400_300", "200_150", $path.$images_obj->file_name));
                    unlink($path.$images_obj->file_name);
                    //continue;
                }
            } else {
                $images_obj->caption = $caption;
                $images_obj->update();
            }
        }
    }

    function saveRemoteImage($url, $path)
    {
        //echo $url;
        //1974/1974_thm_2.jpg
        //$url = str_replace('_thm', '', $url);
        //$path = preg_match("![^/]+$!", $url, $m);
        //print_r($m);
        //exit;
        $req_obj = new GetHTTP;
        //echo "Image ".$url.'<br>';
        
        $req_obj->req->setUrl(strval($url));
        $response = $req_obj->req->send();

        $headers = $response->getHeader();

        if ($headers['content-type'] == 'text/html') {
            return false;
        }


        $img = $response->getBody();
        if ($img) {
            file_put_contents  ($path, $img);
            return true;
        }
        return false;
    }

    function isMain($main_image)
    {
        if (str_replace("200_150", "400_300", $main_image) == $this->file_name) {
            return true;
        }
        return false;
    }

    function insertImagesHA($villa_id, $match, $villa_obj)
    {
        unset($this->main_image);
        $this->villa_id = $villa_id;
        $path = PROJECT_ROOT.'WWW/images/'.$villa_id.'/';
        //echo $path.$name.'<br>';
        @mkdir ($path);

        foreach ($match['img'] as $k => $arr) {
            $url = $arr[0];
            preg_match("![^/]+$!", $url, $m);
            $name = $m[0].'_400_300.jpg';
            //$this->saveRemoteImage($url, $path.$name);

            if ($this->saveRemoteImage($url, $path.$name)) {
                $this->file_name = $villa_id.'/'.$name;
                $this->caption = $arr[1];
                $this->insert();
                
                if (!$this->main_image) {
                    $url_main = $match['main_img'];
                    $name = str_replace("400_300", "200_150", $name);
                    $this->main_image = $villa_id.'/'.$name;
                    $this->saveRemoteImage($url_main, $path.$name);
                    if ($villa_obj) {
                        $villa_obj->main_image = $villa_id.'/'.$name;
                        $villa_obj->update();
                    }
                }
                
            } 
        }
    }
}
