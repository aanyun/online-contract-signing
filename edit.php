<?php
  session_start();
  if(!isset($_GET['id'])) header('Location:Error.php');
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="contract generater for ignitorlabs">
    <meta name="author" content="anyun">

    <title>Contract Edit</title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
    <link href="assets/css/contractList.css" rel="stylesheet">
    <script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
    <script src="https://code.angularjs.org/1.2.0-rc.3/angular.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.2.0-rc.3/angular-sanitize.js"></script>
    <script type="text/javascript" src="assets/lib/tinymce/js/tinymce/tinymce.min.js"></script>

  </head>

  <body ng-app="eContract">

    <?php include 'header.php';?>

    <div class="container" ng-controller="contractController">
      <center ng-init="ready = false" ng-hide="ready"><img style="margin-top:50px;margin-bottom:300px" src="assets/img/loading.gif"/></center>
      <div ng-show="ready">
        <div style="margin-bottom:20px" class="row">
          <div class="col-sm-6" style="padding:0;"><b>Client: </b> {{client_company}} </div> 
          <div class="col-sm-6 text-right">
            <p><b>Create Date:</b> {{create_date}} </p>
            <p><b>Last Update Date:</b> {{update_date}}</p>
          </div>
           
        </div>
        <div class="row">
          <div class="col-sm-4" style="height:450px!important;padding-left:0px" >
            <ul class="contractList2" style="height:450px!important">
              <li ng-repeat="contract in contractList track by $index" ng-class='{active:current($index)}' ng-click="goTo($index)">
                  <div style="height:45px">{{contract.name}}</div>
                  <span class="pull-right glyphicon glyphicon-ok" ng-show="contract.read"></span>
              </li>
            </ul>
          </div>
          <div class="col-sm-8" >
            <div class="contract-body" id="contractText">
              
            </div>
          </div>
        </div>
        <div style="margin-top:20px">
          <p class="text-right"><span class="text-danger" ng-show="loading">Saving, please don't close or refresh this page.</span> 
            <button class="btn btn-primary" ng-click="saveContract();" ng-hide="loading">
              <span>Save Changes</span>
            </button>
            <span ng-show="loading"><img src="assets/img/loading.gif" /></span>
          </p>
        </div>
      <br>
      </div>

      <div class="history modal fade">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
              <h4 class="modal-title">History</h4>
            </div>
            <div class="modal-body">
              <small style="color:#777">Click to view Version</small>
              <br>
              <img src="assets/img/loading.gif" />
              <ul class="" style="height:450px!important">
                <li ><a href="#" ng-click="backToCurrent()">Current</a></li>
                <li ng-repeat="history in historyList">
                    <a href="#" ng-click="replace($index)">Version {{historyTotal-$index}}</a>
                </li>
              </ul>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
              <!-- <button type="button" class="btn btn-primary">Save changes</button>-->
            </div> 
          </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
      </div><!-- /.modal -->

      <div class="modal bs-example-modal-sm fade email">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
              <h4 class="modal-title">Document Saved</h4>
            </div>
            <div class="modal-body">
              <a class="btn btn-default">Email to Client</a>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
              <!-- <button type="button" class="btn btn-primary">Save changes</button>-->
            </div> 
          </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
      </div><!-- /.modal -->

    </div> <!-- /container -->

      <div class="footer container">
        <p>&copy; Ignitor Labs 2014</p>
      </div>


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script>
      var changedDoc = [];
      var contracts;
      var current_contracts;
      var id;
      var current = 0;
      var check =0;

      var app = angular.module('eContract',['ngSanitize']);
      app.controller('contractController',['$scope','$http',function($scope,$http){



      tinymce.init({
        plugins: ["advlist lists charmap print preview code hr example save"],
        selector: ".contract-body",
        menubar: "tools table format view insert edit",
        toolbar:[
          "undo redo | styleselect | bold italic | link image | alignleft aligncenter alignright | bullist numlist | print | mybutton"
        ],
        statusbar : false,
        height: 500,
        theme_advanced_buttons1: "mybutton",
        setup : function(ed) {
      // Register example button
          ed.addButton('mybutton', {
             title : 'Revision History',
             image : 'assets/img/history.png',
             onclick : function() {
              $('.history').modal('show');
              $('.history').find('ul').hide();
              $('.history').find('img').show();
              $http({
                method: 'post',
                url:"API.php?c=getHistory",
                data:$.param({
                id:id,
                index:current
              }),
              headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).success(function(data){
                $scope.historyList = data;
                $scope.historyTotal = data.length;
                $('.history').find('ul').show();
                $('.history').find('img').hide();
              });
                
             }
          });
          ed.on('change', function(e) {
            if($.inArray(current,changedDoc)==-1)
              changedDoc.push(current);
              contracts[current].content = tinymce.activeEditor.getContent();
              contracts[current].name = contracts[current].name.replace(" Example","");
              //contracts[current].name = contracts[current].name.replace("Sample","");
          });
       }
      });


        $scope.init = function(){
          $http.get("API.php?c=getContract&id=<?=$_GET['id']?>").
          success(function(data, status, headers, config) {
            $scope.contractList=JSON.parse(data.content);
            contracts = JSON.parse(data.content);
            current_contracts = JSON.parse(data.content);
            tinymce.activeEditor.setContent(contracts[current].content);
            $scope.client_company = data.client;
            $scope.client_name = data.client_name;
            $scope.client_title = data.client_title;
            $scope.client_email = data.client_email;
            $scope.create_date = data.date;
            $scope.update_date = data.update_date;
            id = data.id;
            $scope.ready = true;
          });
          $scope.loading = false;
          $scope.date = new Date();
          $scope.isIn = "<?= isset($_SESSION['login'])?>";
        };
        
        $scope.init();

        $scope.current = function(value){
          return value == current;
        };
        $scope.goTo = function(value){
          current = value;
          tinymce.activeEditor.setContent(contracts[current].content);
          if(contracts[current].read) {
            $('.checkpoint').hide();
          }
        };
        $scope.replace = function(value){
          console.log($scope.historyList);
          tinymce.activeEditor.setContent($scope.historyList[value].content);
          $('.history').modal('hide');
        };
        $scope.backToCurrent = function(){
          tinymce.activeEditor.setContent(current_contracts[current].content);
          $('.history').modal('hide');
        };

        $scope.saveContract = function(){
          emailcontent = "Updates have been made to%3A%0A";
          $scope.loading = true;
          $.each(changedDoc,function(index,value){
              emailcontent = emailcontent + contracts[value].name+"%0A";
              //console.log(current_contracts[value].content);
              $.post("API.php?c=saveToHistory",
              {content:current_contracts[value].content,
                index:value,
                id: id
              },
              function(){

              });
          }); 
          if($.inArray(3,changedDoc)>-1){
            emailcontent = emailcontent + "An update has been made to the eContent Design Document (eCDD) and is now available to be viewed and signed. At this point, you will be able to review and approve all remaining documents, including the eContent Design Agreement (eCDA). Please note that the eCDA is a legally binding document. Once signed, all parties are contractually obligated to uphold the terms therein.";
          }
          emailcontent = emailcontent+ " Please click the link below to review and approve the changes.%0A";
          
          email = $scope.client_email;
          
          $.post("API.php?c=change&id="+id,{
            content:contracts,
            changedContracts:changedDoc,
            client:$scope.client_company
          },function(data){
            $scope.$apply(function(){
              $scope.loading = false; 
            });   
            if(data){
              emailcontent = emailcontent+data;
              console.log(emailcontent);
              $('.email .btn').attr('href','mailto:'+email+'?body='+emailcontent);
              $('.email').modal('show');
              changedDoc = [];
              
              //location.reload();
            } else {
              alert("Error");
            }
          });
        };

      }]);
    $('.email').on('hidden.bs.modal', function (e) {
      location.reload();
    })

    </script>
  </body>
</html>
