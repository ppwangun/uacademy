'use strict';

angular.module('payment')
        .component('paymentList',{
            templateUrl: 'payments',
            controller: paymentCtrl 
});
function paymentCtrl($timeout,$http,$location,$mdDialog,DTOptionsBuilder,DTColumnDefBuilder,$scope){
    var $ctrl = this;
        $ctrl.payments = [];
    
    $ctrl.init = function(){

    $timeout(
     $http.get('paymentlist').then(function(response){
         
         $ctrl.payments = response.data[0];
     }),500);
 };
 
 
  $ctrl.redirect= function(id){
     $location.path("/paymentdetails/"+id)
     
 };
 
  $ctrl.dtOptions = DTOptionsBuilder.newOptions()
     .withButtons([
            //'columnsToggle',
            //'colvis',
            {extend:'copy',text:'Copier dans le presse papier',className: 'btn btn-default'},
            {extend:'print',text:"Imprimer",className: 'btn btn-default'}

        ])
        .withPaginationType('full_numbers')
        .withDisplayLength(100)
         /* .withFixedHeader({
    top: true
  })*/;
  
    $ctrl.dtColumnDefs = [
    DTColumnDefBuilder.newColumnDef(0),
    DTColumnDefBuilder.newColumnDef(1),
    DTColumnDefBuilder.newColumnDef(2),
    DTColumnDefBuilder.newColumnDef(3),
    DTColumnDefBuilder.newColumnDef(4).notSortable(),
    DTColumnDefBuilder.newColumnDef(5),
    DTColumnDefBuilder.newColumnDef(6)
  ];
     
    /*--------------------------------------------------------------------------
     *---------------------------Updating Student ---------------------------------
     *----------------------------------------------------------------------- */
    $ctrl.importBalance = function(std,ev){
        $scope.student = std;
        $mdDialog.show({
          controller: DialogController,
          templateUrl: 'js/app/payment/importBalance.html',
          parent: angular.element(document.body),
         // parent: angular.element(document.querySelector('#component-tpl')),
          scope: $scope,
          preserveScope: true,
          autoWrap: false,
          targetEvent: ev,
          clickOutsideToClose:false,
          fullscreen: true // Only for -xs, -sm breakpoints.
        })
        .then(function(answer) {
          
          $ctrl.status = 'You said the information was "' + answer + '".';
        }, function() {
          $ctrl.status = 'You cancelled the dialog.';
        });        
    }; 
    
 /*--------------------------------------------------------------------------
     *---------------------------Loading files ---------------------------------
     *----------------------------------------------------------------------- */
    $ctrl.loadStdPayments = function(ev){
        $scope.isUpdate= true;
       
       
        $mdDialog.show({
          controller: DialogController,
          templateUrl: 'js/app/payment/uploadStdPayments.html',
          parent: angular.element(document.body),
         // parent: angular.element(document.querySelector('#component-tpl')),
          scope: $scope,
          preserveScope: true,
          autoWrap: false,
          targetEvent: ev,
          clickOutsideToClose:false,
          fullscreen: true // Only for -xs, -sm breakpoints.
        })
        .then(function(answer) {
          
          $ctrl.status = 'You said the information was "' + answer + '".';
        }, function() {
          $ctrl.status = 'You cancelled the dialog.';
        });        
    }; 
    
 /*--------------------------------------------------------------------------
     *---------------------------Loading files ---------------------------------
     *----------------------------------------------------------------------- */
    $ctrl.loadStdDotation = function(ev){
        $scope.isUpdate= true;
       
       
        $mdDialog.show({
          controller: DialogController,
          templateUrl: 'js/app/payment/uploadStdDotation.html',
          parent: angular.element(document.body),
         // parent: angular.element(document.querySelector('#component-tpl')),
          scope: $scope,
          preserveScope: true,
          autoWrap: false,
          targetEvent: ev,
          clickOutsideToClose:false,
          fullscreen: true // Only for -xs, -sm breakpoints.
        })
        .then(function(answer) {
          
          $ctrl.status = 'You said the information was "' + answer + '".';
        }, function() {
          $ctrl.status = 'You cancelled the dialog.';
        });        
    };     
    
 //Dialog Controller
  function DialogController($scope, $mdDialog,toastr) {
      

$scope.uploadBalance = function(){
 
    var fd = new FormData();
    var files = document.getElementById('file').files[0];
    fd.append('file',files);

    // AJAX request
    $http({
     method: 'post',
     url: 'importBalance',
     data: fd,
     headers: {'Content-Type': undefined},
    }).then(function successCallback(response) { 
      // Store response data
      $scope.response = response.data[0];
      response.data[0]?toastr.success('Import effectué avec succès'):toastr.error('Type de fichier incorrect', 'Erreur');
      response.data[0]?$mdDialog.cancel():toastr.error('Erreur pendant le processus d\'importation', 'Erreur');
      
    } ,function errorCallback(){
        toastr.error('Problème survenu lors de l\'import du fichier', 'Erreur');
    });
 };
 
$scope.uploadStdPayments = function(){
 
    var fd = new FormData();
    var files = document.getElementById('file').files[0];
    fd.append('file',files);

    // AJAX request
    $http({
     method: 'post',
     url: 'importPayments',
     data: fd,
     headers: {'Content-Type': undefined},
    }).then(function successCallback(response) { 
      // Store response data
      $scope.response = response.data[0];
      response.data[0]?toastr.success('Import effectué avec succès'):toastr.error('Type de fichier incorrect', 'Erreur');
      response.data[0]?$mdDialog.cancel():toastr.error('Erreur pendant le processus d\'importation', 'Erreur');
      
    } ,function errorCallback(){
        toastr.error('Problème survenu lors de l\'import du fichier', 'Erreur');
    });
 }; 
 
 
 $scope.uploadStdDotation = function(){
 
    var fd = new FormData();
    var files = document.getElementById('file').files[0];
    fd.append('file',files);

    // AJAX request
    $http({
     method: 'post',
     url: 'importDotations',
     data: fd,
     headers: {'Content-Type': undefined},
    }).then(function successCallback(response) { 
      // Store response data
      $scope.response = response.data[0];
      response.data[0]?toastr.success('Import effectué avec succès'):toastr.error('Type de fichier incorrect', 'Erreur');
      response.data[0]?$mdDialog.cancel():toastr.error('Erreur pendant le processus d\'importation', 'Erreur');
      
    } ,function errorCallback(){
        toastr.error('Problème survenu lors de l\'import du fichier', 'Erreur');
    });
 }; 
 }

    

  
      $scope.cancel = function() {
      //$scope.faculties=[];

      $mdDialog.cancel();
    };

    $scope.answer = function(answer) {
      //$scope.faculties=[];
      //$scope.filiere={nom:'',code:'',fac_id:$scope.faculty.id,status:''};
      $mdDialog.hide(answer);
    };    
};


