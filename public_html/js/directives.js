App.directive('loginForm',function(){
    return{
        restrict:'E',
        templateUrl:'templates/loginform.html',
        replace:true
    };
});
App.directive('adminPage',function(){
    return{
        restrict:'E',
        templateUrl:'templates/adminpage.html',
        replace:true
    };
});
App.directive('curatorPage',function(){
    return{
        restrict:'E',
        templateUrl:'templates/curatorpage.html',
        replace:true
    };
});

App.directive('curatorExtended',function(){
    return{
        restrict:'E',
        templateUrl:'templates/curatorextended.html',
        replace:true
    };
});

App.directive('courseExtended',function(){
    return{
        restrict:'E',
        templateUrl:'templates/coursextended.html',
        replace:true
    };
});

App.directive('teacherExtended',function(){
    return{
        restrict:'E',
        templateUrl:'templates/teacherextended.html',
        replace:true
    };
});

App.directive('testExtended',function(){
    return{
        restrict:'E',
        templateUrl:'templates/testextended.html',
        replace:true
    };
});

App.directive('questionExtended',function(){
    return{
        restrict:'E',
        templateUrl:'templates/questionextended.html',
        replace:true
    };
});

//обработка модели чекбоксов, корректная связь boolean и int 
App.directive('intbooleanvalidation',function(){
    return{
        require:'ngModel',
        restrict:'A',
        link: function($scope, $element, $attrs, modelCtrl) {
            modelCtrl.$formatters.push(function (modelValue) {
                if(modelValue==1)
                    return true;
                else
                    return false;
            });

            modelCtrl.$parsers.push(function (viewValue) {
                if(viewValue==true)
                    return 1;
                else
                    return 0;
            });               
        }
    };
});


