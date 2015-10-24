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

    <title>Contract</title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css" media="screen" type="text/css" />
    <link href="//cdn.datatables.net/1.10.2/css/jquery.dataTables.css" rel="stylesheet">
    <link href="assets/css/jumbotron-narrow.css" rel="stylesheet">
    <script src="assets/js/jquery/jquery-1.7.1.min.js"></script>
    <script src="assets/js/jquery/jquery-ui-1.8.17.custom.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
    <script src="https://code.angularjs.org/1.2.0-rc.3/angular.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.2.0-rc.3/angular-sanitize.js"></script>
    <script src="assets/js/angular-ui-bootstrap-modal.js"></script>

  </head>

  <body ng-app="eContract">

    <?php include 'inc/header.php';?>

    <div class="container" ng-controller="contractController">
      <!--Loading page-->
      <center class="loading"><img style="margin-top:50px;margin-bottom:300px" src="assets/img/loading.gif"/></center>
      <!--end of Loading page-->
      <!--Login page-->  
        <div class="terms" style="display:none">
          <h3>Initial Step: Setting up your e-Signature</h3>
          <hr></hr>
          <h5><b>e-Signature Terms and Conditions</b></h5>
          <p>You agree and consent to the use of a key pad, mouse, or other device to select an item while using any electronic service we offer - or in accessing or making any transactions regarding any agreement, acknowledgement, or consent – constitutes your signature, acceptance, and agreement as if actually signed by you in writing. Further, you agree no certification authority or other third party verification is necessary to the validity of your electronic signature, and the lack of such certification or third party verification will not in any way affect the enforceability of your signature or the resulting contract between you and Ignitor Labs.</p>
          In addition: 
          <ul>
            <li>Any electronic document bearing a user’s electronic signature will be considered ‘in writing’, and ‘wet signed.’ </li>
            <li>Any user electronically signed document shall be deemed to be an ‘original’ document when printed and used in the normal course of business.</li>
            <li>Absent manifest error, the admissibility, validity, or use of any electronically signed document cannot be contested.</li>
          </ul>
          <hr></hr>
          <!--client info-->
          <div class="client_info" ng-init = "loaded = false" ng-show="loaded">
            <h4>Please input your information</h4>
            <br>
            <!--First time regist form-->
            <form name="form1" class="form-horizontal" ng-hide="loaded && registed">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="padding-top:0px;margin-top:-5px;">Company Name</label>
                    <div class="col-sm-9">
                      <input type="text" class="form-control" ng-model="client_company">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="padding-top:0px;margin-top:-5px;">Authorized Signatory</label>
                    <div class="col-sm-9">
                      <input type="text" class="form-control" ng-model="client_name" required >
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="" class="col-sm-3 control-label">Title<br></label>
                    <div class="col-sm-9">
                      <input type="text" class="form-control" ng-model="client_title" required >
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="" class="col-sm-3 control-label" >Email</label>
                    <div class="col-sm-9">
                      <input type="email" class="form-control" ng-model="user.email"  required >
                    </div>
                  </div>
                </div>
              </div> 
              <center><button class="btn btn-primary" ng-disabled="form1.$invalid" ng-click="regist()">Agree and Continue</button></center>
            </form>
            <!--end of First time regist form-->
            <!--Login form-->
            <div ng-show="loaded && registed">
              <p>Are you <span style="font-weight:bold;font-size:1.3em">{{client_name}}</span> from <span style="font-weight:bold;font-size:1.3em">{{client_company}}</span> ?</p>
              <p>If yes, please input your email to continue:</p>
              <form name="form2" class="row form-horizontal">
                <div class="form-group">
                    <label for="" class="col-sm-3 control-label" >Email</label>
                    <div class="col-sm-4">
                      <input type="email" class="form-control" ng-model="user.email"  required >
                    </div>
                </div>
                <center><button class="btn btn-primary" ng-disabled="form2.$invalid" ng-click="check()">Agree and Continue</button></center>
              </form>
            </div>
            <!--end of login form-->
          </div>
          <!--end of client info-->
        </div>
        <!--end of Login Page-->
               <!--Contract viewer-->
        <div class="row isIn" style="display:none">
          <div class="instruction">
            <p class="inst-title">This electronic contract is comprised of two tasks. <small>These tasks are outlined below:<br /><span style="color:#AC0000 ">Please read through the instructions and click the "<strong>I Acknowledge</strong>" button below to continue.</small></span></p>
          <hr></hr>

          <div class="bg1">
            <div class="innerText">
              <ul>
              <li class="a1"><div class="placeholder" ng-class="partA||signed?'checked':'unchecked'"></div> Client reviews and initials the Non-Disclosure Agreement <span style="margin-left: 90px">(NDA) and Source Materials Checklist.</span></li>
              <hr></hr>
              <li class="a2"><div class="placeholder" ng-class="signed?'checked':'unchecked'"></div> Client signs the NDA. <a class="a2jump" ng-show="partA&&!signed" href="javascript:void(0)">Click here</a></li>
            </ul>
            </div>
          </div>


          <div class="note2"><p>Before starting Task 2, Client will be required to send all available source materials. Once all source materials are received and approved, Ignitor Labs will draft an official eContent Design Document (eCDD) to be included with the eContent Development Agreement and the remaining documents. Upon completion, Client will receive an email from Ignitor Labs informing them that Task 2 can be completed.</p>
          </div>

          <div class="bg2">
            <div class="innerText2">
              <ul>
              <li class="b1"><div class="placeholder" ng-class="signed&&partA?'checked':'unchecked'"></div> Client reviews and initials the remaining documents.</li>
              <hr></hr>
              <li class="b2"><div class="placeholder" ng-class="sec_signed?'checked':'unchecked'"></div> Client signs the eContent Development Agreement (eCDA). <a class="b2jump" ng-show="signed&&partA&&!sec_signed" href="javascript:void(0)">Click here</a></li>
            </ul>
            </div>
          </div>


          <div class="note">
            <p>The initial field will appear below the document window after scrolling to the end of the document. If you have any questions about the content of the contract, please contact <b>{{ignitor_name}} (<a href="mailto:{{ignitor_email}}">{{ignitor_email}}</a>), {{ignitor_title}} from Ignitor Labs.</b></p>
          </div>


          <div class="bg3">

          <div ng-hide="instructionAgree" class="col-sm-12" style="margin-left: 10%; margin-bottom:20px;">
                <div class="table-row">
                  <span>
                    <button type="submit" class="pull-right btn btn-primary" ng-click="instruction()">I Acknowledge</button>
                  </span>
                  <span style="font-weight:bold;font-size:.95em;color:#ac0000;width:500px;">
                    that I have read and understand the contents of this document.
                  </span>
                </div>
          </div>

          </div>

            
          </div>
          
          <div class="contractViewer" ng-show="instructionAgree">
          <!--Export Button-->

            <div class="col-sm-3" style="font-weight:bold;top:20px;padding:0;">
              
            </div>
            <div class="col-sm-3" style="font-weight:bold;top:20px;padding:0;">
              
            </div>

            <div class="col-sm-6" style="padding-bottom:15px;padding-right:0;padding-left:0;">
            <p>
              <span class="pull-right glyphicon glyphicon-export" style="color:#fff;"></span>
              <a style="width:300px;float:right;" class="btn btn-primary" href="assets/contracts/download.php?client={{folder}}">
                Export All Documents as a Zip File
              </a>
            </p>
            </div>

            <!--end of Export Button-->
            <!--Contract List-->
            <div class="col-sm-4" style="height:450px!important;padding-left:0px" >
              <div class="taskOneDiv"><span>PART A</span></div>
              <div class="taskTwoDiv">PART B</div>
              <ul class="contractList" style="height:450px!important"> 
                <li ng-repeat="contract in contracts" ng-class='{active:current($index),approved:contract.read&&contract.check,TaskOne:$index<2,stepOneDone:signed,TaskTwo:$index>1,StepTwoDone:sec_signed}' ng-click="goTo($index)">
                    <div style="height:45px;">{{contract.name}}</div>
                    <div style="margin-top: -40px;">
                    <span class="pull-right glyphicon " ng-class="{'glyphicon-ok':contract.read||contract.check,'glyphicon-remove':!contract.read||(contract.read&&!contract.check&&!signed)||contract.disable,'StepOneCheck':signed}" style=""></span>

                    </div>
                </li>
              </ul>
            </div>
            <!--end of Contract List-->
            <div class="panel panel-default contract-panel col-sm-8" style="padding-left:0px;padding-right:0px">
                <div class="panel-heading" style="padding:5px 15px"><a href="#" ng-click="open()"><img style="width:30px" src="assets/img/history2.png" />&nbsp;&nbsp; History</a></div>
              <div class="panel-body contract-body" style="height:530px;overflow:scroll;overflow-x:hidden;">
                
              </div>
            </div>
          </div>
          <!--end of Contract viewer-->
          <!--Signature Area-->
          <div> 
            <div class="row">
              <form name="form" id = "form" class="form-inline col-sm-9 col-sm-offset-3" role="form">
                <div class="checkpoint row" style="display:none">
                  <div class="col-sm-3">
                    <div class="form-group pull-right">
                      <label for="initial">Initial</label>
                      <input type="text" style="width:50px" class="form-control" name="initial" ng-model="client_initial" required>
                    </div>
                  </div>
                  <div class="col-sm-2" style="padding:0;">
                  <button type="submit" class="pull-right btn btn-primary" ng-disabled="form.$invalid" ng-click="next()">I Acknowledge</button>
                </div>
                  <div class="col-sm-7" style="padding-left: 25px;margin-top:5px;">
                    <div class="checkbox">
                      <label style="font-size:1em;color:#d44950;">
                       <!-- <input type="checkbox" ng-model="client_initial_checked" required> -->I have read and understand these instructions.
                      </label>
                    </div>
                  </div> 

                </div>
              </form>
            </div>
            <!--NDA signature form-->
            <div class="panel panel-default showAgreement sign1" style="display:none;border-color:#fff;" ng-show="instructionAgree && showAgreement && !signed">
              <div class="panel-body">
                <div class="sign-title">
                <h3>Step 1: Sign and Approve </h3>
                </div>
                <div class="s">
                <p>Please sign below to agree to the <b>Non-Disclosure Agreement (NDA)</b> and acknowledge that you will submit the materials required by the Source Materials Checklist. </p>
                <p>The Source Materials Checklist is required in order to develop the eContent Design Document (eCDD).
                  After submission of this form, please submit all source materials via email (<a href="mailto:{{ignitor_email}}">{{ignitor_email}}</a>) within one week. 
                  Upon receipt of the required materials, Ignitor Labs will create a customized eCDD and replace the sample eCDD in your contract documents.
                </p>
                <form name = "form3" class="form-horizontal" role="form" >
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Company Name</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" ng-model="client_company" required>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputPassword3" class="col-sm-2 control-label">Printed Name</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" ng-model="client_name" required>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputPassword3" class="col-sm-2 control-label">Title</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" ng-model="client_title" required>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="" class="col-sm-2 control-label">e-Signature</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" ng-model="client_signiture" required>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="" class="col-sm-2 control-label">Date</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" ng-model="client_sign_date" required>
                    </div>
                  </div>
                    <div class="form-group">
                      <div class="col-sm-offset-2 col-sm-10">
                        <div class="checkbox">
                          <label>
                            <input type="checkbox" ng-model="client_agree" required> I agree to the Non-Disclosure Agreement (NDA) and the Source Materials Checklist.
                          </label>
                        </div>
                      </div>
                    </div>
                  <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                      <button id="submit" type="submit" class="btn btn-primary" ng-disabled="form3.$invalid" ng-click="submit(1)">Submit</button>
                       <span class="submitloading" style="display:none"><img src="assets/img/loading.gif" /></span>
                    </div>
                  </div>
                </form>
              </div>
            </div>
            </div>
            <!--end of NDA signature form-->
            <!--eCDA signature form-->
            <div class="panel panel-default Sec_Agreement sign2" style="display:none" >
              <div class="panel-body">
                <div class="sign-title">
                <h3>Step 2: Sign and Approve</h3>
                </div>
                <div class="s">
                <p>Please sign to confirm that you have reviewed all documents, and that you approve and agree to the <b>eContent Development Agreement (eCDA)</b>.</p>
                <p>By agreeing to the eCDA, you are entering into a binding contract as defined in the eCDA. If you do not agree to any document displayed above, please send your inquiry to {{ignitor_name}} (<a href="mailto:{{ignitor_email}}">{{ignitor_email}}</a>). Once approved, the production on your project can start.</p>
                <form name = "form3" class="form-horizontal" role="form" >
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Company Name</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" ng-model="client_company" required>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputPassword3" class="col-sm-2 control-label">Printed Name</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" ng-model="client_name" required>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputPassword3" class="col-sm-2 control-label">Title</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" ng-model="client_title" required>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="" class="col-sm-2 control-label">e-Signature</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" ng-model="client_signiture" required>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="" class="col-sm-2 control-label">Date</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" ng-model="client_sign_date" required>
                    </div>
                  </div>
                    <div class="form-group">
                      <div class="col-sm-offset-2 col-sm-10">
                        <div class="checkbox">
                          <label>
                            <input type="checkbox" ng-model="client_agree" required> I agree to the eContent Development Agreement (eCDA).
                          </label>
                        </div>
                      </div>
                    </div>
                  <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                      <button id="submit" type="submit" class="btn btn-primary" ng-disabled="form3.$invalid" ng-click="submit(2)">Submit</button>
                       <span class="submitloading" style="display:none"><img src="assets/img/loading.gif" /></span>
                    </div>
                  </div>
                </form>
              </div>
            </div>
            </div>
            <!--end of eCDA signature form-->
        </div>
      </div>
      <!--end of signature area-->
      <br>
      <div class="footer">
        <p>&copy; Ignitor Labs 2014</p>
      </div>


      <div modal="showModal" close="cancel()" class="history">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" ng-click="cancel()" ><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
              <h4 class="modal-title">History of {{current_contract_name}}</h4>
            </div>
            <div class="modal-body">
              <small style="color:#777">Click to Download</small>
              <br>
              <img src="assets/img/loading.gif" />
              <ul class="" style="height:450px!important">
                <li ><b>Current</b>
                  <span style="float:right"><a target="_blank" ng-href="contracts/{{folder}}/{{client}}_{{current_contract_name}}.pdf">Download</a></span>
                </li>
                <li ng-repeat="history in historyList">
                  <b>Version {{historyTotal-$index}}</b>
                  <span><small style="color:#777">{{history.create_date}}</small></span>
                  <span style="float:right;margin-left:5px"><a target="_blank" ng-href="download.php?name={{current_contract_name}}&id={{history.id}}&version={{historyTotal-$index}}&date={{history.create_date}}">Download</a></span>
                  <span style="float:right"><a target="_blank" ng-href="compare.php?id={{history.id}}">Compare</a></span>
                </li>
              </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" ng-click="cancel()" >Close</button>
            </div>
          </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
      </div><!-- /.modal -->


    </div> <!-- /container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script>
      var fullDate = new Date();
      var twoDigitMonth = (fullDate.getMonth()+1)+"";if(twoDigitMonth.length==1)  twoDigitMonth="0" +twoDigitMonth;
      var twoDigitDate = fullDate.getDate()+"";if(twoDigitDate.length==1) twoDigitDate="0" +twoDigitDate;
      var currentDate = twoDigitMonth + "/" + twoDigitDate + "/" + fullDate.getFullYear();
      var signed;
      var sec_signed;
      var check =0;
      var id;
      var contracts;
      var current = 0;
      <?php 
        if(isset($_SESSION['login'])){
          echo "var login=\"".$_SESSION['login']."\";";
        } else echo "var login =0;";
      ?>
      var app = angular.module('eContract',['ngSanitize','ui.bootstrap.modal']);
      app.controller('contractController',['$scope','$http',function($scope,$http){
        $scope.loaded = false;
        $http({
          method:"GET",
          url:"API.php?c=getContractByClient&id=<?=$_GET['id']?>",
          cache:false
        }).
        success(function(data) {
          ignitor = data[0];
          id = ignitor.id;
          $scope.ignitor_name = ignitor.ignitor_name;
          $scope.ignitor_title = ignitor.ignitor_title;
          $scope.ignitor_email = ignitor.ignitor_email;
          $scope.registed = ignitor.registed;
          $scope.client_name = ignitor.client_name;
          $scope.client_title = ignitor.client_title;
          $scope.client_company = ignitor.client_company;
          if($scope.client_company==""||$scope.client_company==null) $scope.client_company = ignitor.client;
          $scope.loaded = true;
          $scope.client = ignitor.client;
          if (login == ignitor.client) {
            $scope.getContractContent(id);
          } else {
            $('.terms').show();
            $('.loading').hide();
          }

        });

        $scope.client_sign_date = currentDate;

        $scope.isIn = 0;

          $scope.history2 = function(){
//               $('.history').modal('show');
               $('.history').find('ul').hide();
               $('.history').find('img').show();
               $http({
               method: 'post',
                   url:"API.php?c=getHistoryId",
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
       };

        $scope.getContractContent = function(value){
          //console.log(value);
          $('.terms').hide();
          $('.loading').show();
          $http({
            method: 'GET',
            url:"API.php?c=getContractContent&id="+value,
            cache:false
          }).
          success(function(data, status, headers, config) {
            $scope.signed = data.first_signed;
            signed = data.first_signed;
            $scope.sec_signed = data.sec_signed;
            sec_signed = data.sec_signed;
            var sec_signed = $scope.sec_signed;
            contracts = JSON.parse(data.content);
            $scope.status = [];
            if (data.initialed!=null){
              $scope.instruction();
              $scope.status = data.initialed.split(",");
            }
              
            console.log(data.first_signed);

            if(signed) {
              $scope.instruction();
              current = 2;
              for(var i=2;i<8;i++){
                contracts[i].disable = true;
              }
                contracts[0].check = true;
                contracts[0].read = true;
                contracts[1].check = true;
                contracts[1].read = true;
              } else {
                contracts[5].read = true;
                contracts[2].read = true;
                contracts[3].read = true;
                contracts[4].read = true;
                contracts[6].read = true;
                contracts[7].read = true;
                check = 6;
              }

            for(var i=0;i<8;i++){
                if($.inArray(i+"",$scope.status)>-1){
                  contracts[i].read = true;
                  contracts[i].check = true;
                  contracts[i].disable = false;
                  check++;
                  $scope.isFormShow();
                }
            }
            
            if(sec_signed) {
              current = 0;
              for(var i=0;i<8;i++){
                contracts[i].read = true;
                contracts[i].check = true;
                contracts[i].disable = false;
              }
                check = 8;
            }
            // $each(contracts,function(index,value){
            //   if(value.read)
            // });
            $scope.contracts = contracts;
            $scope.folder = data.folder;
            $('.contract-body').html(contracts[current].content);
            $scope.current_contract_name = contracts[current].name;
            $('.contract-body').scrollTop(0);
            $('.isIn').show();
            $('.loading').hide();
          });
        }
        $scope.isFormShow = function(){
          if (check == contracts.length&&!$scope.sec_signed) {
            $scope.showAgreement = true;
            $scope.Sec_Agreement = true;
            if($scope.signed) $('.Sec_Agreement').show();
            else $('.showAgreement').show();
          }
          if (check == contracts.length) {
            $scope.partA = true;
          }
        };
        $scope.instruction = function(){
          $scope.instructionAgree = true;
        };
        $scope.saveStatus = function(status){
          $.post("API.php?c=saveStatus&id="+id,{
            status:$scope.status
          },function(data){
          });
        };
        $scope.next = function(){
          check++;
          $scope.status.push(current);
          $scope.saveStatus($scope.status);
          console.log($scope.status);
          contracts[current].check = true;
          contracts[current].disable = false;
          contracts[current].read = true;
          if (check == contracts.length) {
            $scope.partA = true;
            $scope.showAgreement = true;
            $scope.Sec_Agreement = true;
            if($scope.signed) {
              $('.Sec_Agreement').show();
              // $('html, body').animate({
              //   scrollTop: $(".Sec_Agreement").offset().top
              // }, 2000);
            }
            else {
              $('.showAgreement').show();
              // $('html, body').animate({
              //   scrollTop: $(".showAgreement").offset().top
              // }, 2000);
            }
          }
          if(current == contracts.length-1) {
            $('.checkpoint').hide();
            return;
          }
          // while(contracts[current].read==true&&current<contracts.length){
          //   current++;
          //   console.log(current);
          // }
          
          current++;
          $scope.current_contract_name = contracts[current].name;
          $('.contract-body').html(contracts[current].content);
          $('.contract-body').scrollTop(0);
          if(current!=4) $('.checkpoint').hide();
        };
        $scope.current = function(value){
          return value == current;
        };
        $scope.getIn=function(){
          $scope.isIn = true;
          $('.isIn').show();

        };
        $scope.regist = function(){
          //$scope.getIn();
          $.post("API.php?c=initial&id="+id,{
            client:$scope.client,
            fullName:$scope.client_name,
            company:$scope.client_company,
            title:$scope.client_title,
            email:$scope.user.email,
            folder:$scope.folder
          },function(data){
            if(data){
              $scope.$apply(function(){
                  $scope.isIn = true;
                  $scope.getContractContent(id);
              });
            }
              

        });
        
        };

        $scope.goTo = function(value){
          current = value;
          $scope.current_contract_name = contracts[current].name;
          $('.contract-body').html(contracts[current].content);
          if(current!=4) $('.contract-body').scrollTop(0);
          else $('.contract-body').scrollTop(100);
          if(contracts[current].read) {
            $('.checkpoint').hide();
          } else if(current!=4){
            $('.checkpoint').hide();
          }
        };
        $scope.check = function(){
          email = $scope.user.email;
          $.post("API.php?c=check&id="+id,{
            email:$scope.user.email
          },function(data){
            if(data == 1){
              $scope.getContractContent(id);
              $scope.$apply(function(){
                $('.terms').hide();
              });
            } else {
              alert("Email is wrong.");
            }
              

          });
        };

          //MODAL CHANGE
          $scope.open = function() {
              $('.history').find('ul').hide();
              $('.history').find('img').show();
              $http({
                  method: 'post',
                  url:"API.php?c=getHistoryId",
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
              $scope.showModal = true;
          };

          $scope.ok = function() {
              $scope.showModal = false;
          };

          $scope.cancel = function() {
              $scope.showModal = false;
          };

        $scope.submit = function(value){
          addString="<table><tr><td><h3>IGNITOR LABS, LLC</h3><p>By: "+$scope.ignitor_name+'</p><p>Title: '+$scope.ignitor_title+'</p><p>Date: '+$scope.client_sign_date+'</p><p>Date: '+$scope.ignitor_name+'</p>';
          addString= addString+ "</td><td><h3>"+$scope.client+"</h3><p>By: "+$scope.client_name+'</p><p>Title: '+$scope.client_title+'</p><p>Date: '+$scope.client_sign_date+'</p><p>Signature: '+$scope.client_signiture+'</p></td></tr></table>';
          if(value==1) {
            temp_content = contracts[0].content + addString;
          }
          else {
            temp_content = contracts[2].content + addString;
          }
          $('.submitloading').show();
          $('#submit').hide();
          $.post("API.php?c=sign&id="+id,{
            contract:temp_content,
            type:value,
            client:$scope.client,
            fullName:$scope.client_name,
            date:$scope.client_sign_date,
            title:$scope.client_title,
            initial:$scope.client_initial,
            signature:$scope.client_signiture,
            folder:$scope.folder
          },function(data){      
            if(data=="true") {
              //window.location.href = "contracts/download.php?client="+$scope.folder;
              location.reload();
            } else {
              alert('Error. Please try it later.');
            }
          });
        };

      }]);
      $('.contract-body').scroll(function(){
        console.log(contracts[current].read);
         if (contracts[current].read || sec_signed || (signed&&contracts[3].name.indexOf('Example')>-1)) {
          if(signed&&contracts[3].name.indexOf('Example')>-1) {
            //alert('Step 2 cannot be completed until a customized eCDD has been created by Ignitor Labs. An email will be sent to you to notify when it is ready.');
          }
          return;
         }
         if ($(this).scrollTop() - ($(this)[0].scrollHeight - $(this).height()) >-100) {
           $('.checkpoint').show();
         }

      });
      $('.history').on('hide',function(e){
        e.preventDefault();
      });
      $('.b2jump').click(function(){
        if(check==8){
          $('html, body').animate({
            scrollTop: $(".Sec_Agreement").offset().top
          }, 2000);
        }
      });
      $('.a2jump').click(function(){
        if(check==8){
          $('html, body').animate({
            scrollTop: $(".showAgreement").offset().top
          }, 2000);
        }
      });
    </script>
  </body>
</html>
