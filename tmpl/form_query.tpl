
{qf_javascript}
	<div id="enquiry-form">
		<div class="form">
<form {qf_attributes}>

<!-- BEGIN qf_hidden_loop -->
{qf_hidden}
<!-- END qf_hidden_loop -->

<!-- BEGIN qf_main_loop -->
    
	<!-- BEGIN qf_header -->
	<div class="section">
	<div class="label"><span>{qf_header}</span></div>
	<div class="input">
	<!-- END qf_header -->
		
	
	<!-- BEGIN qf_element -->
    <div class="row">
	<label><!-- BEGIN qf_element_required --><span class="required">* </span><!-- END qf_element_required -->
	<span>{qf_label}</span>
	</label>
	<!-- BEGIN qf_element_error --><div class="field"><span style="color: #FF0000;">{qf_error}</span></div><!-- END qf_element_error -->
	<div class="field">{qf_element}</div>
	</div>
	<!-- END qf_element -->

	<!-- BEGIN qf_radio -->
    <div class="row">
	<label>
	<span>{qf_label}</span>
	</label>
	<!-- BEGIN qf_radio_error --><span style="color: #FF0000;">{qf_error}</span><br /><!-- END qf_radio_error -->
	<div class="field">{qf_element}</div>
	</div>
	<!-- END qf_radio -->

    <!-- BEGIN qf_static -->
            {qf_label}{qf_element}
    <!-- END qf_static -->

<!-- END qf_main_loop -->
</form></div>
		
		<div id="owner-contact-info" class="contact">
		</div>
		<div class="clear"></div>


</div>
</div>
</div>