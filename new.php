<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="contract generater for ignitorlabs">
    <meta name="author" content="anyun">

    <title>Contract</title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">             

    <!-- Custom styles for this template -->
    <link href="assets/css/contractList.css" rel="stylesheet">
    <script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
    <script src="assets/lib/tinymce/js/tinymce/tinymce.min.js"></script>
    <script src="https://code.angularjs.org/1.2.0-rc.3/angular.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.2.0-rc.3/angular-sanitize.js"></script>
  </head>

  <body ng-app="eContract">
    <?php include 'inc/header.php';?>
    <div class="container" ng-controller="InfoController as info">

      <div id="home" ng-hide="info.isDisplay" class="col-sm-10 col-sm-offset-1">
        <form name="form" class="form-horizontal" role="form" novalidate >
          <div class="panel panel-default">
            <div class="panel-body">
              <h3 style="font-weight:bold;">Ignitor Labs Information&nbsp;&nbsp;<small><em>(To be completed with Ignitor Labs information)</em></small></h3>
              <form name="form" class="form-horizontal" role="form" novalidate >
              <div class="form-group">
                <label class="col-sm-3 control-label">Effective Date:</label> 
                <div class="col-sm-9">
                  <input class="form-control" date-input ng-model="info.date" required/>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label">Authorized Signatory:</label> 
                <div class="col-sm-9">
                  <input class="form-control" ng-model="info.ignitor.name" ng-init="info.ignitor.name='Tester Tester'" placeholder="Employee Name" required disabled/>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label">Position Title:</label>
                <div class="col-sm-9"> 
                  <input class="form-control" ng-model="info.ignitor.title" disabled ng-init="info.ignitor.title='Vice President, Operations'" placeholder="Production Team" required />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label">Email:</label>
                <div class="col-sm-9"> 
                  <input type="email" class="form-control" placeholder="example@test.com" ng-model="info.ignitor.email" ng-init="info.ignitor.email='anyunww@gmail.com'" required />
                </div>
              </div>
              
            </div>
          </div>
          <div class="panel panel-default">
            <div class="panel-body">
              <h3 style="font-weight:bold;">Client Info&nbsp;&nbsp;<small><em>(To be completed with client information)</em></small></h3>
              
              <div class="form-group">
                <label class="col-sm-3 control-label">Client's Company Name:</label>
                <div class="col-sm-9">
                  <input class="form-control" placeholder="Company Title" ng-model="info.client.name" required >
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label">Client's State of Business (Geographical Location):</label>
                <div class="col-sm-9">
                  <input  class="form-control" placeholder="State (No abbreviations)" ng-model="info.client.corporation" required >
                </div>
              </div>
              <div class="form-group">
                <label for="inputClientAddress" class="col-sm-3 control-label">Client Address:</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" id="inputClientAddress" ng-model="info.client.address" placeholder="Street, City, State Zipcode" required >
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label">Client Email:</label>
                <div class="col-sm-9"> 
                  <input type="email" class="form-control" placeholder="example@example.com" ng-model="info.client.email" required />
                </div>
              </div>

            </div>
          </div>
          <div class="panel panel-default">
            <div class="panel-body">
                <h3 style="font-weight:bold;">Project Info&nbsp;&nbsp;<small><em>(To be completed with project information)</em></small></h3>
                <div class="form-group">
                  <label for="inputCourse" class="col-sm-3 control-label">Project Title:</label>
                  <div class="col-sm-9">
                    <textarea type="text" class="form-control" id="inputCourse" placeholder="Title of eContent" ng-model="info.client.course"></textarea>
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputClientAddress" class="col-sm-3 control-label">Development Time:</label>
                  <div class="col-sm-9">
                    <div class="input-group">
                      <input type="text" class="form-control" id="inputClientComplete" placeholder="12 (Numbers only - do not add extra words or characters like # $ , etc)" ng-model="info.client.complete" required >
                      <span class="input-group-addon">weeks</span>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <div class="col-sm-offset-2 col-sm-10">
                    <div class="checkbox">
                      <label>
                        <input type="checkbox" ng-model="info.client.feedbackCheck" ng-init="info.client.feedbackCheck=false"> Implement the "Train Anyone" period (Delayed reviews affect the "Train Anyone" period).
                      </label>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label">Production State:</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" ng-init="info.client.performState='Illinois'" ng-model="info.client.performState" placeholder="Illinois (No abbreviations)" required >
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputClientAddress" class="col-sm-3 control-label">Production Cost:</label>
                  <div class="col-sm-9">
                    <div class="input-group">
                      <input type="number" class="form-control" placeholder="25000 (Numbers only - do not add extra words or characters like # $ , etc)" id="inputClientPayment" ng-model="info.client.payment" required >
                      <span class="input-group-addon">dollars</span>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputClientAddress" class="col-sm-3 control-label">Revision Rate:</label>
                  <div class="col-sm-9">
                    <div class="input-group">
                      <input type="number" class="form-control" id="inputClientPayment" placeholder="200 (Numbers only - do not add extra words or characters like # $ , etc)" ng-model="info.client.rate" required >
                      <span class="input-group-addon">dollars per hour</span>
                    </div>
                  </div>
                </div>
            </div>
          </div>
          </form>
          <div>    
            <p class="text-right"><button class="btn btn-primary" ng-disabled="form.$invalid" ng-click="info.showContract(1)">Next Step</button></p>
          </div>
        </div>

        <div style="display:none" ng-controller="listContractController as contractList">
          <div class="contractContent" ng-repeat="contract in contractList.contracts" ng-include="contract.url">
          </div>
        </div>

        <!-- ng-show="info.isDisplay" -->
        <div id="contract" ng-show="info.isDisplay">
          <div class="row" style="padding-top: 20px;">
            <div class="col-sm-4" style="height:450px!important;padding-left:0px" >
              <ul class="contractList2" style="height:450px!important" ng-controller="listContractController as contractList2">
                <li ng-repeat="contract in contractList2.contracts" ng-class='{active:contractList2.current($index)}' ng-click="contractList2.goTo($index)">
                    <div style="height:45px">{{contract.name}}</div>
                </li>
              </ul>
            </div>
            <div class="col-sm-8" >
              <div class="textarea" id="contractText">
                
              </div>
            </div>
          </div>
          <script>
            var current = 0;
            var slider = null;
            var contracts = [   {url:'assets/contractTemplate/NDA.html',name:'Mutual Confidentiality and Non-Disclosure Agreement'},
                                {url:'assets/contractTemplate/checklist.html',name:'Source Materials Checklist'},
                                {url:'assets/contractTemplate/eCDA.html',name:'eContent Development Agreement for Manufacturers'},
                                {url:'assets/contractTemplate/eCDD.html',name:'eContent Design Document (eCDD) - Exhibit A Example'},
                                {url:'assets/contractTemplate/exhibitB.html',name:'Payment Terms - Exhibit B'},
                                {url:'assets/contractTemplate/exhibitC.html',name:'Change Order Process - Exhibit C'},
                                {url:'assets/contractTemplate/ptracker.html',name:'Project Tracker Sample'},
                                {url:'assets/contractTemplate/audio.html',name:'Audio Script Sample'}];
            var contractContent = {};
            tinymce.init({
                  plugins: ["advlist lists charmap print preview code hr example save"],
                  selector: ".textarea",
                  menubar: "tools table format view insert edit",
                  toolbar:[
                    "undo redo | styleselect | bold italic | link image | alignleft aligncenter alignright | print"
                  ],
                  height: 500,
                  statusbar : false,
                  // theme_advanced_buttons3_add : "save",
                  // save_enablewhendirty : true,
                  // save_onsavecallback : "mysave"
                  setup : function(ed) {
                  ed.on('change', function(e) {
                      contractContent[current].content = tinymce.activeEditor.getContent();
                  });
  
                
                  }
                });
            // function mysave(){
            //   index = $('.editable').index();
            //   contractContent[index].content = editor.getContent();
            // }

            var app = angular.module('eContract',['ngSanitize']);
            app.controller('InfoController',function($scope){
              this.date = new Date();
              this.isDisplay = false;
              $scope.loading = false;
              $scope.isExport = false;
              //this.client.feedbackCheck = false;
              this.showContract = function(value){
                if(value == 0) {
                  this.isDisplay = false;
                } else {

                  editor = tinymce.get('contractText');
                  data = $('.contractContent').eq(0).html();
                  editor.setContent(data);
                  this.isDisplay = true;


                  $('.contractContent').each(function(index,value){
                    content = {name:contracts[index].name,content:$(this).html()};
                    contractContent[index] =content;
                  });
                  console.log(contractContent);
                }

              };

              this.saveContract = function(){
                this.loading = true;
                client = this.client.name;
                email = this.ignitor.email;
                title = this.ignitor.title;
                name = this.ignitor.name;
                project = this.client.course;
                client_email = this.client.email
                $.post("API.php?c=save",{
                  content:contractContent,
                  client:client,
                  client_email:client_email,
                  ignitor_email:email,
                  ignitor_title:title,
                  ignitor_name:name
                },function(data){
                  if(data){
                    $scope.isExport = true;
                    $scope.$apply(function(){
                        $scope.isExport = true;
                        $('#export').show();
                        $('#contract').hide();
                        $('#client_link').html('<a href="contract.php?id='+data.link+'">contract.php?id='+data.link+'</a>');
                        $('#email').attr('href','mailto:'+client_email+'?subject='+project+'&body='+
                          'Thank you for your interest in Ignitor Labs. In order to move forward with your project, please review and approve the electronic documents by clicking the link below. %0d%0a%0d%0a'+
                          'http://'+location.host+'/contract_gen/contract.php?id='+data.link+'%0d%0a%0d%0a'+
                          'To learn more about the eContract process, you can view the Welcome Guide by clicking below.%0d%0a%0d%0a'
                          +'http://'+location.host+'/contract_gen/eContractWelcome.pdf%0d%0a%0d%0a'+
                          // 'First you will have to read, initial, and sign the Non-Disclosure Agreement (NDA) as well as send over all available source materials requested on the Source Materials Checklist. In order to initial each document, you must scroll through the entire document. %0d%0a%0d%0a'+
                          // 'Once all available source materials are received and approved, Ignitor Labs will begin to draft an official eContent Development Document (eCDD).%0d%0a%0d%0a'+
                          // 'In order to make changes, please download the documents, mark the changes, and send them via email to the Instructional Designer at Ignitor Labs. The documents will be updated and an email will be sent to you. %0d%0a%0d%0a'+
                          // 'Upon receipt of the eCDD, we request you read, initial, and sign all remaining documents including the newly drafted eCDD and the eCDA. Please note that this eContract is a legally binding document. Once signed, all parties are contractually obligated to uphold the terms. We also ask that the eContract process be completed as quickly as possible in order to expedite the production of your project.%0d%0a%0d%0a'+
                          'If you have any questions or concerns about the content of the contract, please contact '+name+' ('+email+'), your representative from Ignitor Labs.%0d%0a%0d%0a'

                          );
                        $('#edit').attr('href','edit.php?id='+data.link);
                        $('#print').attr('href','assets/contracts/download.php?client='+data.folder);
                    });
                  }
                },"json");
              };





            });
              

            app.controller('listContractController',function(){
              this.contracts = contracts;
              this.current = function(value){
                return value == current;
              };
              this.goTo = function(value){
                current = value;
                editor = tinymce.get('contractText');
                data = $('.contractContent').eq(current).html();
                editor.setContent(data);
              };
            });

            app.directive(
              'dateInput',
              function(dateFilter) {
                  return {
                      require: 'ngModel',
                      template: '<input type="date"></input>',
                      replace: true,
                      link: function(scope, elm, attrs, ngModelCtrl) {
                          ngModelCtrl.$formatters.unshift(function (modelValue) {
                              return dateFilter(modelValue, 'yyyy-MM-dd');
                          });

                          ngModelCtrl.$parsers.unshift(function(viewValue) {
                              return new Date(viewValue);
                          });
                      },
                  };
              });
            app.controller('CheckController',function(){
              this.createNew = false;
              this.addNew = function(value){
                this.createNew=value;
              };
            });
          </script>
          <div class="row" style="padding-top:20px;">
            <div class="col-sm-6" style="padding-left:0;">
              <button ng-click="info.showContract(0)" class="btn btn-primary">Back</button>
            </p>
            </div>
            <div class="col-sm-6">
            <p class="text-right"><span class="text-danger" ng-show="info.loading">Loading...Please don't close or refresh this page.</span> 
              <button class="btn btn-primary" ng-click="info.saveContract();" ng-hide="info.loading">
                <span>Export All Documents</span>
              </button>
              <span ng-show="info.loading"><img src="assets/img/loading.gif" /></span>
            </p>
            </div>
          </div>
        </div>
        <div id="export" style="display:none">
          <p>You have successfully generated new contract documents for client <b>{{info.client.name}}</b>.</p>
          <hr></hr>
          <!--<h4>Please send this link to the client so that they can begin the approval process.</h4>
          <p id="client_link"></p> -->
          <p><a id="email" class="btn btn-success" href=""> Email to Client </a></p>
          <p><a id="edit" class="btn btn-warning" href=""> Edit </a></p>
          <p><a id="print" class="btn btn-primary" href=""> Export All Documents as a Zip File </a></p>
        </div>
      </div><!-- End of Tab -->



    </div> <!-- /container -->

    <div class="footer container">
      <p>&copy; Ignitor Labs 2014</p>
    </div>

  </body>
</html>
