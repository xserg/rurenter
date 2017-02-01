<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
<meta http-equiv="Content-Language" content="ru" />
<title>Квитанция</title>        

<style type="text/css">
<!--
P {
	font-family: Arial, Helvetica, sans-serif; font-size: 9pt;
	}
TD {
	font-family: Arial, Helvetica, sans-serif; font-size: 9pt;
	}
LI {
	font-family: Arial, Helvetica, sans-serif; font-size: 9pt;
	}
-->
</style>
</head>
<body>
<table border="1" bordercolor="black" cellpadding="3" cellspacing="0" width="640">
  <tbody>
	<!-- BEGIN KVIT -->
    <tr>
      <td align="left" valign="middle" width="240">   <b>{kvit_title}</b> <br />
          <br />
          <br />
          <br />
          <br />
          <br />
          <br />
          <br />
          <br />
          <br />
          <br />
          <br />
          <br />
          <br />
          <br />
          Кассир<br />
      </td>
      <td align="right" valign="middle" width="400"><table border="1" bordercolor="black" cellpadding="3" cellspacing="0" width="410">
          <tbody>
            <tr>
              <td colspan="3">Получатель платежа: {firm_name} ИНН: {inn}<br />
                р/с: {bank_rs} в {bank_name} КПП: {kpp} <br />
                к/с {bank_ks}, БИК {bank_bik}
                <br />
                <!-- БИК: {bank_bik} КБК: {bank_kbk} <br />
                ОКАТО {bank_okato}<br /> -->
              </td>
            </tr>
            <tr>
              <td colspan="3"><p>{last_name} {first_name} {patr_name} 
                </p>
                  <hr noshade="noshade" size="1" width="95%" /> 
                  <br />{address}
                  <hr noshade="noshade" size="1" width="95%" />
                  <small><small>
                  <div style="" sans-serif="" font-size="" 8pt="" align="center">фамилия, и. о., адрес</div>
                </small></small> </td>
            </tr>
            <tr>
              <td align="center">Вид платежа</td>
              <td align="center">Дата</td>
              <td align="center">Сумма</td>
            </tr>
            <tr>
              <td align="left" width="150"><br />
                           {purpose}<br />
                <br />
              </td>
              <td width="100"> </td>
              <td width="150" align="center"> {ammount} р. </td>
            </tr>
            <tr>
              <td rowspan="2" colspan="3" align="left" valign="center"><br />
                Плательщик:</td>
            </tr>
          </tbody>
      </table></td>
    </tr>
	<!-- END KVIT -->
  </tbody>
</table>
</body>
</html>
