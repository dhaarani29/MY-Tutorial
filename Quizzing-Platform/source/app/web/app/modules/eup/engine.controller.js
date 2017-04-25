/**
 * @namespace EUP
 * @desc Quiz engine controller 
 * @memberOf Controller
 * @author Jagadeeshraj V S
 */
(function() {
    'use strict';

    angular.module('eupapp').controller('TestEngineController', function($rootScope, $scope, $uibModal, $filter, $window, $timeout, $log, eupService) {
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

        vm.openSubmitModal = function() {
            var modalInstance = $uibModal.open({
                animation: true,
                templateUrl: 'submit-test-modal',
                controller: SubmitModalCtrl,
                controllerAs: '$ctrl'
            });
        }

    });
})();
