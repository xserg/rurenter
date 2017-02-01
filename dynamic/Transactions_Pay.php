<?php
/**
 * Входящий платеж
 * @package rurenter
 * $Id: Transactions_Pay.php 89 2011-02-06 11:39:12Z xxserg $
 */

require_once COMMON_LIB.'DVS/Dynamic.php';

define('DVS_ERROR_PAY', 'Недостаточная сумма на счету!');

class Project_Transactions_Pay extends DVS_Dynamic
{
    // Права
    public $perms_arr = array('ar' => 1);

    function getPageData()
    {   
        //DB_DataObject::DebugLevel(1);
        $campaign_id = DVS::getVar('campaign_id', 'int');
        $campaigns_obj = DB_DataObject::factory('campaigns');
        $campaigns_obj->get($campaign_id);
        if (!$campaigns_obj->N) {
            $this->show404();
            $this->nocache = true;
        }

        unset($this->db_obj->campaign_id);
        $this->db_obj->query('BEGIN');
        $transaction_id = $this->db_obj->campaignTransaction($campaigns_obj);
        if ($transaction_id < 0) {
            $this->msg = 'ERROR_PAY';
        } else {
            $old_campaigns_obj = $campaigns_obj->clone();
            $campaigns_obj->status_id = 3;
            $result = $campaigns_obj->update($old_campaigns_obj);
            if ($result) {
                $this->msg = 'UPDATE_ROW';
                $this->db_obj->query('COMMIT');
            }
        }
        //$this->db_obj->qs = '?op=campaigns&act=show&cid='.$this->db_obj->campaign_id;
        $this->db_obj->qs = '?op=campaigns&advertiser_id='.$this->db_obj->advertiser_id;
        $this->goLocation();
    }


}

?>