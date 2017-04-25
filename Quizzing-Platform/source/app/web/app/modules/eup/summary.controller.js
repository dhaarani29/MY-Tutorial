/**
 * @namespace EUP
 * @desc Quiz summary page controller 
 * @memberOf Controller
 * @author Jagadeeshraj V S
 */

(function() {
    'use strict';

    angular.module('eupapp').controller('TestSummaryController', function($rootScope, $scope, config, $filter, $window, $timeout, $log, eupService) {
        var vm = this;
        vm.stateType = $rootScope.currentState.split(".")[1];
        vm.chart = {};

        //Check the action type and authentication
        if (vm.stateType == "summary" && angular.isDefined($rootScope.token) && eupService.decodeToken($rootScope.token)) {
            vm.testInstanceId = $rootScope.$stateParams.testInstanceId;
            vm.testId = $rootScope.$stateParams.testId;
            vm.table = {};
            vm.table.dataPerPage = config.minRecordsPerPageDefault; //Default data per page
            vm.table.dataPerPageOptions = config.minRecordsPerPage; //Default date per page options
        } else if (angular.isUndefined($rootScope.token) || eupService.decodeToken($rootScope.token)) {
            //If the authentication token is missing/invalid 
            $log.error("Authentication error")
            $rootScope.$state.go("eup.401")
        } else if (angular.isUndefined(vm.id)) { //If action type is invalid
            $log.error("Invalid State/Action Type")
            $rootScope.$state.go("eup.404")
        }

        //Create the chart based on quiz progress details
        var getChartDetails = function() {
            vm.chart.type = "PieChart"; //Chart Type
            vm.chart.options = {
                is3D: true,
                slices: {
                    0: { color: '#85BC20' },
                    1: { color: '#E5202E' },
                    2: { color: '#007AC3' }
                }
            };
            //Chart Options
            vm.chart.data = {
                "cols": [
                    { id: "t", label: "Questions", type: "string" },
                    { id: "s", label: "Count", type: "number" }
                ],
                "rows": [{
                    c: [
                        { v: "Correct" },
                        { v: vm.summary.totalCorrect },
                    ]
                }, {
                    c: [
                        { v: "In-Correct" },
                        { v: vm.summary.totalIncorrect },
                    ]
                }, {
                    c: [
                        { v: "Unattempted" },
                        { v: vm.summary.totalUnattempted },
                    ]
                }]
            };
        }

        //Summary page table pipe function. 
        vm.summaryPaginationPipe = function(tableState) {
            var params = {};
            vm.showLoader = true;

            vm.summary = {} //remove previous data

            if (angular.isDefined(vm.prevParentId))
                delete vm.prevParentId; //Reset previous child parent to show parent details

            //Finding and assigning current page number
            if (tableState.pagination.start !== 0)
                vm.pageNumber = Math.ceil(tableState.pagination.start / vm.table.dataPerPage) + 1;
            else
                vm.pageNumber = 1;

            params.page = vm.pageNumber, params.perPage = vm.table.dataPerPage;
            $log.debug("Passed Parameters:" + JSON.stringify(params))

            //call item service to get list of item details 
            eupService.getSummaryDetails(vm.testId, vm.testInstanceId, params).then(function(response) {
                if (response.status === 200) {
                    vm.summary = response.data.details;
                    vm.table.totalRecords = vm.summary.totalQuestions;
                    tableState.pagination.numberOfPages = Math.ceil(vm.table.totalRecords / vm.table.dataPerPage);
                    getChartDetails(); //Create pie chart
                    vm.showLoader = false; //Hide loader

                    $log.debug(response.results)
                    $log.debug("Total Result:" + vm.table.totalRecords)
                } else if (response.status === 401) {
                    $log.error("Authentication error")
                    $rootScope.$state.go("eup.401")
                }
            });
        };

        //Get the template name dynacmically based on item type which will be used for rendering item
        vm.getTemplateName = function(item) {
            if (angular.isDefined(item.parentLabelText)) { //If any parent exist then it is MEDICAL/CLINICAL
                return "medical-question-preview";
            } else if (item.labelText == 'GRAPHIC_OPTION') //For GRAPHIC_OPTION items
                return "graphic-option-preview"
            else
                return "multiple-choice-preview"; //Default template type 

        };

        //Gets the choice label class name dynamically based on its status
        vm.getChoiceClassName = function(choiceDetails, userResponse) {
            if (choiceDetails.correct)
                return "wkqp-icon-checkmark green-checkmark";
            else if (!choiceDetails.correct && userResponse.indexOf(choiceDetails.choiceId) != -1)
                return "wkqp-icon-close wrong-checkmark";
            else
                return "wkqp-icon-checkmark disabled-checkmark";
        }

        //Convert seconds to respective type
        vm.convertTime = function(seconds, type) {
            return eupService.convertTime(seconds, type);
        }

        vm.getAnswers = function(itemDetails) {
            itemDetails.correctAnswer = "";
            itemDetails.selectedAnswer = "";
            itemDetails.rationale = "";
            itemDetails.correct = false;
            var correctIds = [],
                selectedIds = [];
            //In case of multiple correct answers create a comma seperated answer string
            angular.forEach(itemDetails.choiceInteraction.simpleChoices, function(choice, key) {
                if (choice.correct == true) {
                    correctIds.push(choice.choiceId);
                    if (itemDetails.correctAnswer != "")
                        itemDetails.correctAnswer = itemDetails.correctAnswer + ',' + choice.label;
                    else if (itemDetails.labelText == 'GRAPHIC_OPTION')
                        itemDetails.correctAnswer = choice.value;
                    else
                        itemDetails.correctAnswer = choice.label;
                }
                if (itemDetails.userResponse.indexOf(choice.choiceId) != -1) {
                    selectedIds.push(choice.choiceId);
                    if (itemDetails.selectedAnswer != "")
                        itemDetails.selectedAnswer = itemDetails.selectedAnswer + ',' + choice.label;
                    else if (itemDetails.labelText == 'GRAPHIC_OPTION')
                        itemDetails.selectedAnswer = choice.value;
                    else
                        itemDetails.selectedAnswer = choice.label;
                }
            });
            //Check whether the choosen answer is correct
            if (angular.equals(correctIds, selectedIds)) {
                itemDetails.correct = true;
            }

            //Based on correct/incorrect answer assign the feedback details
            if (itemDetails.correct) { //If answer is correct then assign ccorect rationale
                itemDetails.rationale = itemDetails.modelFeedback[0].feedbackText;
            } else { //If answer is wrong then assign inccorect rationale
                itemDetails.rationale = itemDetails.modelFeedback[1].feedbackText;
            }

            //Assign basic asset details which applicable for asset related wuestion types
            if (angular.isDefined(itemDetails.parentLabelText) && (itemDetails.parentLabelText == 'MEDICAL_CASE' || itemDetails.parentLabelText == 'CLINICAL_SYMPTOMS')) {
                if (angular.isUndefined(vm.prevParentId)) {
                    vm.prevParentId = itemDetails.parentId //Used for grouping medical/clinical child questions.
                    itemDetails.showParentItemDetails = true;
                } else if (angular.isDefined(vm.prevParentId) && vm.prevParentId != itemDetails.parentId) {
                    vm.prevParentId = itemDetails.parentId //Used for grouping medical/clinical child questions.
                    itemDetails.showParentItemDetails = true;
                } else {
                    itemDetails.showParentItemDetails = false;
                }
                if (angular.isObject(itemDetails.parentDetails.assets)) {
                    itemDetails.assetTypeId = itemDetails.parentDetails.assets.assetTypeId;
                    itemDetails.itemAssetPath = '/' + itemDetails.parentDetails.assets.assetPath + "/" + itemDetails.parentDetails.assets.assetName;
                    console.log(itemDetails.assetTypeId + itemDetails.itemAssetPath)
                }
            } else if ((itemDetails.labelText == 'IMAGE_INTEGRATION' || itemDetails.labelText == 'VIDEO_QUESTIONS' || itemDetails.labelText == 'CLINICAL_SYMPTOMS' || itemDetails.labelText == 'MEDICAL_CASE') && angular.isDefined(itemDetails.assets)) {
                itemDetails.assetTypeId = itemDetails.assets["0"].assetTypeId;
                itemDetails.itemAssetPath = '/' + itemDetails.assets["0"].assetPath + "/" + itemDetails.assets["0"].assetName;
            }


        }
    });
})();
