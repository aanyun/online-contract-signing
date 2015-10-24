<?php
require_once('inc/MysqliDb.php');
require_once('inc/HtmlDiff.php');

$db = new MysqliDb();
$db->where('id',$_GET['id']);
$history = $db->getOne ('history');

$db->where('contract_id',$history['contract_id']);
$db->where('doc_id',$history['doc_id']);
$historys = $db->get('history');

if(!isset($_GET['toid']) || $_GET['toid'] == null || $_GET['toid'] == "" || $_GET['toid'] == 0) {
	$db->where('id',$history['contract_id']);
	$contracts = $db->getOne ('contracts');
	$index = $history['doc_id'];
	$contract_content = json_decode($contracts['content']);
	$to_text = $contract_content[$index]->content;
} else {
	$db->where('id',$_GET['toid']);
	$to_history = $db->getOne ('history');
	$to_text = $to_history['content'];
}

$from_text = $history['content'];


// $opcodes = FineDiff::getDiffOpcodes($from_text, $to_text);
// echo FineDiff::renderDiffToHTMLFromOpcodes($from_text, $opcodes);


$diff = new HtmlDiff( $from_text, $to_text );
$diff->build();


?>
<html>
<head>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
	<script type="text/javascript" src="../assets/js/jquery/jquery-1.7.1.min.js"></script>
	<style>
		ins.diffins,ins.diffmod {color:green;background:#dfd;text-decoration:none}
		del.diffmod,del.diffdel {color:red;background:#fdd;text-decoration:none}
		.panel {
			margin-bottom: 0px;
		}
		.panel-heading {
		    padding: 5px 10px;
		    background-color: #E8E8E8!important;
		    font-weight: bold;
		}
	</style>
</head>
<body>
	<div class="row" style="height:50px;padding:10px;margin:0;">
		<div class="col-sm-3" style="padding:0;">
			<strong>Orginal Document:</strong>
			<select name="original">
				<?php
					foreach($historys as $index=>$data){
						echo "<option value= '".$data['id']."' ";
						if($data['id'] == $_GET['id']) {
							echo 'selected="selected"';
							$original = ($index+1).".0";
						}
						echo ">Version ".($index+1).".0</option>";
						
					}
				?>
			</select>
		</div>
		<div class="col-sm-3" style="padding:0;">
			<strong>Revised Document:</strong>
			<select name="revised">
				<option value="0">Current</option>
				<?php
					foreach($historys as $index=>$data){
						echo "<option value= '".$data['id']."' ";
						if($data['id'] == $_GET['id']) echo 'disabled';
						if(isset($_GET['toid'])&&$data['id'] == $_GET['toid']) echo 'selected="selected"';
						echo ">Version ".($index+1).".0</option>";
					}
				?>
			</select>
		</div>
		<div class="col-sm-6">
			<button class='btn btn-default' onclick="compare()">Compare</button>
		</div>
	</div>



		<div class="row" style="padding:10px;margin:0;">

			<div style="font-weight:bold;float:left;margin-right: 25px;">Legend:</div> 

			<div style="color:red;background:#fdd;float:left;margin-right:25px;">&#9642; &nbsp; - &nbsp; Deleted Text Differences</div>

			<div style="color:green;background:#dfd;float:left;">&#9642; &nbsp; - &nbsp; Added Text Differences</div>

		</div>


	<div class="row">
		<div class="col-sm-7" style="padding-right:0px">
			<div class="panel panel-default">
			  <div class="panel-heading">Compared Document</div>
			  <div class="panel-body" style="height:100%;overflow:auto">
			    <?php echo $diff->getDifference(); ?>
			  </div>
			</div>
		</div>
		<div class="col-sm-5" style="padding-left:0px;">
			<div style="height:50%;padding-bottom: 32px;">
				<div class="panel panel-default">
				  <div class="panel-heading">Original Document - <small>Version <?=$original?></small></div>
				  <div class="panel-body" style="height:100%;overflow:auto;margin-bottom:20px">
				    <?php echo $diff->getOldHtml(); ?>
				  </div>
				</div>
			</div>
			<div style="height:50%;">
				<div class="panel panel-default">
				  <div class="panel-heading">Revised Document</div>
				  <div class="panel-body" style="height:100%;overflow:auto">
				    <?php echo $diff->getNewHtml(); ?>
				  </div>
				</div>
				
			</div>
		</div>
	</div>
</body>
<script>
	$(document).ready(function(){
		$('select[name=original]').change(function(){
			value = $(this).val();
		$('select[name=revised] option').attr('disabled',false);
		$('select[name=revised] option[value='+value+']').attr('disabled',true);
		if($('select[name=revised]').val() == value){
			$('select[name=revised]').val(0);
		}
		});

	});
		function compare(){
			from = $('select[name=original]').val();
			to = $('select[name=revised]').val();
			location.href="compare.php?id="+from+"&toid="+to;
		}
</script>
</html>