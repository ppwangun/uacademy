'use strict';

angular.module('teachingunit')
        .component('courseProgramming',{
            controller: programmingCtrl,
            templateUrl: 'programmingtpl',
            
});
function programmingCtrl($timeout,$http,$location,$mdDialog,$scope,uiCalendarConfig){
    var $ctrl = this;
    $ctrl.isActivatedUeSelect = false;
    $scope.eventSources = [ 
        
        {
           
      events: [ // put the array in the `events` property
        {
            id : 1,
            resourceId: 'a',
          title  : 'event1',
          start  : '2024-02-09T08:30:00',
          end    : '2024-02-09T12:30:00',
          color: 'black',
        },
        {
            id : 3,
          title  : 'event2 \n Campus B A001',
          start  : '2024-02-09T12:30:00',
          end    : '2024-02-09T14:30:00'
        },
        {
          id : 3,
          title  : 'event3',
          start  : '2024-02-08T12:30:00',
        }
      ],
      //color: 'black',     // an option!
     //textColor: 'yellow' // an option!

    },
];
    
    $scope.alertEventOnClick = function(info)
  {
      console.log(info);
      alert("je suis dedans"+info.id)
  }  
    /* config object */
    $scope.uiConfig = {
      calendar:{
        //height: 450,
        height: 900,
        editable: true,
        header:{
          //left: 'month basicWeek basicDay agendaWeek agendaDay',
          left: ' month agendaWeek',
          center: 'title',
          right: 'today prev,next'
        },
        eventClick: $scope.alertEventOnClick,
        eventDrop: $scope.alertOnDrop,
        eventResize: $scope.alertOnResize,
        
      }
    };    

 $ctrl.init = function(){

    //Loading classes of study asynchronously
    $ctrl.query = function(classe)
    {
       var  dataString = {id: classe},
          config = {
            params: dataString,
            headers : {'Accept' : 'application/json; charset=utf-8'}
            };
    
            return  $http.get('classes',config).then(function(response){
                   return response.data[0];
                });
     };
     
         

   
 };
 
 $ctrl.asignedSemToClasse = function(class_code){
    $ctrl.semesters = [];
    var data = {id: class_code};
    var config = {
    params: data,
    headers : {'Accept' : 'application/json'}
    };      
    $http.get('assignsemtoclass',config).then(function(response){
        $ctrl.semesters = response.data[0];
        $ctrl.isActivatedUeSelect = true;
                
    });
};
 
 $ctrl.loadUE = function(classe,sem_id){
                $ctrl.selectedSubject =null;
                $ctrl.subjects = [];
                $ctrl.ues = [];
                var data = {id: {classe_id:classe.id,sem_id:sem_id}};
                var config = {
                params: data,
                headers : {'Accept' : 'application/json'}
                };
                //Loading selected class information for update
                $timeout(
                        
                        $http.get('teachingunit',config).then(
     
                        function(response){
                                $ctrl.ues=response.data[0];
                                $ctrl.isActivatedMatiereSelect = true;
                      

                        }),1000);

 };
 
 
  //Looad all student who are registered to the subject
 //Load all subjects associated with the UE as well
 $ctrl.loadPedagogicalRegStd = function(selectedUe,classe){
                            $ctrl.isActivatedMatiereSelect = false;
                            $ctrl.isMatiereRequired = false;
                            
                            $scope.items = [];
                            if($ctrl.selectedSubject) var data ={id: {ueId: $ctrl.selectedUe.id,subjectId: $ctrl.selectedSubject.id,classId : classe.id}};
                            else    var data ={id: {ueId: $ctrl.selectedUe.id,classId : classe.id}};
                             
                            var i;
                            var config = {
                            params: data,
                            headers : {'Accept' : 'application/json'}
                            };


                                data ={id: $ctrl.selectedUe.id}
                                    var config = {
                                    params: data,
                                    headers : {'Accept' : 'application/json'}
                                    };   
                                    if($ctrl.subjects<=0)
                                        $http.get('subjectbyue',config).then(function(response){

                                            $ctrl.subjects = response.data[0];
 

                                        });
                                                $ctrl.isActivatedMatiereSelect = true;
                                                $ctrl.isMatiereRequired = true;                                    
  
                        
                           
                        };
                        

     
    /*--------------------------------------------------------------------------
     *--------------------------- updating curriculum---------------------------
     *----------------------------------------------------------------------- */
    $ctrl.loadData = function(ev){
        $scope.isUpdate= true;
       
       
        $mdDialog.show({
          controller: DialogController,
          templateUrl: 'js/my_js/globalconfig/loadteachingunit.html',
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
  function DialogController($scope, $mdDialog) {
      
$scope.subject = {code:'',name:'',credits: '',hours_vol:'',cm_hrs:'',tp_hrs:'',td_hrs:'',ue_classe_id: '', ue_id:''};

$scope.uploadSubject = function(){
 console.log("je suis dednas")
    var fd = new FormData();
    var files = document.getElementById('file').files[0];
    fd.append('file',files);

    // AJAX request
    $http({
     method: 'post',
     url: 'importSubject',
     data: fd,
     headers: {'Content-Type': undefined},
    }).then(function successCallback(response) { 
      // Store response data
      $scope.response = response.data[0];
      response.data[0]?toastr.success('Import effectué avec succès'):toastr.error('Type de fichier incorrect', 'Erreur');
      
    } ,function errorCallback(){
        toastr.error('Problème survenu lors de l\'import du fichier', 'Erreur');
    });
 };

 $scope.createSubject = function(){
     
     $http.post('subject',$scope.subject).then(function successCallback(response){
         $scope.subject=response.data[0];
         $mdDialog.cancel();
         
     }, function errorCallback(response){
        alert(" Une erreur inattendue s'est produite") ;
     });
 };
 
 $scope.updateCycle = function(){
        var data = {id: $scope.cycle.id,data:$scope.cycle}; 
        var config = {
        params: data,
        headers : {'Accept' : 'application/json'}
      };
       $ctrl.degree.filiere_id=$ctrl.selectedItem.id;
       $http.put('cycle',data,config).then(
            function successCallback(response){
                //reinitialise form
                //$location.path("/degree");
                $mdDialog.cancel();
            },
            function errorCallback(response){
                alert("Une erreur inattendue s'est produite");
           
            });
     
 };
 

      

  }
  
      $scope.cancel = function() {
      //$scope.faculties=[];
    
      $scope.subject = {code:'',name:'',credits: '',hours_vol:'',class_id: '',cm_hrs:'',tp_hrs:'',td_hrs:''};
      $mdDialog.cancel();
    };

    $scope.answer = function(answer) {
      //$scope.faculties=[];
      //$scope.filiere={nom:'',code:'',fac_id:$scope.faculty.id,status:''};
      $mdDialog.hide(answer);
    };   
    
       
};


