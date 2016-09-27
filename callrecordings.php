<?php

	###################################################
	### Name: callrecordings.php 					###
	### Functions: Display, Search and Filter  		###
	### Copyright: GOAutoDial Ltd. (c) 2011-2016	###
	### Version: 4.0 								###
	### Written by: Alexander Jim H. Abenoja		###
	### License: AGPLv2								###
	###################################################

	require_once('./php/UIHandler.php');
	require_once('./php/CRMDefaults.php');
    require_once('./php/LanguageHandler.php');
    include('./php/Session.php');

	$ui = \creamy\UIHandler::getInstance();
	$lh = \creamy\LanguageHandler::getInstance();
	$user = \creamy\CreamyUser::currentUser();
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title><?php $lh->translateText("call_recordings"); ?></title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>

   		<!-- Call for standardized css -->
   		<?php print $ui->standardizedThemeCSS();?>

        <!-- DATA TABLES -->
        <link href="css/datatables/dataTables.bootstrap.css" rel="stylesheet" type="text/css" />
		<!-- Bootstrap Player -->
		<link href="css/bootstrap-player.css" rel="stylesheet" type="text/css" />
        <?php print $ui->creamyThemeCSS(); ?>

        <!-- Datetime picker -->
		<link rel="stylesheet" href="theme_dashboard/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css">

        <!-- Data Tables -->
        <script src="js/plugins/datatables/jquery.dataTables.js" type="text/javascript"></script>
        <script src="js/plugins/datatables/dataTables.bootstrap.js" type="text/javascript"></script>

        <!-- Date Picker -->	
        <script type="text/javascript" src="theme_dashboard/eonasdan-bootstrap-datetimepicker/build/js/moment.js"></script>
		<script type="text/javascript" src="theme_dashboard/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>

		<!-- CHOSEN-->
   		<link rel="stylesheet" href="theme_dashboard/chosen_v1.2.0/chosen.min.css">
   		<!-- SELECT2-->
   		<link rel="stylesheet" href="theme_dashboard/select2/dist/css/select2.css">
   		<link rel="stylesheet" href="theme_dashboard/select2-bootstrap-theme/dist/select2-bootstrap.css">

		<style>
		/*
		* CUSTOM CSS for disable function
		*/
			.c-checkbox input[type=checkbox]:disabled + span,
			.c-radio input[type=checkbox]:disabled + span,
			.c-checkbox input[type=radio]:disabled + span,
			.c-radio input[type=radio]:disabled + span {
				border-color: none !important;
    			background-color: none !important;
			}
			.c-checkbox input[type=checkbox]:checked + span,
			.c-radio input[type=checkbox]:checked + span,
			.c-checkbox input[type=radio]:checked + span,
			.c-radio input[type=radio]:checked + span
			 {
				border-color: #3f51b5 !important;
    			background-color: #3f51b5 !important;
			}
		</style>
    </head>
    <?php print $ui->creamyBody(); ?>
        <div class="wrapper">
        <!-- header logo: style can be found in header.less -->
		<?php print $ui->creamyHeader($user); ?>
            <!-- Left side column. contains the logo and sidebar -->
			<?php print $ui->getSidebar($user->getUserId(), $user->getUserName(), $user->getUserRole(), $user->getUserAvatar()); ?>

            <!-- Right side column. Contains the navbar and content of the page -->
            <aside class="right-side content-wrapper">
                <!-- Content Header (Page header) -->
                <section class="content-header content-heading">
                    <h1>
                        <?php $lh->translateText("call_recordings"); ?>
                        <small><?php $lh->translateText("call_recordings"); ?></small>
                    </h1>
                    <ol class="breadcrumb">
                        <li><a href="./index.php"><i class="fa fa-phone"></i> <?php $lh->translateText("home"); ?></a></li>
                       <li><?php $lh->translateText("telephony"); ?></li>
						<li class="active"><?php $lh->translateText("call_recordings"); ?>
                    </ol>
                </section>
<?php
$callrecs = $ui->API_getListAllRecordings();
?>
                <!-- Main content -->
                <section class="content">
                	<div class="row">
						<div class="col-lg-9">
							<div class="form-group mb-xl">
								<div class="has-clear">
									<input type="text" placeholder="Search Phone Number, First or Last Name" id="search" class="form-control mb">
									<span class="form-control-clear fa fa-close form-control-feedback"></span>
								</div>
								<div class="clearfix">
									<button type="button" class="pull-left btn btn-default" id="search_button"> Search</button>
									<div class="pull-right">
										<label class="checkbox-inline c-checkbox" for="search_recordings">
											<input id="search_recordings" name="table_filter" type="checkbox" checked disabled>
											<span class="fa fa-check"></span> Recordings
										</label>
									</div>
								</div>
							</div>
		                	<div class="panel panel-default">
								<div class="panel-body">
									
									<div class="callrecordings_div">
									<!-- Call Recordings panel tab -->
										<legend>Call Recordings</legend>

										<!--==== Call Recordings ====-->
										<table class="table table-striped table-bordered table-hover" id="table_callrecordings">
										   <thead>
											  <tr>
												 <th nowrap>Date</th>
												 <th nowrap class='hide-on-low'>Customer</th>
												 <th nowrap class='hide-on-low'>Phone Number</th>
												 <th nowrap class='hide-on-medium hide-on-low'>Agent</th>
												 <th nowrap class='hide-on-medium hide-on-low'>Duration</th>
												 <th nowrap>Action</th>
											  </tr>
										   </thead>
										   <tbody>
											   	<?php
											   		for($i=0;$i < count($callrecs->list_id);$i++){

												   		$d1 = strtotime($callrecs->start_last_local_call_time[$i]);
														$d2 = strtotime($callrecs->end_last_local_call_time[$i]);

														$diff = abs($d2 - $d1);

														$action_Call = $ui->getUserActionMenuForCallRecording($callrecs->uniqueid[$i], $callrecs->location[$i], $callrecs->full_name[$i]);
														//echo $callrecs->result;
											   	?>
														<tr>
															<td nowrap><?php echo $callrecs->end_last_local_call_time[$i];?></td>
															<td nowrap class='hide-on-low'><?php echo $callrecs->full_name[$i];?></td>
															<td nowrap class='hide-on-low'><?php echo $callrecs->phone_number[$i];?></td>
															<td nowrap class='hide-on-medium hide-on-low'><?php echo $callrecs->users[$i];?></td>
															<td nowrap class='hide-on-medium hide-on-low'><?php echo gmdate('H:i:s', $diff); ?></td>
															<td nowrap><?php echo $action_Call;?></td>
														</tr>
												<?php
													}
												?>
										   </tbody>
										</table>
									</div>
			               		</div><!-- /.body -->
		               		</div><!-- /.panel -->
	               		</div><!-- /.col-lg-9 -->
<?php
$agents = $ui->API_goGetAllUserLists();
?>
	               		<div class="col-lg-3">
	           				<h3 class="m0 pb-lg">Filters</h3>
	           				<form id="search_form">

		                        <div class="form-group">
		                           <label>Add Filters:</label>
		                           <div class="mb">
			                            <div class="add_callrecording_filters">
			                              	<select multiple="multiple" class="select2-3 form-control add_filters2" style="width:100%;">
		                                    	<option value="filter_agent" class="contacts_filters" >Agent </option>
			                             	</select>
			                            </div>
		                           </div>
		                        </div>

							<!-- CALL RECORDINGS FILTER -->
		                    <div class="all_callrecording_filters">
		                        <div class="callrecordings_filter_div">
		                        	<div class="agent_filter_div" style="display:none;">
										<div class="form-group">
											<label>Agent: </label>
											<div class="mb">
												<select name="agent_filter" id="agent_filter" class="form-control">
													<option value="" selected DISABLED> -- SELECT AN AGENT -- </option>
													<?php
														for($i=0; $i < count($agents->user_id);$i++){
															echo '<option value="'.$agents->user[$i].'"> '.$agents->full_name[$i].' </option>';
														}
													?>
												</select>
											</div>
										</div>
									</div>
		               				<div class="form-group">
			               				<label>Start Date:</label>
							            <div class="form-group">
							                <div class='input-group date' id='datetimepicker3'>
							                    <input type='text' class="form-control" id="start_filterdate" placeholder="<?php echo date("m/d/Y H:i:s ");?>"/>
							                    <span class="input-group-addon">
							                        <!-- <span class="glyphicon glyphicon-calendar"></span>-->
													<span class="fa fa-calendar"></span>
							                    </span>
							                </div>
							            </div>
							        </div>
							        <div class="form-group">
							            <label>End Date:</label>
							            <div class="form-group">
							                <div class='input-group date' id='datetimepicker4'>
							                    <input type='text' class="form-control" id="end_filterdate" placeholder="<?php echo date("m/d/Y H:i:s");?>" value="<?php echo date("m/d/Y H:i:s");?>"/>
							                    <span class="input-group-addon">
							                        <!-- <span class="glyphicon glyphicon-calendar"></span>-->
							                        <span class="fa fa-calendar"></span>
							                    </span>
							                </div>
							            </div>
								    </div>
								</div>
							</div>

							<fieldset>
							    <!--
							    <div class="campaign_filter_div" style="display:none;">
								    <div class="form-group">
										<label>Campaign: </label>
										<div class="mb">
											<select name="campaign_filter" class="form-control">
												<?php
												/*
													for($i=0; $i < count($campaign->campaign_id);$i++){
														echo "<option value='".$campaign->campaign_id[$i]."'> ".$campaign->campaign_name[$i]." </option>";
													}
												*/
												?>
											</select>
										</div>
									</div>
								</div>
								-->
							</fieldset>

							</form>
						    <!--<button type="button" class="pull-left btn btn-default" id="search_button">Apply</button>-->
	           			</div><!-- ./filters -->
           			</div><!-- /. row -->
                </section><!-- /.content -->
            </aside><!-- /.right-side -->
        </div><!-- ./wrapper -->

	<!-- Modal -->
	<div id="call-playback-modal" class="modal fade" role="dialog">
	  <div class="modal-dialog">

	    <!-- Modal content-->
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal">&times;</button>
	        <h4 class="modal-title"><b>Call Recording Playback</b></h4>
	      </div>
	      <div class="modal-body">

	      	<div class="play_avatar" style="padding: 20px 35%;"></div>
	      	<br/>
			<div class="audio-player" style="width:100%"></div>
		      	<!-- <audio controls>
				<source src="http://www.w3schools.com/html/horse.ogg" type="audio/ogg" />
				<source src="http://www.w3schools.com/html/horse.mp3" type="audio/mpeg" />
				<a href="http://www.w3schools.com/html/horse.mp3">horse</a>
			</audio> -->
	      </div>
	      <div class="modal-footer">
		<a href="" class="btn btn-primary download-audio-file" download>Download File</a>
	        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	      </div>
	    </div>
	    <!-- End of modal content -->
	  </div>
	</div>
	<!-- End of modal -->

		<?php print $ui->standardizedThemeJS();?>

		<!-- CHOSEN-->
   		<script src="theme_dashboard/chosen_v1.2.0/chosen.jquery.min.js"></script>
   		<!-- SELECT2-->
   		<script src="theme_dashboard/select2/dist/js/select2.js"></script>

		<script type="text/javascript">

			$(document).ready(function() {

				$('body').on('keypress', '#search', function(args) {
				    if (args.keyCode == 13) {
				        $("#search_button").click();
				        return false;
				    }
				});

				var init_callrecs_table = $('#table_callrecordings').DataTable({
                	"bDestroy" : true
                });

				// initialize single selecting
				$('.select2-1').select2({
			        theme: 'bootstrap'
			    });
			    // initialize multiple selecting
				$('.select2-3').select2({
			        theme: 'bootstrap'
			    });

				// limits checkboxes to single selecting
				$("input:checkbox").on('click', function() {
				  var $box = $(this);
				  if ($box.is(":checked")) {
				    var group = "input:checkbox[name='" + $box.attr("name") + "']";
				    $(group).prop("checked", false);
				    $box.prop("checked", true);
				  } else {
				    $box.prop("checked", false);
				  }
				});

				/****
				** Change between Contacts and Recordings
				****/
					// shows call recordings datatable if Recordings tickbox is checked
					$(document).on('change','#search_recordings',function() {
		            	$("#search_recordings").prop("disabled", true);

						if($('#search_recordings').is(":checked")){

							$(".callrecordings_div").show(); // show recordings table
							$(".callrecordings_filter_div").show(); // show recording filter

							$(".all_callrecording_filters").show(); // show filters
							$(".add_callrecording_filters").show(); // enable add filter

		            	}else{
		            		$(".callrecordings_div").hide();
		            		$(".all_callrecording_filters").hide();
		            		$(".add_callrecording_filters").hide(); // disable add filter
		            	}

					});

				/***
				** Add Filters
				***/
					// add filters

				    $(".add_filters2").change(function(){

						$(".agent_filter_div").fadeIn("slow")[ $.inArray('filter_agent', $(this).val()) >= 0 ? 'show' : 'hide' ]();

				    }).change();

				/****
				** Call Recording filters
				****/

					// ---- DATETIME PICKER INITIALIZATION

						$('#datetimepicker3').datetimepicker({
							format: 'MM/DD/YYYY'
						});

		                $('#datetimepicker4').datetimepicker({
		                	useCurrent: false,
                    		format: 'MM/DD/YYYY'
		                });

	                // ---- DATE FILTERS

		                $("#datetimepicker3").on("dp.change", function(e) {
		                	$('#datetimepicker4').data("DateTimePicker").minDate(e.date);

		                	if($('#search').val() == ""){
		                		$('#search_button').attr("disabled", false);
		                		$('#search_button').text("Searching...");
		                	}else{
			                	$('#search_button').text("Searching...");
			                	$('#search_button').attr("disabled", true);
		                	}

		                	if($('#agent_filter').is(':visible')) {
							    var agent_filter_val = $('#agent_filter').val();
							}else{
								var agent_filter_val = "";
							}
							
		            		var start_filterdate_val = $('#start_filterdate').val();
		                	var end_filterdate_val = $('#end_filterdate').val();

		            		$.ajax({
							    url: "filter_callrecs.php",
							    type: 'POST',
							    data: {
							    	search_recordings : $('#search').val(),
							    	start_filterdate : start_filterdate_val,
							    	end_filterdate : end_filterdate_val,
							    	agent_filter : agent_filter_val
							    },
								success: function(data) {
									$('#search_button').text("Search");
	                				$('#search_button').attr("disabled", false)
									console.log(data);
									if(data != ""){

										$('#table_callrecordings').html(data);
										$('#table_callrecordings').DataTable({
						                	"bDestroy" : true
						                });
									}else{
										init_callrecs_table.fnClearTable();
									}
								}
							});
						});

		                $("#datetimepicker4").on("dp.change", function(e) {

		                	$('#datetimepicker3').data("DateTimePicker").maxDate(e.date);

		                	if($('#search').val() == ""){
		                		$('#search_button').attr("disabled", false);
		                		$('#search_button').text("Searching...");
		                	}else{
			                	$('#search_button').text("Searching...");
			                	$('#search_button').attr("disabled", true);
		                	}

		                	if($('#agent_filter').is(':visible')) {
							    var agent_filter_val = $('#agent_filter').val();
							}else{
								var agent_filter_val = "";
							}

		            		var start_filterdate_val = $('#start_filterdate').val();
		                	var end_filterdate_val = $('#end_filterdate').val();

		            		$.ajax({
							    url: "filter_callrecs.php",
							    type: 'POST',
							    data: {
							    	search_recordings : $('#search').val(),
							    	start_filterdate : start_filterdate_val,
							    	end_filterdate : end_filterdate_val,
							    	agent_filter : agent_filter_val
							    },
								success: function(data) {
									$('#search_button').text("Search");
	                				$('#search_button').attr("disabled", false)
									console.log(data);
									if(data != ""){

										$('#table_callrecordings').html(data);
										$('#table_callrecordings').DataTable({
						                	"bDestroy" : true
						                });
									}else{
										init_callrecs_table.fnClearTable();
									}
								}
							});
						});

		            // AGENT FILTER
		            	$('#agent_filter').on('change', function() {

		            		if($('#search').val() == ""){
		                		$('#search_button').attr("disabled", false);
		                		$('#search_button').text("Searching...");
		                	}else{
			                	$('#search_button').text("Searching...");
			                	$('#search_button').attr("disabled", true);
		                	}

		                	if($('#agent_filter').is(':visible')) {
							    if($('#agent_filter').is(':visible')) {
							    var agent_filter_val = $('#agent_filter').val();
							}else{
								var agent_filter_val = "";
							}
							}else{
								var agent_filter_val = "";
							}
		            		
		            		var start_filterdate_val = $('#start_filterdate').val();
		                	var end_filterdate_val = $('#end_filterdate').val();

		            		$.ajax({
							    url: "filter_callrecs.php",
							    type: 'POST',
							    data: {
							    	search_recordings : $('#search').val(),
							    	start_filterdate : start_filterdate_val,
							    	end_filterdate : end_filterdate_val,
							    	agent_filter : agent_filter_val
							    },
								success: function(data) {
									$('#search_button').text("Search");
	                				$('#search_button').attr("disabled", false)
									console.log(data);
									if(data != ""){

										$('#table_callrecordings').html(data);
										$('#table_callrecordings').DataTable({
						                	"bDestroy" : true
						                });
									}else{
										init_callrecs_table.fnClearTable();
									}
								}
							});

						});

	                /****
	                ** Search function
	                ****/
		                $(document).on('click','#search_button',function() {
		                //init_contacts_table.destroy();

		                	if($('#search').val() == ""){
		                		$('#search_button').attr("disabled", false); 
		                		$('#search_button').text("Searching...");
		                	}else{
			                	$('#search_button').text("Searching...");
			                	$('#search_button').attr("disabled", true);
		                	}

			            	if($('#search_recordings').is(":checked")){
			            		var start_filterdate_val = $('#start_filterdate').val();
	                			var end_filterdate_val = $('#end_filterdate').val();
	                			if($('#agent_filter').is(':visible')) {
							    var agent_filter_val = $('#agent_filter').val();
							}else{
								var agent_filter_val = "";
							}

								$.ajax({
								    url: "search.php",
								    type: 'POST',
								    data: {
								    	search_recordings : $('#search').val(),
								    	start_filterdate : start_filterdate_val,
								    	end_filterdate : end_filterdate_val,
								    	agent_filter : agent_filter_val
								    },
									success: function(data) {
										$('#search_button').text("Search");
	                					$('#search_button').attr("disabled", false);
										console.log(data);
										if(data != ""){
											$('#table_callrecordings').html(data);
											$('#table_callrecordings').DataTable({
							                	"bDestroy" : true
							                });
										}else{
											init_callrecs_table.fnClearTable();
										}
									}
								});
			            	}

						});

				/*****
				** For playing Call Recordings
				*****/
				$('.play_audio').click(function(){
					var audioFile = $(this).attr('data-location');

					var sourceFile = '<audio class="audio_file" controls>';
					    sourceFile += '<source src="'+ audioFile +'" type="audio/mpeg" download="true"/>';
					    sourceFile += '</audio>';

					var avatar = $(this).attr('data-avatar');
					//var avatar = '<avatar username="'+ fullname +'" :size=36></avatar>';

					$('.play_avatar').html(avatar);
					goAvatar._init(goOptions);
					$('.download-audio-file').attr('href', audioFile);
					$('.audio-player').html(sourceFile);
					$('#call-playback-modal').modal('show');

					var aud = $('.audio_file').get(0);
					aud.play();
				});

				$('#call-playback-modal').on('hidden.bs.modal', function () {
					var aud = $('.audio_file').get(0);
					aud.pause();
				});

			});
		</script>

		<?php print $ui->getRightSidebar($user->getUserId(), $user->getUserName(), $user->getUserAvatar()); ?>
		<?php print $ui->creamyFooter();?>
    </body>
</html>