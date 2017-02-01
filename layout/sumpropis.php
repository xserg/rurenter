<?
// —умма прописью - доработал - Alexander Selifonov, as-works[at]narod.ru
// last updated: 15.01.2007
// echo SumProp(2004.30, 'руб.', 'коп.');
// SumProp(nnnn,'USD'|'RUR'|'EUR')-полный вывод со спр€жением "долларов"-"центов"
function SumProp($srcsumm,$val_rub='', $val_kop='')
{
  $cifir= Array('од','дв','три','четыр','п€т','шест','сем','восем','дев€т');
  $sotN = Array('сто','двести','триста','четыреста','п€тьсот','шестьсот','семьсот','восемьсот','дев€тьсот');
  $milion= Array('триллион','миллиард','миллион','тыс€ч');
  $anDan = Array('','','','сорок','','','','','дев€носто');
  $scet=4;
  $cifR='';
  $cfR='';
  $oboR= Array();
//==========================
  $splt = explode('.',"$srcsumm");
  if(count($splt)<2) $splt = explode(',',"$srcsumm");
  $xx = $splt[0];
  $xx1 = (empty($splt[1])? '00': $splt[1]);
  $xx1 = str_pad("$xx1", 2, "0", STR_PAD_RIGHT); // 2345.1 -> 10 копеек
//  $xx1 = round(($srcsumm-floor($srcsumm))*100);
  if ($xx>999999999999999) { $cfR=$srcsumm; return $cfR; }
  while($xx/1000>0){
     $yy=floor($xx/1000);
     $delen= round(($xx/1000-$yy)*1000);

     $sot= floor($delen/100)*100;
     $des=(floor($delen-$sot)>9? floor(($delen-$sot)/10)*10:0);
     $ed= floor($delen-$sot)-floor(($delen-$sot)/10)*10;

     $forDes=($des/10==2?'а':'');
     $forEd= ($ed==1 ? 'ин': ($ed==2?'е':'') );
     if ( floor($yy/1000)>=1000 ) { // делаю "единицы" дл€ тыс€ч, миллионов...
         $ffD=($ed>4?'ь': ($ed==1 || $scet<3? ($ed<2?'ин': ($scet==3?'на': ($scet<4? ($ed==2?'а':( $ed==4?'е':'')) :'на') ) ) : ($ed==2 || $ed==4?'е':'') ) );
     }
     else { // единицы дл€ "единиц
         $ffD=($ed>4?'ь': ($ed==1 || $scet<3? ($scet<3 && $ed<2?'ин': ($scet==3?'на': ($scet<4? ($ed==2?'а':( $ed==4?'е':'')) :'ин') ) ) : ( $ed==4?'е':($ed==2?'а':'')) ) );
     }
     if($ed==2) $ffD = ($scet==3)?'е':'а'; // два рубл€-миллиона-миллиарда, но две тыс€чи

     $forTys=($des/10==1? ($scet<3?'ов':'') : ($scet<3? ($ed==1?'': ($ed>1 && $ed<5?'а':'ов') ) : ($ed==1? 'а': ($ed>1 && $ed<5?'и':'') )) );
     $nnn = floor($sot/100)-1;
     $oprSot=(!empty($sotN[$nnn]) ? $sotN[$nnn]:'');
     $nnn = floor($des/10);
     $oprDes=(!empty($cifir[$nnn-1])? ($nnn==1?'': ($nnn==4 || $nnn==9? $anDan[$nnn-1]:($nnn==2 || $nnn==3?$cifir[$nnn-1].$forDes.'дцать':$cifir[$nnn-1].'ьдес€т') ) ) :'');

     $oprEd=(!empty($cifir[$ed-1])? $cifir[$ed-1].(floor($des/10)==1?$forEd.'надцать' : $ffD ) : ($des==10?'дес€ть':'') );
     $oprTys=(!empty($milion[$scet]) && $delen>0) ? $milion[$scet].$forTys : '';

     $cifR= (strlen($oprSot) ? ' '.$oprSot:'').
       (strlen($oprDes)>1 ? ' '.$oprDes:'').
       (strlen($oprEd)>1  ? ' '.$oprEd:'').
       (strlen($oprTys)>1 ? ' '.$oprTys:'');
     $oboR[]=$cifR;
     $xx=floor($xx/1000);
     $scet--;
     if (floor($xx)<1 ) break;
  }
  $oboR = array_reverse($oboR);
  for ($i=0; $i<count($oboR); $i++){
      $probel = strlen($cfR)>0 ? ' ':'';
      $cfR .= (($oboR[$i]!='' && $cfR!='') ? $probel:'') . $oboR[$i];
  }
  if (strlen($cfR)<3) $cfR='ноль';

  $intsrc = $splt[0];
  $kopeiki = $xx1;
  $kop2 =str_pad("$xx1", 2, "0", STR_PAD_RIGHT);

  $sum2 = str_pad("$intsrc", 2, "0", STR_PAD_LEFT);
  $sum2 = substr($sum2, strlen($sum2)-2); // 676571-> '71'
  $sum21 = substr($sum2, strlen($sum2)-2,1); // 676571-> '7'
  $sum22 = substr($sum2, strlen($sum2)-1,1); // 676571-> '1'
  $kop1  = substr($kop2,0,1);
  $kop2  = substr($kop2,1,1);
  $ar234 = array('2','3','4'); // доллар-ј, рубл-я...
  // делаю спр€жени€ у слова рубл-ей|€|ь / доллар-ов... / евро
  if($val_rub=='RUR') {
    $val1 = 'рубл';
    $val2 = 'копейка';
    if($sum22=='1' && $sum21!='1') $val1 .= 'ь'; // 01,21...91 рубль
    elseif(in_array($sum22, $ar234) && ($sum21!='1')) $val1 .= '€';
    else $val1 .= 'ей';

    if(in_array($kop2, $ar234) && ($kop1!='1')) $val2 = 'копейки';
    elseif($kop2=='1' && $kop1!='1') $val2 = 'копейка'; // 01,21...91 копейка
    else $val2 = 'копеек';
    $cfR .= " $val1 $kopeiki $val2";
  }
  elseif($val_rub=='USD') {
    $val1 = 'доллар';
    $val2 = 'цент';
    if($sum22=='1' && $sum21!='1') $val1 .= ''; // 01,21...91 доллар
    elseif(in_array($sum22, $ar234) && ($sum21!='1')) $val1 .= 'a';
    else $val1 .= 'ов';

    if($kop2=='1' && $kop1!='1') $val2 .= ''; // 01,21...91 цент
    elseif(in_array($kop2, $ar234) && ($kop1!='1')) $val2 .= 'a';
    else $val2 .= 'ов';
    $val1 .= ' —Ўј';
    $cfR .= " $val1 $kopeiki $val2";
  }
  elseif($val_rub=='EUR') {
    $val1 = 'евро';
    $val2 = 'цент';
    if($kop2=='1' && $kop1!='1') $val2 .= ''; // 01,21...91 цент
    elseif(in_array($kop2, $ar234) && ($kop1!='1')) $val2 .= 'a';
    else $val2 .= 'ов';

    $cfR .= " $val1 $kopeiki $val2";
  }
  else {
    $cfR .= ' '.$val_rub;
    if($val_kop!='') $cfR .= " $kopeiki $val_kop";
  }
  return $cfR;
} // SumProp() end
?>