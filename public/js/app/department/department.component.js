'use strict';

angular.module('department')
        .component('departmentList',{
            templateUrl: 'departmentpl',
            controller: departmentCtrl 
});

angular.module('department')
        .component('departmentDetails',{
            templateUrl: 'newDpt',
            controller: departmentCtrl 
});

function departmentCtrl($scope,$http,$timeout,$mdDialog,$location){
    var $ctrl=this;
    $scope.faculties=null;
    $scope.faculty={name:'',id:''};
    $scope.dpt={name:'',code:'',fac_id:$scope.faculty.id,status:''};
    $scope.dpts = [];
    $scope.dptId = null;
     /*--------------------------------------------------------------------------
     *--------------------------- create new department   ---------------------
     *----------------------------------------------------------------------- */    
    
      $ctrl.redirect= function(id){

     $location.path("/newDpt/"+id);
     $scope.dptId=id;

    };
     $scope.init = function(){
    /*--------------------------------------------------------------------------
     *--------------------------- Load faculties--------------------------------
     *----------------------------------------------------------------------- */

    $http.get('faculty').then(
        function(response){
        $scope.faculties = response.data[0];
    });
         
        
    }
 
     /*--------------------------------------------------------------------------
     *--------------------------- loading all filières   ---------------------
     *----------------------------------------------------------------------- */
     $http.get('filiere').then(function(response){
        $scope.filieres=response.data[0];  
     });
    
    /*--------------------------------------------------------------------------
     *--------------------------- loading form for creation---------------------
     *----------------------------------------------------------------------- */
    $ctrl.addFiliere = function(ev) {
        $mdDialog.show({
          controller: DialogController,
          templateUrl: 'js/my_js/globalconfig/filiereformtpl.html',
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
     *--------------------------- create filiere---------------------
     *----------------------------------------------------------------------- */
    $scope.createFilere = function(){
        $http.post('filiere',$scope.filiere).then(
                function successCallback(response){
                        
                          $scope.filieres.push($scope.filiere);
                          $scope.filiere={nom:'',code:'',fac_id:''};
                          
                          $mdDialog.cancel();
                },
                function errorCallback(response){
                    alert("une erreur inattendue s'est produite");
                    
                });
        
    };
     /*-------------------------------------------------------------------------
     *--------------------------- updating filiere------------------------------
     *----------------------------------------------------------------------- */
            $scope.updateFiliere=function(fil,ev){
                $scope.filiere= fil;

            $mdDialog.show({
              controller: DialogController,
              templateUrl: 'js/my_js/globalconfig/filiereupdateformtpl.html',
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
        }
    
     /*-------------------------------------------------------------------------
     *--------------------------- deleting filiere------------------------------
     *----------------------------------------------------------------------- */
    
      $scope.deleteFiliere= function(fil,ev)
      {
      var data = {id: fil.id}; 
      var config = {
      params: data,
      headers : {'Accept' : 'application/json'}
      };

// Preparing the confirm windows
      var confirm = $mdDialog.confirm()
            .title('Voulez vous vraiment supprimer?')
            .textContent('Toutes les données associées à cette information seront perdues')
             // .ariaLabel('Lucky day')
            .targetEvent(ev)
            .ok('Supprimer')
            .cancel('Annuler');
//open de confirm window
    $mdDialog.show(confirm).then(function() {
        //in case delete is pressee excute  the delete backend 
        $http.delete('filiere',config).then(
          function successCallback(response){
              //check the index of the current object in the array
              var x;
              var index = $scope.filieres.findIndex(x > x.id === fil.id);
              //remove the current object from the array
              $scope.filieres.splice(index,1);

         },
        function errorCallback(response){

            });
    }, function() {
     // $scope.status = 'You decided to keep your debt.';
    });
     
  };
    
    /*--------------------------------------------------------------------------
     *--------------------------- Load faculties--------------------------------
     *----------------------------------------------------------------------- */
    $http.get('faculty').then(
        function(response){
        $scope.faculties = response.data[0];
    });
         
    $scope.loadFaculties = function(){
        return $scope.faculties;
    };
    
  //Dialog Controller
  function DialogController($scope, $mdDialog) {
      
   /* $scope.faculty = {name:'', code:'',respo:'',created_date:'',school_id: $ctrl.school.id};       
    $scope.createFaculty = function() {
        $http.post('faculty',$scope.faculty)
            .then(function succesCallback(response)
            {
                 //$ctrl.school=response.data[0];
                console.log(response.data[0]);
                $ctrl.faculties= response.data[0];
                $mdDialog.cancel();
                
            },
            function errorCallback(response) {
                  // called asynchronously if an error occurs
                  // or server returns response with an error status.
                 
                  alert(response.data);
                });       
    };*/

    $scope.cancel = function() {
      //$scope.faculties=[];
      
      $scope.filiere={nom:'',code:'',fac_id:$scope.faculty.id,status:''};
      $mdDialog.cancel();
    };

    $scope.answer = function(answer) {
      //$scope.faculties=[];
      $scope.filiere={nom:'',code:'',fac_id:$scope.faculty.id,status:''};
      $mdDialog.hide(answer);
    };
    };
}