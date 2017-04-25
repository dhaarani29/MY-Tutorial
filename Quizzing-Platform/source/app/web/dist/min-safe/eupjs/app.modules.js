/**
 * @namespace EUP
 * @desc All the webservice calls and services for eup will be handled in this factory 
 * @memberOf Factories
 * @author Jagadeeshraj V S
 */
(function() {
    'use strict';

    angular.module('eupapp')
        .factory('eupService', ['$rootScope', '$log', '$http', '$filter', '$window', '$q', 'jwtHelper', 'config', function($rootScope, $log, $http, $filter, $window, $q, jwtHelper, config) {
            var obj = {};

            //Capture current item user answer and time spent
            obj.saveAnswer = function(itemDetail, testInstanceId, attemptDetails) {
                return $http.post(config.apiUrl + 'tests/instance/' + testInstanceId + '/items/' + itemDetail.itemId + '/version/' + itemDetail.version, attemptDetails)
                    .success(function(response) {
                        if (response.status == 201)
                            return true;
                        else
                            return false;
                    })
                    .error(function(response, status) {
                        $log.error(response, status) //Log custom errors
                        return response;
                    })
                    .catch(function(response) {
                        $log.debug(response)
                        return response;
                    });
            };

            //call to api to get basic test details depending on primary key id
            obj.getTestById = function(id, version) {
                return $http.get(config.apiUrl + 'tests/' + id, { params: { version: version } })
                    .success(function(response) {
                        return response;
                    })
                    .error(function(response, status) {
                        $log.error(response, status) //Log custom errors
                        return response;
                    })
                    .catch(function(response) {
                        //response.data["success"] = false;
                        $log.debug("Data not found", response)
                        return response;
                    });
            };

            //call to api to get item details based on test instance and item id
            obj.getTestInstanceItemById = function(itemDetail, testInstanceId) {
                var apiUrl = config.apiUrl + 'tests/instance/' + testInstanceId + '/items';

                if (itemDetail && itemDetail.itemId && itemDetail.version)
                    apiUrl = config.apiUrl + 'tests/instance/' + testInstanceId + '/items/' + itemDetail.itemId + '/version/' + itemDetail.version;


                return $http.get(apiUrl)
                    .success(function(response) {
                        return response;
                    })
                    .error(function(response, status) {
                        $log.error(response, status) //Log custom errors
                        return response;
                    })
                    .catch(function(response) {
                        //response.data["success"] = false;
                        $log.debug("Data not found", response)
                        return response;
                    });
            };

            //decode jwt token and assign details to scope
            obj.decodeToken = function(token) {
                if (angular.isUndefined(token) || token.split('.').length != 3)
                    return false;

                $rootScope.userDetails = jwtHelper.decodeToken(token);
                console.log($rootScope.userDetails)
                if (angular.isDefined($rootScope.userDetails.clientUserId))
                    return true;
                else
                    return false;
            }

            //Convert seconds to respective type
            obj.convertTime = function(sec, type) {
                if (type == "min") {
                    return $filter('number')(sec / 60, 2) + "min";
                } else if (type == "time") {
                    if (angular.isDefined(sec) && angular.isNumber(sec)) {
                        var hours = $window.Math.floor(sec / 3600, 0);
                        var minutes = $window.Math.floor((sec - (hours * 3600)) / 60, 0);
                        var seconds = sec - (hours * 3600) - (minutes * 60);
                        if (hours < 10) { hours = "0" + hours; }
                        if (minutes < 10) { minutes = "0" + minutes; }
                        if (seconds < 10) { seconds = "0" + seconds; }

                        if (hours != "00")
                            return hours + ':' + minutes + ':' + seconds;
                        else
                            return minutes + ':' + seconds;
                    } else
                        return "00:00";
                }
            }

            //call to api to get test progress details
            obj.getTestProgress = function(testId, testInstanceId) {
                return $http.get(config.apiUrl + 'tests/' + testId + '/instances/' + testInstanceId, { params: { 'summary': true } })
                    .success(function(response) {
                        return response;
                    })
                    .error(function(response, status) {
                        $log.error(response, status) //Log custom errors
                        return response;
                    })
                    .catch(function(response) {
                        //response.data["success"] = false;
                        $log.debug("Data not found", response)
                        return response;
                    });
            };

            //call to api to get list of item details based on pagnination or search filters
            obj.getSummaryDetails = function(testId, testInstanceId, params) {
                var deferred = $q.defer();

                //$http call to api endpoints to get list of item details
                return $http.get(config.apiUrl + 'tests/' + testId + '/instances/' + testInstanceId + '/summary', { params: params })
                    .then(function(response) {
                        deferred.resolve(response);
                        return deferred.promise;
                    })
            };

            return obj;
        }]);
})();

/**
 * @namespace EUP
 * @desc Quiz engine controller 
 * @memberOf Controller
 * @author Jagadeeshraj V S
 */
(function() {
    'use strict';

    angular.module('eupapp').controller('TestEngineController', ['$rootScope', '$scope', '$uibModal', '$filter', '$window', '$timeout', '$log', 'eupService', function($rootScope, $scope, $uibModal, $filter, $window, $timeout, $log, eupService) {
        var vm = this;
        vm.stateType = $rootScope.currentState.split(".")[1];
        vm.items = {}, vm.showLoader = true, vm.showAnswer = false, vm.quizSubmit = false, vm.disableNavigation = false;
        vm.ansShowHideText = "LABELS.SHOW_ANSWER";

        //Fetch item next/prev item details from the server
        var getItemDetails = function(itemDetail, placement) {
            eupService.getTestInstanceItemById(itemDetail, vm.testInstanceId).then(function(response) {
                vm.items[placement] = response.data;
            });
        };

        //Render the question in quiz engine based on its question type
        var renderItem = function() {
            if (angular.isDefined(vm.items.current.parentLabelText)) {
                if (vm.items.current.parentLabelText == 'MEDICAL_CASE')
                    vm.itemtypeTemplate = "medical-question-preview";
                else if (vm.items.current.parentLabelText == 'CLINICAL_SYMPTOMS')
                    vm.itemtypeTemplate = "clinical-question-preview";
            } else if (vm.items.current.labelText == 'GRAPHIC_OPTION')
                vm.itemtypeTemplate = "graphic-option-preview"
            else
                vm.itemtypeTemplate = "multiple-choice-preview";

            //Turn Off loader
            vm.showLoader = false;

            //Assign current sequence data
            vm.sequence = { currentSequence: vm.items.current.sequence, totalItems: vm.testDetails.testProgress.totalTestQuestions };


            //Assign basic asset details which applicable for asset related wuestion types
            if (angular.isDefined(vm.items.current.parentLabelText) && angular.isObject(vm.items.current.parentDetails.assets) && (vm.items.current.parentLabelText == 'MEDICAL_CASE' || vm.items.current.parentLabelText == 'CLINICAL_SYMPTOMS')) {
                vm.assetTypeId = vm.items.current.parentDetails.assets.assetTypeId;
                vm.itemAssetPath = '/' + vm.items.current.parentDetails.assets.assetPath + "/" + vm.items.current.parentDetails.assets.assetName;
            } else if ((vm.items.current.labelText == 'IMAGE_INTEGRATION' || vm.items.current.labelText == 'VIDEO_QUESTIONS') && angular.isDefined(vm.items.current.assets)) {
                vm.assetTypeId = vm.items.current.assets["0"].assetTypeId;
                vm.itemAssetPath = '/' + vm.items.current.assets["0"].assetPath + "/" + vm.items.current.assets["0"].assetName;
            }

        };

        if (vm.stateType == "engine") { //action block when the state type is engine
            if (angular.isDefined($rootScope.token) && eupService.decodeToken($rootScope.token)) {
                vm.testInstanceId = $rootScope.$stateParams.testInstanceId;
                vm.testId = $rootScope.$stateParams.testId;
                vm.testVersion = $rootScope.$stateParams.testVersion;
                //Get the basic test details
                eupService.getTestById(vm.testId, vm.testVersion).then(function(response) {
                    if (response.status === 200) {
                        vm.testDetails = response.data;

                        //Get initial progress stats if the test mode is review
                        //if (!vm.testDetails.testMode)

                        //Get current item details from sever
                        eupService.getTestInstanceItemById(false, vm.testInstanceId).then(function(response) {
                            if (response.status === 200) {
                                vm.items.current = response.data;
                                vm.itemId = { itemId: vm.items.current.id, version: vm.items.current.version };

                                vm.getProgress().then(function() {
                                    //Renders UI based on item type
                                    renderItem();

                                    //Starts timer
                                    vm.startTimer();
                                });

                                //Pre-load previous question details
                                if (vm.items.current.previousItemId && vm.items.current.previousItemId !== {}) {
                                    getItemDetails(vm.items.current.previousItemId, "prev");
                                }
                                //Pre-load next question details
                                if (vm.items.current.nextItemId && vm.items.current.nextItemId !== {}) {
                                    getItemDetails(vm.items.current.nextItemId, "next");
                                }
                            } else if (response.status === 400) { //Redirect to summary page if the test instance is already completed
                                var stateParams = { testId: vm.testId, testInstanceId: vm.testInstanceId, token: $rootScope.token }
                                $rootScope.$state.go("eup.summary", stateParams); //redirect to sumamry
                            } else if (response.status === 404) { //Error in case of data not found on server
                                if (response.data.code == 4021) {
                                    $rootScope.$state.go("eup.invalidtest")
                                }
                            }

                        });
                    } else if (response.status === 404) { //Error in case of data not found on server
                        if (response.data.code == 4019) {
                            $rootScope.$state.go("eup.invalidtest")
                        }
                    } else if (response.status === 401) {
                        $log.error("Authentication error")
                        $rootScope.$state.go("eup.401")
                    }
                });
            } else {
                $log.error("Authentication error")
                $rootScope.$state.go("eup.401")
            }
        } else if (angular.isUndefined(vm.id)) {
            $log.error("Invalid State/Action Type")
            $rootScope.$state.go("eup.404")
        }

        //Get test progress details
        vm.getProgress = function() {
            return eupService.getTestProgress(vm.testId, vm.testInstanceId).then(function(response) {
                vm.testDetails.testProgress = response.data.testProgress;
                //$rootScope.$apply();
            });
        }

        //Handles all the changes when user navigate from one item to another
        //Also handles preloading of questions
        vm.navigate = function(direction, submit) {
            $log.debug("Go to " + direction)
            if (submit) {
                //Store Current Answer to server
                if (!vm.quizSubmit) //avoid multiple submit 
                    vm.captureAnswer(angular.copy(vm.items.current.userAttemptDetails), submit);
                vm.quizSubmit = true;
            } else if (direction == "next" && angular.isDefined(vm.items.next) && !vm.showLoader) { //Check whether preloaded next item data availability
                vm.showLoader = true;
                //Store Current Answer to server
                vm.captureAnswer(angular.copy(vm.items.current.userAttemptDetails), submit);
                vm.items.prev = angular.copy(vm.items.current);
                vm.items.current = angular.copy(vm.items.next);
                vm.itemId = { itemId: vm.items.current.id, version: vm.items.current.version };
                delete vm.items.next; //remove next data

                //Renders UI based on item type
                renderItem();

                //Starts timer
                vm.startTimer();

                //Pre-load previous item data from server
                if (vm.items.current.nextItemId && vm.items.current.nextItemId !== {})
                    getItemDetails(vm.items.current.nextItemId, "next");

                //If it is review mode then hide answer while navigating
                if (!vm.testDetails.testMode) {
                    vm.hideAnswer();
                }
            } else if (vm.testDetails.navigationType == 2 && direction == "prev" && angular.isDefined(vm.items.prev) && !vm.showLoader) { //Check whether preloaded prev item data availability
                vm.showLoader = true;
                //Store Current Answer to server
                vm.captureAnswer(angular.copy(vm.items.current.userAttemptDetails), submit);
                vm.items.next = angular.copy(vm.items.current); //move current to prev
                vm.items.current = angular.copy(vm.items.prev); //move prev to current
                vm.itemId = { itemId: vm.items.current.id, version: vm.items.current.version };
                delete vm.items.prev; //remove prev data

                //Renders UI based on item type
                renderItem();

                //Starts timer
                vm.startTimer();

                //Pre-load previous item data from server
                if (vm.items.current.previousItemId && vm.items.current.previousItemId !== {})
                    getItemDetails(vm.items.current.previousItemId, "prev");

                //If it is review mode then hide answer while navigating
                if (!vm.testDetails.testMode) {
                    vm.hideAnswer();
                }
            } else if (!angular.isDefined(vm.items.prev) || !angular.isDefined(vm.items.next)) {
                $log.debug("no data")
            }
            $log.debug("Item Id " + vm.itemId)
        }

        //Capture answer details
        vm.captureAnswer = function(attemptDetails, submit) {
            attemptDetails.submit = (submit == true) ? true : false;
            //$log.debug(attemptDetails)
            eupService.saveAnswer(vm.itemId, vm.testInstanceId, attemptDetails).then(function(response) {
                if (submit) {
                    var stateParams = { testId: vm.testId, testInstanceId: vm.testInstanceId, token: $rootScope.token }
                    $rootScope.$state.go("eup.summary", stateParams);
                } else {
                    vm.getProgress();
                }

            });
        }

        //Start the timer for current item
        vm.startTimer = function() {
            if (angular.isDefined(vm.itemTimer)) //Stop previous running timers 
                $timeout.cancel(vm.itemTimer);

            vm.itemTimer = $timeout(vm.countDown, 1000);
        }

        //Increment the timespent and calls recursively
        vm.countDown = function() {
            if (vm.items.current.userAttemptDetails.timeSpent === null) //assign
                vm.items.current.userAttemptDetails.timeSpent = 0;

            vm.items.current.userAttemptDetails.timeSpent++;
            vm.itemTimer = $timeout(vm.countDown, 1000);
        }

        //Reset the current item answer
        vm.resetAnswer = function() {
            vm.items.current.userAttemptDetails.userAnswer = [];
            //If it is review mode then hide answer while navigating
            if (!vm.testDetails.testMode)
                vm.hideAnswer();
            // if (!vm.testDetails.testMode && vm.items.current.userAttemptDetails.userAnswer.length > 0) {
            //     vm.testDetails.testProgress.totalUnAttempted++;
            // }
        }

        //Convert seconds to respective type
        vm.convertTime = function(seconds, type) {
            return eupService.convertTime(seconds, type);
        }

        //Hide answer and text of show answer botton
        vm.hideAnswer = function() {
            vm.showAnswer = false;
            vm.ansShowHideText = "LABELS.SHOW_ANSWER";
        }

        //Show/hide correct answer and other remedy details
        vm.answerToggle = function() {
            if (vm.showAnswer) {
                vm.hideAnswer();
            } else if (!vm.showAnswer && !vm.testDetails.testMode) { //If it is review mode then find and show answer
                var answerDetails = vm.items.current.choiceInteraction.simpleChoices;
                vm.items.current.correctAnswer = "";
                vm.items.current.selectedAnswer = "";
                vm.items.current.rationale = "";
                vm.items.current.correct = false;
                var correctIds = [],
                    selectedIds = [];
                //In case of multiple correct answers create a comma seperated answer string
                angular.forEach(answerDetails, function(choice, key) {
                    if (choice.correct == true) {
                        correctIds.push(choice.choiceId);
                        if (vm.items.current.correctAnswer != "")
                            vm.items.current.correctAnswer = vm.items.current.correctAnswer + ',' + choice.label;
                        else if (vm.items.current.labelText == 'GRAPHIC_OPTION')
                            vm.items.current.correctAnswer = choice.value;
                        else
                            vm.items.current.correctAnswer = choice.label;
                    }
                    if (vm.items.current.userAttemptDetails.userAnswer.indexOf(choice.choiceId) != -1) {
                        selectedIds.push(choice.choiceId);
                        if (vm.items.current.selectedAnswer != "")
                            vm.items.current.selectedAnswer = vm.items.current.selectedAnswer + ',' + choice.label;
                        else if (vm.items.current.labelText == 'GRAPHIC_OPTION')
                            vm.items.current.selectedAnswer = choice.value;
                        else
                            vm.items.current.selectedAnswer = choice.label;
                    }
                });

                //Check whether the choosen answer is correct
                if (angular.equals(correctIds, selectedIds)) {
                    vm.items.current.correct = true;
                }

                //Based on correct/incorrect answer assign the feedback details
                if (vm.items.current.correct) { //If answer is correct then assign ccorect rationale
                    vm.items.current.rationale = vm.items.current.modelFeedback[0].feedbackText;
                } else { //If answer is wrong then assign inccorect rationale
                    vm.items.current.rationale = vm.items.current.modelFeedback[1].feedbackText;
                }
                vm.showAnswer = true;
                vm.ansShowHideText = "LABELS.HIDE_ANSWER";
            }
        }

        //On click of answer choices based on item type chosen answers will be captured in front-end based on item type
        vm.selectAnswer = function(choiceId) {
                $log.debug(choiceId)
                if (angular.isDefined(vm.items.current) && angular.isDefined(vm.items.current.userAttemptDetails)) {
                    if (vm.items.current.labelText == "CHOICE_MULTIPLE") {
                        var index = vm.items.current.userAttemptDetails.userAnswer.indexOf(choiceId);
                        if (index === -1)
                            vm.items.current.userAttemptDetails.userAnswer.push(choiceId);
                        else
                            vm.items.current.userAttemptDetails.userAnswer.splice(index, 1);
                        $log.debug(vm.items.current.userAttemptDetails.userAnswer)
                    } else {
                        vm.items.current.userAttemptDetails.userAnswer = [choiceId];
                    }
                }
            }
            //Gets the choice label class name dynamically based on its status
        vm.getChoiceClassName = function(choiceDetails) {
            if (choiceDetails.correct)
                return "wkqp-icon-checkmark green-checkmark";
            else if (!choiceDetails.correct && vm.items.current.userAttemptDetails.userAnswer.indexOf(choiceDetails.choiceId) != -1)
                return "wkqp-icon-close wrong-checkmark";
            else
                return "wkqp-icon-checkmark disabled-checkmark";
        }

        var SubmitModalCtrl = function($scope, $uibModalInstance) {
            var $ctrl = this;
            $ctrl.submit = function() {
                $uibModalInstance.close();
                vm.navigate('next', true);
            };
            $ctrl.cancel = function() {
                $uibModalInstance.dismiss('cancel');
            };
        }
        SubmitModalCtrl.$inject = ['$scope', '$uibModalInstance'];

        vm.openSubmitModal = function() {
            var modalInstance = $uibModal.open({
                animation: true,
                templateUrl: 'submit-test-modal',
                controller: SubmitModalCtrl,
                controllerAs: '$ctrl'
            });
        }

    }]);
})();

/**
 * @namespace EUP
 * @desc Quiz summary page controller 
 * @memberOf Controller
 * @author Jagadeeshraj V S
 */

(function() {
    'use strict';

    angular.module('eupapp').controller('TestSummaryController', ['$rootScope', '$scope', 'config', '$filter', '$window', '$timeout', '$log', 'eupService', function($rootScope, $scope, config, $filter, $window, $timeout, $log, eupService) {
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
    }]);
})();
