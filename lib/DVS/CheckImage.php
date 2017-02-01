<?php
/*////////////////////////////////////////////////////////////////////////////
lib2/DVS
------------------------------------------------------------------------------
Класс проверки загружаемых изображений
------------------------------------------------------------------------------
$Id: CheckImage.php 21 2010-10-28 06:25:35Z xxserg $
////////////////////////////////////////////////////////////////////////////*/

class DVS_CheckImage
{
    /* Максимальный размер файла */
    var $_maxFileSize = 2097152; // 200*1024
    /* Допустимые типы файла */
    var $_imageTypes = array(
        'image/pjpeg'   => 'jpg',
        'image/jpeg'    => 'jpg',
    );
    /* Максимальная ширина изображения */
    var $_maxImageWidth = 1024;
    /* Минимальная ширина изображения */
    var $_minImageWidth = 468;

    /* Ширина по умолчанию */
    var $_resizeWidth       = 468;
    var $_resizeSmallWidth  = 120;

    /* По умолчанию фотки не обрезать */
    var $_isCrop = false;
    /* По умолчанию маленькую картинку сохранять*/
    var $_saveSmallImage = true;

    /* Тип сохроняемой кортинки */
    var $_imageSaveType = 'jpg';

    /* Установка логотипа на фотку */
    var $_setLogo = false;

    /* Путь до логотипов */
    var $_logoPath = '';

    /* Качество картинок */
    var $_quality = 95;

    function DVS_CheckImage($options=array(NULL))
    {
        $this->initOptions($options);
    }

    /**
     * Инициализация параметров
     */
    function initOptions($options=array(NULL))
    {
        if (!is_array($options)) {
            return false;
        }
        $availableOptions = array(
            'maxFileSize',
            'imageTypes',
            'maxImageWidth',
            'minImageWidth',
            'resizeWidth',
            'resizeSmallWidth',
            'isCrop',
            'saveSmallImage',
            'imageSaveType',
            'setLogo',
            'logoPath',
            'quality',
        );
        foreach ($options as $key => $value) {
            if (in_array($key, $availableOptions)) {
                $property = '_'.$key;
                $this->$property = $value;
            }
        }
        return true;
    }

    /**
     * Проверка на превышение размера загружаемого файла
     */
    function checkFileSize($file_arr)
    {
        if ($file_arr['size'] > $this->_maxFileSize) {
            return 'Размер изображения не должен превышать '.($this->_maxFileSize / 1024).'kb!';
        }
        return '';
    }

    /**
     * Проверка на допустимый тип файла
     */
    function checkFileType($file_arr)
    {
        if (!isset($this->_imageTypes[$file_arr['type']])) {
            return 'Недопустимый тип файла! Тип файла должен быть '.(implode(',', array_unique($this->_imageTypes))).'!';
        }
        return '';
    }

    /**
     * Проверка ширины изображения
     */
    function checkImageWidth($file_arr)
    {
        list($width, $height, $type, $attr) = @getimagesize($file_arr['tmp_name']);
        if ($width > $this->_maxImageWidth) {
            return 'Ширина изображения не должна превышать '.$this->_maxImageWidth.'px!';
        }
        if ($width < $this->_minImageWidth) {
            return 'Ширина изображения не должна быть меньше '.$this->_minImageWidth.'px!';
        }
        $minHeight = $this->_getHeight($this->_minImageWidth);
        if ($this->_isCrop && $height < $minHeight) {
            return 'Высота изображения не должна быть меньше '.$minHeight.'px!';
        }
        return '';
    }

    /**
     * Применение всех правил для проверки изборажения
     */
    function checkImage($filename)
    {
        switch ($_FILES[$filename]['error']) {
            case 1:return 'Размер изображения не должен превышать '.round($this->_maxFileSize / 1024).'kb!';
            case 2:return 'Размер изображения не должен превышать '.round($this->_maxFileSize / 1024).'kb!';
            case 3:return 'Сбой при загрузке файла!';
            case 4:return '';
        }

        if (!$_FILES[$filename]['name']) {
            return '';
        }

        /* Размер файла */
        if ($error = $this->checkFileSize($_FILES[$filename])) {
            return $error;
        }
        /* Тип файла */
        if ($error = $this->checkFileType($_FILES[$filename])) {
            return $error;
        }
        /* Ширина изображения */
        if ($error = $this->checkImageWidth($_FILES[$filename])) {
            return $error;
        }
        return '';
    }

    /**
     * Проверка группы изборажений
     */
    function checkGroupImage($count, $filename)
    {
        $err_arr = array();
        for ($i = 1; $i <= $count; $i++) {
            if ($error = $this->checkImage($filename.$i)) {
                $err_arr[$filename.$i] = $error;
            }
        }
        return $err_arr;
    }

    /**
     * Проверка существования директории
     */
    function mkRecursiveDir($dir, $mode = 0775)
    {
        if (is_null($dir) || $dir === "") {
            return false;
        }
        if (is_dir($dir) || $dir === "/") {
            return true;
        }
        if ($this->mkRecursiveDir(dirname($dir), $mode, $recursive)) {
            mkdir($dir, $mode);
            chmod($dir, $mode);
            return true;
        }
        return false;
    }

    /**
     * Сохранение файла
     */
    function saveFile($path, $file_arr, $rand_name = '')
    {
        if (!$file_arr['tmp_name']) {
            return false;
        }
        /* Создадим директории */
        $this->mkRecursiveDir($path);
        /* Название файла */
        if (!$rand_name) {
            $rand_name = $this->getRandName();
        }
        /* При вводе все картинки преобразуются в указанные тип (по умолчанию jpg) */
        $imagename = $rand_name.'.'.$this->_imageSaveType;
        $fullpath  = $path.$rand_name.'.'.$this->_imageTypes[$file_arr['type']];
        /* Копирование */

        if (copy($file_arr['tmp_name'], $fullpath)) {
            /* Большая */
            $this->_convertImage($this->_resizeWidth, $fullpath, $path.$imagename);

            if ($this->_setLogo) {
                $logo_big = 'watermark-big.png';
                $this->compositeLogo($logo_big, $path.$imagename, 5, false, false);
            }

            list($width, $height, $type, $attr) = @getimagesize($path.$imagename);
            $ret['name'] = $imagename;
            $ret['size'] = $width.','.$height;

            /* Маленькая */
            if ($this->_saveSmallImage) {
                $ret['size_small'] = $this->saveSmallFile($fullpath, $path, $imagename);
            }

            /* Удалим оригинал */
            if ($path.$imagename != $fullpath) {
                unlink($fullpath);
            }

            return $ret;
        }
        return false;
    }

    /**
     * Сохраняет маленькую фотку
     */
    function saveSmallFile($fullpath, $path, $imagename)
    {
        $this->mkRecursiveDir($path.'small/');
        $this->_convertImage($this->_resizeSmallWidth, $fullpath, $path.'small/'.$imagename);

        if ($this->_setLogo) {
            $logo_small = 'watermark-small.png';
            $this->compositeLogo($logo_small, $path.'small/'.$imagename, 2, 'southeast', false);
        }

        list($width_small, $height_small, $type_small, $attr_small) = @getimagesize($path.'small/'.$imagename);

        return $width_small.','.$height_small;
    }


    /**
     * Добавление Лого на картинку
     */
    function compositeLogo($logo_arr, $image, $margin = 0, $useGravity = false, $colorAllocate = false)
    {
        if (is_array($logo_arr)) {
            $logo = $logo_arr[array_rand($logo_arr)];
        } else {
            $logo = $logo_arr;
        }

        $logo_size = getimagesize($this->_logoPath.$logo);
        $size = getimagesize($image);

        if ($useGravity) {

            $x_offset = $size[0] - $logo_size[0] - $margin;
            $y_offset = $size[1] - $logo_size[1] - $margin;

            if ($colorAllocate && is_array($logo_arr)) {
                $logo = $this->colorallocateLogo($image, $logo_arr, $x_offset, $y_offset, $logo_size[0], $logo_size[1], true);
            }

            system("/usr/local/bin/composite -gravity ".$useGravity." ".$this->_logoPath.$logo." ".$image." ".$image);
        } else {

            $x_offset = rand($margin, $size[0] - $logo_size[0] - $margin);
            $y_offset = rand($size[1] - $logo_size[1] - $margin, $size[1] - $logo_size[1]);

            if ($colorAllocate && is_array($logo_arr)) {
                $logo = $this->colorallocateLogo($image, $logo_arr, $x_offset, $y_offset, $logo_size[0], $logo_size[1], true);
            }

            system("/usr/local/bin/composite -geometry +".$x_offset."+".$y_offset." ".$this->_logoPath.$logo." ".$image." ".$image);
        }
    }

    /**
     * Получить пропорционально высоту от ширины
     */
    function _getHeight($width)
    {
        return round($width * 3 / 4);
    }

    /**
     * Получить пропорционально ширину от высоты
     */
    function _getWidth($height)
    {
        return round($height * 4 / 3);
    }

    /**
     * Конвертировать фотку
     */
    function _convertImage($to_width, $from_file, $to_file)
    {
        if ($this->_isCrop) {
            list($from_width, $from_height, $type, $attr) = @getimagesize($from_file);
            $resize = '';
            /* Узкая фотка */
            if ($from_width <= $from_height && $from_height > ($h0 = $this->_getHeight($from_width))) {
                $resize = '0x'.round(($from_height - $h0) / 2);
            }
            /* Широкая фотка */
            if ($from_width >= $from_height && $from_width > ($w0 = $this->_getWidth($from_height))) {
                $resize = round(($from_width - $w0) / 2).'x0';
            }
            /* Обрежем картинку до пропорциональных размеров */
            if ($resize) {
                system("/usr/local/bin/convert -shave ".$resize." ".$from_file." ".$to_file);
                $from_file = $to_file;
            }
        }
        system("/usr/local/bin/convert -quality ".$this->_quality." -resize ".$to_width." ".$from_file." ".$to_file);
        chmod($to_file, 0666);
    }

    function getRandName()
    {
        return substr(md5(uniqid(rand())), 0, 8);
    }

    /**
     * Удаление файла
     */
    function removeFile($path, $filename)
    {
        @unlink($path.$filename);
        @unlink($path.'small/'.$filename);
    }

    function savePathFile($file_arr, $pre_path = '')
    {
        $pre_path = $this->getPrePath($pre_path);

        /* Уникальность */
        list($full_path, $rand_name) = $this->getFileOpt($pre_path);
        while (file_exists($full_path.$rand_name.'.jpg')) {
            list($full_path, $rand_name) = $this->getFileOpt($pre_path);
        }

        return $this->saveFile($full_path, $file_arr, $rand_name);
    }

    function getPrePath($pre_path)
    {
        return $pre_path ? $pre_path : SERVER_ROOT.SERVER.'/'.HTDOCS_FOLDER.IMAGE_FOLDER;
    }

    function removePathFile($filename, $pre_path = '')
    {
        $full_path = DVS_CheckImage::getFullImagePath($filename, $pre_path);
        DVS_CheckImage::removeFile($full_path, $filename);
    }


    function getFileOpt($pre_path)
    {
        $rand_name = $this->getRandName();
        $full_path = $pre_path.$this->getImageFolderName($rand_name);
        return array($full_path, $rand_name);
    }

    function getImageFolderName($imagename)
    {
        return substr($imagename, 0, 2).'/';
    }

    function getFullImagePath($imagename, $pre_path = '')
    {
        return DVS_CheckImage::getPrePath($pre_path).DVS_CheckImage::getImageFolderName($imagename);
    }

    function getWWWImageFolder($imagename, $pre_path = '')
    {
        if (!$pre_path) {
            $pre_path = PHOTO_URL;
        }
        return $pre_path.DVS_CheckImage::getImageFolderName($imagename);
    }

}

?>
