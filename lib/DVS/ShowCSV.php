<?php
/*////////////////////////////////////////////////////////////////////////////
lib/DVS
------------------------------------------------------------------------------
Класс создания CVS данных
------------------------------------------------------------------------------
$Id: ShowCSV.php 21 2010-10-28 06:25:35Z xxserg $
////////////////////////////////////////////////////////////////////////////*/

require_once COMMON_LIB.'DVS/Dynamic.php';

class DVS_ShowCSV extends DVS_Dynamic
{
    function getPageData()
    {
        $this->db_obj->setFrom($_GET);
        if (method_exists($this->db_obj, 'preGenerateList')) { 
            $this->db_obj->preGenerateList();
        }
        $this->db_obj->find();
        $this->setHeaders($this->db_obj->__table);
        echo $this->tableHeaders();
        while ($this->db_obj->fetch()) {
            $row1 = array();
            //$row = $this->db_obj->tableRow();
            if (method_exists($this->db_obj, 'tableRow')) {
                $row = $this->db_obj->tableRow();
            } else {
                //$row = method_exists($this->db_obj, 'tableRow') ? $this->db_obj->tableRow() : $this->db_obj->ToArray();
                $row = array_intersect_key($this->db_obj->ToArray(), $this->db_obj->listLabels);
            }
            foreach ($this->db_obj->listLabels as $k => $v) {
                $row1[] = $row[$k];
            }
            //print_r($row);
            echo strip_tags(implode(';', $row1))."\n";
        }
        exit;
    }

    function tableHeaders()
    {
        foreach ($this->db_obj->listLabels as $k => $v) {
            $headers[] = $this->db_obj->fb_fieldLabels[$k];
        }
        return implode(';', $headers)."\n";
    }

    function setHeaders($file)
    {
        //header("Pragma: public");
        header('Content-Type: text/csv');
        //header('Content-Type: application/vnd.ms-excel');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        //header("Content-Description: downloaded from iunu.net as an example");
        header('Content-Disposition: attachment; filename="'.$file.'.csv"');
    }


}
?>
