<?php
require_once('inc/MysqliDb.php');
require_once('inc/Encryption.php');
ini_set("memory_limit","200M");
$db = new MysqliDb();
$db->orderBy("id","Desc");
$contracts = $db->get ('contracts');
$encrypt = new Encryption();
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="contract generater for ignitorlabs">
    <meta name="author" content="anyun">

    <title>Contract List</title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
    <!-- Custom styles for this template -->
    <link href="//cdn.datatables.net/1.10.2/css/jquery.dataTables.css" rel="stylesheet">
    <script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
    <script src="//cdn.datatables.net/1.10.2/js/jquery.dataTables.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
  </head>

  <body>
    <?php include 'inc/header.php';?>
    <div class="container">
      <div>
        <table>
          <thead>
            <tr><th>Status</th><th>Creation Date</th><th>Client</th><th>Link</th><th>Download</th><th>Email</th><th>Remove</th><th>Edit</th></tr>
          </thead>
          <?php
            if ($db->count > 0)
                foreach ($contracts as $contract) {
                  $link = $encrypt->encode($contract['id']);
                  echo "<tr id={$contract['id']}>";
                  if ($contract['sec_signed']) echo "<td><span class='label label-success'>Complete</span></td>";
                  else if($contract['first_signed']) echo "<td><span class='label label-warning' >Stage 1</span></td>";
                  else if($contract['client_name']!=null) echo "<td><span class='label label-default' data-container=\"body\" data-toggle=\"popover\" trigger='focus' data-placement=\"right\" data-html='true' data-title='New User registed in this contract:' data-content=\"<b>".$contract['client_name']."</b><br><a href='mailto:".$contract['client_email']."'>".$contract['client_email']."</a><br><i>".$contract['client_title']."</i><br>".$contract['client_company']."\">Stage 0</span></td>";
                  else echo "<td></td>";
                  echo "<td>{$contract['date']}</td>
                  <td>{$contract['client']}</td>
                  <td><a href='contract.php?id={$link}'>contract.php?id={$link}</a></td>
                  <td><a href='assets/contracts/download.php?client=".$contract['folder']."' class='btn btn-info btn-xs'>Download</a></td>
                  <td><a href='' data-link='{$link}' data-client='".$contract['client_email']."' data-ignitor='".$contract['ignitor_name']."' data-email='".$contract['ignitor_email']."' class='btn btn-default btn-xs email'>Email</a></td>
                  <td><img style='display:none' src='assets/img/loading.gif'/><button class='btn btn-danger btn-xs remove'>Remove</button></td>
                  <td><a href='edit.php?id={$link}' class='btn btn-warning btn-xs edit'>Edit</a></td>";
                  echo "</tr>";
                }
          ?>
        </table>
      </div>
      <div class="footer">
        <p>&copy; Ignitor Labs 2014</p>
      </div>

    </div> <!-- /container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script>
      $(document).ready(function() {
        $('table').dataTable();
        $('.label-default').popover();

        /*******Remove********/
        $('button.remove').click(function(){
          $(this).hide();
          $(this).parent().find('img').show();
          id = $(this).parent().parent().attr('id');
          $.post('API.php?c=remove',{
            id:id
          },function(data){
            if(data) window.location.reload();
          });
        });

        /*******Email*******/
        $('.email').click(function(){
          data= $(this).attr('data-link');
          name = $(this).attr('data-ignitor');
          email = $(this).attr('data-email');
          client = $(this).attr('data-client');
          $(this).attr('href','mailto:'+client+'?subject=Ignitor Labs&body='+
              'Thank you for your interest in Ignitor Labs. In order to move forward with your project, please review and approve the electronic documents by clicking the link below. %0d%0a%0d%0a'+
              'http://'+location.host+'/contract_gen/contract.php?id='+data+'%0d%0a%0d%0a'+
              'To learn more about the eContract process, you can view the Welcome Guide by clicking below.%0d%0a%0d%0a'
              +
              'http://'+location.host+'/contract_gen/eContractWelcome.pdf%0d%0a%0d%0a'+
              'If you have any questions or concerns about the content of the contract, please contact '+name+' ('+email+'), your representative from Ignitor Labs.%0d%0a%0d%0a'

            );
        });
      });
    </script>
  </body>
</html>
