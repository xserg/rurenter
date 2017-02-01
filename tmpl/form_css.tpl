{qf_javascript}
<div class="ff"><div class="ff1"><div class="ff2"><div class="ff3" id="formBlock">
<form {qf_attributes}>
<!-- BEGIN qf_hidden_loop -->
{qf_hidden}
<!-- END qf_hidden_loop -->

<fieldset>
<!-- BEGIN qf_main_loop -->
    <!-- BEGIN qf_header --><h2 class="no first-child">{qf_header}</h2><!-- END qf_header -->
	<!-- BEGIN qf_element -->
    <p class="row"><label><!-- BEGIN qf_element_required --><span style="color: #FF0000;">* </span><!-- END qf_element_required -->{qf_label}</label>
	<!-- BEGIN qf_element_error --><span style="color: #FF0000;">{qf_error}</span><br /><!-- END qf_element_error -->{qf_element}</p>
	<!-- END qf_element -->
	<!-- BEGIN file_up -->
	<script type="text/javascript">
	$(document).ready(function(){
		$("#file_up").jqUploader({
		background: "efefef", barColor: "FF00FF",
		allowedExt:     "*.avi; *.flv",
		allowedExtDescr: "movies (*.avi; *.flv;)",
		src: '/js/jqUploader/jqUploader.swf',
		cls: 'f-file',
		width: 480,
		height: 60,
		elementType: 'p',
		startMessage: 'Файл',
		errorSizeMessage: 'Ошибка',
		validFileMessage: 'Загружено',
		progressMessage: 'Загружено:',
		endMessage: 'Загрузка завершена',
		});
	});
	</script>
    <p class="row">
	<label><span style="color: #FF0000;">* </span>{qf_label}</label>
	<!-- BEGIN file_up_error --><span style="color: #FF0000;">{qf_error}</span><br /><!-- END file_up_error -->{qf_element}
	</p>
	<!-- END file_up -->
    <!-- BEGIN qf_group -->
	<p class="row"><!-- BEGIN qf_group_required --><span style="color: #FF0000;">* </span><!-- END qf_group_required --> <b>{qf_group_label} &nbsp;</b> <span>
            <!-- BEGIN qf_group_error --><span style="color: #FF0000;">{qf_error}</span><br /><!-- END qf_group_error -->
            <!-- BEGIN qf_group_loop -->
                <!-- BEGIN qf_group_element -->{qf_separator}{qf_element} &nbsp; {qf_label}<!-- END qf_group_element -->
            <!-- END qf_group_loop -->
	</span></p>
    <!-- END qf_group -->
	<!-- BEGIN qf_group_cat -->
		<!-- BEGIN qf_group_cat_required --><span style="color: #FF0000;">* </span><!-- END qf_group_cat_required -->
	    <script type="text/javascript">
        function textCounter(field, countfield, maxlimit) {
        if (field.value.length > maxlimit) // if too long...trim it!
        field.value = field.value.substring(0, maxlimit);
        // otherwise, update 'characters left' counter
        else 
        countfield.value = maxlimit - field.value.length;
        }
        </script>
        <b>{qf_group_label} </b>
		<div class="oops">
			<ul class="optlist co">
			<!-- BEGIN qf_group_cat_loop -->
			<li><!-- BEGIN qf_group_cat_element --> {qf_element}{qf_label}</li>{qf_separator}<!-- END qf_group_cat_element -->
			<!-- END qf_group_cat_loop -->
			</ul>
			<ul class="optlist las"></ul>
		</div>
	<!-- END qf_group_cat -->
	<!-- BEGIN adult -->
		<ul class="optlist las">
			<li>{qf_element}<strong>Категория "16+"</strong><br /></li>
			Файл содержит эротику, нецензурную лексику, сцены насилия и жестокости
		</ul><BR><BR>
	<!-- END adult -->
	<!-- BEGIN image -->
		<p class="row"><label>{qf_label}</label><img src={qf_element}></p>
	<!-- END image -->
	<!-- BEGIN license -->
		<p class="row license"><label>{qf_label}</label></p>
		<div class="text"><p><strong>{qf_element}</strong></p></div>
	<!-- END license -->
	<!-- BEGIN agree -->
		<p class="row agree"><label>{qf_label}</label>{qf_element}</p>
	<!-- END agree -->
<!-- END qf_main_loop -->
	</fieldset>
</form>
</div></div></div></div>
	<!-- BEGIN file_up2 -->
	<script type="text/javascript">
	    function doUpload()
		{
			f = document.forms.vupload_form;
			if( f.label.value == "" )
			{
				alert("Заполните поле Название!"); return;
			}
						
			if( f.tags.value == "" )
			{
				alert("Заполните поле теги!"); return;
			}
			
			error = "Выберите категорию!";
			for (i=0;i<f.cat_id.length;i++) {
				if (f.cat_id[i].checked) {
					error = '';
				}
			}
			if (error != '')
			{
				alert(error); return;
			}
			
			f.submit();
			document.getElementById("formBlock").style.display = "none";
			document.getElementById("progressBlock").style.display = "block";
		}
	</script>
	<div align="left" id="progressBlock" style="display: none;">
		<strong>{qf_label}...</strong>
		<br/>
		<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0" width="200" height="12" id="uploading" align="middle">
		<param name="allowScriptAccess" value="sameDomain" />
		<param name="movie" value="/img/uploading.swf" />
		<param name="quality" value="high" />
		<param name="bgcolor" value="#ffffff" />
		<embed src="/img/uploading.swf" quality="high" bgcolor="#ffffff" width="200" height="12" name="uploading" align="middle" allowScriptAccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
		</object>
		<br>&nbsp;<br>
	</div>
	<!-- END file_up2 -->