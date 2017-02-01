<script type="text/javascript" src="/js/hltable.js"></script>

<style type="text/css">
/* Определяем стили для подсвечивания строк */
  .hoverRow { background-color: #f8f8f8; }
</style>


<!-- BEGIN ADD_BUTTON -->
<div align="left" style="width:96%;"><a href="{ADD_BUTTON_URL}">{ADD_BUTTON}</a></div>
<!-- END ADD_BUTTON -->

<!-- BEGIN TABLE -->
<table width=95%><tr><td>{DVS_TOTAL}: {CNT}</td><td align=right>{PAGES}</td></tr></table>

<table id="data" width=100%>
<thead>
<tr>
 <!-- BEGIN TH -->
  <th {TD_ATTR}><b>{VAL}</b></th>
 <!-- END TH -->
</tr>
</thead>
<tbody>
<!-- BEGIN ROW -->
<tr>
 <!-- BEGIN CELL -->
  <td {TD_ATTR}>{VAL}</td>
 <!-- END CELL -->

 <!-- BEGIN SORDER -->
 <td align="center" width=45>
  <table width=100% cellspacing=3 cellpadding=0>
   <tr>
    <td align=left width=50%>
     <!-- BEGIN UP_BUTTON -->
      <A HREF="{UP_BUTTON}"><img src={IMGS}up.gif width=16 height=16 border=0 alt="Вверх" title="Вверх"></A>
     <!-- END UP_BUTTON -->
    </td>
    <td align=right>
     <!-- BEGIN DOWN_BUTTON -->
      <A HREF="{DOWN_BUTTON}"><img src={IMGS}down.gif width=16 height=16 border=0 alt="Вниз" title="Вниз"></A>
     <!-- END DOWN_BUTTON -->
    </td>
   </tr>
  </table><img src={IMGSS}empty.gif width=0 height=0 border=0 alt="Вниз" title="Вниз"></td>
 <!-- END SORDER -->

 <!-- BEGIN BUTTONS -->
 <td class="acts">
   <!-- BEGIN EDIT_BUTTON --><A HREF="{EDIT_BUTTON}"><img src=/img/edit.png title='{DVS_EDIT}'></A><!-- END EDIT_BUTTON --><!-- BEGIN DELETE_BUTTON --><A HREF="{DELETE_BUTTON}" onclick="return doDelete();"><img src="/img/delete.png" title='{DVS_DELETE}'></A><!-- END DELETE_BUTTON -->
 </td>
 <!-- END BUTTONS -->
</tr>
<!-- END ROW -->
</tbody>
</table>

<table width=95%><tr><td>{DVS_TOTAL}: {CNT}</td><td align=right>{PAGES}</td></tr></table>
<!-- END TABLE -->

<!-- BEGIN NO_TABLE -->
<BR><BR><P><B>{NO_TABLE}</B></P><BR><BR><BR>
<!-- END NO_TABLE -->

<!-- BEGIN ADD_BUTTON1 -->
<div align="left" style="float:left;"><a href="{ADD_BUTTON_URL}">{ADD_BUTTON}</a></div>
<!-- END ADD_BUTTON1 -->
<!-- BEGIN DOWNLOAD_BUTTON -->
<div align="right" style="display:block;"><a href="{DOWNLOAD_BUTTON_URL}">Скачать CSV {DOWNLOAD_BUTTON}</a></div>
<!-- END DOWNLOAD_BUTTON -->

<script type="text/javascript">
//Подсветка по клику и при наведении мышки на ряд
highlightTableRows("myTable","hoverRow");

</script>