<?php
/**
 * Table Definition for loginza
 ���� 	��������
identity 	���������� ������������� ����������������� ������������. �������� ������������ � ���� URL. ��� �������� ������ �������������� ���������� ������, ��� � ��������� �����������.
provider 	����������� ����������, ����� ������� ������ �����������.
uid 	������������� ������������, ������������ �� ������� ����������.
nickname 	��� ������������ ��� ��� �����.
email 	����� ����������� �����. ������ � email � ��������� ����������� ����� ���� �� ��������� �� ��������, ������� ����������� ������ ����� �������� ��������������.
gender 	��� ������������. ��������� ��������: M, F. ��������������, ������� � �������.
photo 	URL-����� �� ���� ��������. ������ � ������ �����������, ��� ��������� �����������, ����� ����������.
name 	������ ���������� ������ ��� �� �������.
full_name 	��� � ������� ������������.
first_name 	���
last_name 	�������
middle_name 	��������
dob 	���� ��������. ������: ����-��-��. �������� ����, ��� ��� ������ ����� ���� �� ������� � ������� ������������. ����������� ������������� ����� ���� �������� ����� ��������� ������ (��������: 0000-12-31).
work 	������ ������ � ����� ������ � ���������.
company 	�������� ��������.
job 	��������� ��� ���������.
address 	������ ������ � �������� ������ � ����� ����� ������:
home 	������ � ������� � �������� ������ (�������� ������: country, postal_code, state, city, street_address).
business 	������ � ������� � ������� ������ (�������� ������: country, postal_code, state, city, street_address).
phone 	������ ���������� ������ �� ��������� � ������� ���������. ��������� � ������� ��������� ������ ���� phone.
preferred 	����� �������� ��������� �� ���������.
home 	�������� �������.
work 	������� �������.
mobile 	��������� �������.
fax 	����.
im 	������ � ���������� ��� �����.
icq 	����� ICQ ��������.
jabber 	Jabber �������.
skype 	Skype �������.
web 	������ ���������� ������ ������ ������������.
default 	����� ������� ��� ������������ ��������.
blog 	����� �����
language 	����.
timezone 	��������� ����.
biography 	������ ���������� � ������������ � ��� ���������.
 */
require_once 'DB/DataObject.php';

class DBO_Loginza extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'loginza';                         // table name
    public $_database = 'rurenter';                         // database name (used with database_{*} config)
    public $id;                              // int(4)  primary_key not_null
    public $identity;                        // varchar(255)  unique_key not_null
    public $provider;                        // varchar(255)  
    public $nickname;                        // varchar(255)  
    public $email;                           // varchar(255)  
    public $status;                          // int(4)  
    public $user_id;                         // int(4)  
    public $first_name;                      // varchar(255)  
    public $last_name;                       // varchar(255)  
    public $middle_name;                     // varchar(255)  
    public $photo;                           // varchar(255)  
    public $regdate;                         // datetime()  
    public $lastdate;                        // datetime()  

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DBO_Loginza',$k,$v); }

    function table()
    {
         return array(
             'id' =>  DB_DATAOBJECT_INT + DB_DATAOBJECT_NOTNULL,
             'identity' =>  DB_DATAOBJECT_STR + DB_DATAOBJECT_NOTNULL,
             'provider' =>  DB_DATAOBJECT_STR,
             'nickname' =>  DB_DATAOBJECT_STR,
             'email' =>  DB_DATAOBJECT_STR,
             'status' =>  DB_DATAOBJECT_INT,
             'user_id' =>  DB_DATAOBJECT_INT,
             'first_name' =>  DB_DATAOBJECT_STR,
             'last_name' =>  DB_DATAOBJECT_STR,
             'middle_name' =>  DB_DATAOBJECT_STR,
             'photo' =>  DB_DATAOBJECT_STR,
             'regdate' =>  DB_DATAOBJECT_STR + DB_DATAOBJECT_DATE + DB_DATAOBJECT_TIME,
             'lastdate' =>  DB_DATAOBJECT_STR + DB_DATAOBJECT_DATE + DB_DATAOBJECT_TIME,
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


    function createIdentity($authresult)
    {
        $this->identity = $authresult['identity'];
        $this->provider = $authresult['provider'];
        $this->nickname = iconv("UTF-8", "Windows-1251", $authresult['nickname']);
        $this->email = $authresult['email'];
        $this->photo = $authresult['photo'];
        if (is_array($authresult['name'])) {
            $this->first_name = $authresult['name']['first_name'];
            $this->last_name = $authresult['name']['last_name'];
            $this->middle_name = $authresult['name']['middle_name'];
        }
        $this->regdate = date("Y-m-d H:i");
        return $this->insert();
    }

}
