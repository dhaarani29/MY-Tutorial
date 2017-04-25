(function() {
    'use strict';

    var itemsApp = angular.module('app.items')
    itemsApp.directive('itemPreview', function($rootScope, $log, itemsService) {
        var directive = {}; //directive object

        directive.restrict = 'E'; //restrict directive only for Element
        directive.require = [];
        directive.templateUrl = "app/modules/items/partials/items-preview.html"; //template url.
        directive.transclude = true;
        directive.bindToController = true;
        directive.scope = {
            itemId: "=",
            itemDetails: "="
        };
        directive.controllerAs = 'vm';
        directive.controller = function($scope, $element, $attrs) {
            var vm = this;
            vm.pageTitle = "PAGE_TITLE.ITEM_PREVIEW";

            if (angular.isUndefined(vm.itemId) || vm.itemId == "") //Check if itemId exist
                vm.pageError = true; //Show data not available error
            else {
                vm.showLoader = true;
                var getCorrectAnswer = function(answerDetails) {
                    //vm.itemDetails.correctAnswer = "";
                    //In case of multiple correct answers create a comma seperated answer string
                    angular.forEach(answerDetails, function(choice, key) {
                        if (choice.correct == true) {
                            if (angular.isDefined(vm.itemDetails.correctAnswer))
                                vm.itemDetails.correctAnswer = vm.itemDetails.correctAnswer + ',' + choice.label;
                            else if (vm.itemDetails.labelText == 'GRAPHIC_OPTION')
                                vm.itemDetails.correctAnswer = choice.value;
                            else
                                vm.itemDetails.correctAnswer = choice.label;
                        }
                    });
                }

                var assignOtherDetails = function() {
                    if (vm.itemDetails.labelText == 'GRAPHIC_OPTION')
                        vm.itemtypeTemplate = "graphic-option-preview"
                    else if (vm.itemDetails.labelText == 'CLINICAL_SYMPTOMS' || vm.itemDetails.labelText == 'MEDICAL_CASE') {
                        if (vm.itemDetails.labelText == 'MEDICAL_CASE')
                            vm.itemtypeTemplate = "medical-question-preview";
                        else
                            vm.itemtypeTemplate = "clinical-question-preview";

                        //Get list of child question details
                        var params = { userId: $rootScope.userId, parent: vm.itemId, sort: "+childOrder" };
                        itemsService.getItemsDetails(params).then(function(response) {
                            vm.itemChildList = response.results.data;
                            if (response.results.total > 0) {
                                vm.getChildDetail(0);
                            } else {
                                vm.showLoader = false;
                            }
                        });
                    } else
                        vm.itemtypeTemplate = "multiple-choice-preview"

                    if (vm.itemDetails.labelText != 'CLINICAL_SYMPTOMS' && vm.itemDetails.labelText != 'MEDICAL_CASE') {
                        getCorrectAnswer(vm.itemDetails.choiceInteraction.simpleChoices);
                    }
                    //Assign basic asset details which applicable for asset related wuestion types
                    if ((vm.itemDetails.labelText == 'IMAGE_INTEGRATION' || vm.itemDetails.labelText == 'VIDEO_QUESTIONS' || vm.itemDetails.labelText == 'CLINICAL_SYMPTOMS' || vm.itemDetails.labelText == 'MEDICAL_CASE') && angular.isDefined(vm.itemDetails.assets)) {
                        vm.assetTypeId = vm.itemDetails.assets["0"].assetTypeId;
                        vm.itemAssetPath = '/' + vm.itemDetails.assets["0"].assetPath + "/" + vm.itemDetails.assets["0"].assetName;
                    }

                }

                vm.getChildDetail = function(index) {
                    vm.showLoader = true;
                    vm.currentChild = index;
                    var childId = vm.itemChildList[index].id;
                    delete vm.itemDetails.correctAnswer;
                    itemsService.getItemById(childId).then(function(response) { //Get item detail from server based on item id
                        if (response.status === 200) {
                            vm.itemChildDetails = response.data;
                            $log.debug("Item details from server:", vm.itemDetails);
                            getCorrectAnswer(vm.itemChildDetails.choiceInteraction.simpleChoices);
                        } else if (response.status === 404) { //Error in case of data not found on server
                            if (response.data.code == "2007") {
                                //vm.pageError = true;
                            }
                        }
                        vm.showLoader = false;
                    });
                }

                if (angular.isUndefined(vm.itemDetails)) { //Check if item details already binded
                    itemsService.getItemById(vm.itemId).then(function(response) { //Get item detail from server based on item id
                        if (response.status === 200) {
                            vm.itemDetails = response.data;
                            assignOtherDetails();
                            $log.debug("Item details from server:", vm.itemDetails);
                        } else if (response.status === 404) { //Error in case of data not found on server
                            if (response.data.code == "2007") {
                                vm.pageError = true;
                                vm.showLoader = false;
                            }
                        }
                        if (vm.itemDetails.labelText != 'CLINICAL_SYMPTOMS' && vm.itemDetails.labelText != 'MEDICAL_CASE')
                            vm.showLoader = false;
                    });
                } else {
                    assignOtherDetails();
                    vm.showLoader = false;
                }
            }


        };
        return directive;
    });
})();
