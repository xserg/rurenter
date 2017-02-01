<?php
/*////////////////////////////////////////////////////////////////////////////
lib/DVS
------------------------------------------------------------------------------
 ласс создани€ карточки
------------------------------------------------------------------------------
$Id: ShowCard.php 21 2010-10-28 06:25:35Z xxserg $
////////////////////////////////////////////////////////////////////////////
*/

require_once COMMON_LIB.'DVS/Dynamic.php';

class DVS_ShowCard extends DVS_Dynamic
{
    function getPageData()
    {
        $this->createTemplateObj();
        if (method_exists($this->db_obj, 'preGenerateCard')) { 
            $this->db_obj->preGenerateCard();
        }
        $this->setCenterTitle();
        $page_arr['CENTER_TITLE'] = $this->db_obj->center_title;
        //$page_arr['CENTER'] = $this->buildCard();
        $page_arr['CENTER'] = $this->render($this->db_obj);
        return $page_arr;
    }

        
    public function render($db_obj)
    {
        $this->template_obj->loadTemplateFile('card.tmpl',1,1);
        if (isset($db_obj->card_fields)) {
            $fields = $db_obj->card_fields;
        } else {
            $fields = $db_obj->toArray();
        }

        if (is_array($db_obj->fieldsToRender)) {
            unset($fields);
            foreach ($db_obj->fieldsToRender as $field) {
                $fields[$field] = $db_obj->$field;
            }
        }

        foreach ($fields as $key=>$val) {
            if ($db_obj->card_fields[$key] && is_array($db_obj->card_fields[$key])) {
                if (in_array($db_obj->card_fields[$key][0], $this->other_blocks)) {
                    $this->template_obj->setVariable(array($db_obj->card_fields[$key][0] => $db_obj->card_fields[$key][1]));
                } else {
                    $this->template_obj->setVariable(array('KEY' => $db_obj->card_fields[$key][0], 'VAL' => $db_obj->card_fields[$key][1]));
                }
            } else {
                $this->template_obj->setVariable(array('KEY' => ($db_obj->fb_fieldLabels[$key] ? $db_obj->fb_fieldLabels[$key] : $key), 'VAL' => $val));
            }
            $this->template_obj->parse('ROW');
        }
        if ($db_obj->show_edit) {
            $id_name = DVS_Dynamic::getPrimaryKey($db_obj);
            $this->template_obj->setVariable(array('LINK' => $db_obj->qs.'&act=edit&id='.$db_obj->$id_name, 'LINK_NAME' => '–едактировать'));
        }
        return $this->template_obj->get();
    }

    
    /**
     * «аголовок страницы карточки по умолчанию
     * ¬ыводит название записи (поле 'name' по умолчанию, или поле заданное свойством 'title_name')
     * ¬ыводит ссылку на список записей
     */
    public function setCenterTitle()
    {
        if (!isset($this->db_obj->title_name)) {
            if ($this->db_obj->name) {
                $this->db_obj->title_name = 'name';
            }
        }
        if ($this->db_obj->center_title) {
            $this->db_obj->center_title = '<A HREF="'.$this->db_obj->qs.'&act=list">'.$this->db_obj->center_title.'</A> / ';
        }
        if ($this->db_obj->title_name) {
            $this->db_obj->center_title .= $this->db_obj->{$this->db_obj->title_name};
        }
    }
}
?>