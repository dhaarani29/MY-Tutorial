'use strict';

var metadataApp = angular.module('app.metadata')
metadataApp.directive('metadataTree', function () {
    //directive object
    var directive = {};
    console.log("Inside Dir");
    //restrict directive only for Element
    directive.restrict = 'E';
    directive.require = ["^ngMessages", "^pascalprecht.translate"];

    //template url. 
    directive.templateUrl = "app/modules/metadata/partials/metatree-node.html";
    directive.transclude = false;
    directive.bindToController = true;
    directive.scope = {
        mode: "@mode",
        metadataValues: "=",
        metadataId: "=",
        isResourceAssociated: "=",
        metadataForm: "=form",
        currentNode: "=selectedNode",
        metadataAssoc: "=metadataAssoc",
        metadataPrev: "=metadataPrev"
    };

    directive.controllerAs = 'vm';
    directive.controller = function ($scope, $element, $attrs, $window, $filter, $rootScope, metadataService) {
        var vm = this;
        vm.actionType = $rootScope.$state.current.name.split(".")[1];
        vm.getModule = $rootScope.currentState.split('.')[0];
        // console.log(vm.metadataValues)
        vm.treeMode = $attrs.mode;
        vm.showContextMenu = false;
        vm.allReadySavedNodes = vm.metadataPrev;
        if (vm.getModule == 'items' && (vm.actionType == 'edit' || vm.actionType == 'create'))
        {
            vm.collectNodes = [] || vm.metadataAssoc;
            vm.currentNode = [];
            angular.forEach(vm.metadataAssoc, function (value, key) {

                if (angular.isUndefined(value.id))
                {
                    vm.collectNodes[value] = true;
                }
                else
                {
                    vm.collectNodes[value.id] = true;
                }


            });
            angular.forEach(vm.collectNodes, function (value, key) {
                if (value == true) //get all records to be added
                    vm.currentNode.push(key);
            });

        }
        else
        {
            vm.currentNode = vm.currentNode || vm.metadataValues[0] || {}

        }
        //console.log(!$filter('isEmptyObject')(vm.currentNode))

        vm.toggleVisibility = function (node, checkMultiValues) {
            //event.preventDefault();

            if ((vm.treeMode == 'view' || (vm.metadataForm.treetagName.$valid && vm.metadataForm.treetagDesc.$valid)) || (vm.getModule == 'metadata' && vm.actionType != 'create')) {

                var params = {};
                params.metadataValueId = node.id;
                if (checkMultiValues != 1)
                {
                    //Fetch metadata for the given id by calling metadata/{id} api
                    metadataService.getMetadataById(vm.metadataId, params).then(function (response) {

                        if (response.status === 200) {

                            if (!angular.isUndefined(response.data.metadataValues))
                            {
                                node.children = response.data.metadataValues;

                                vm.currentNode = node


                                var totalAliveChildren = $filter('filter')(node.children, {nodeStatus: "!deleted"}, true).length;
                                if (totalAliveChildren) {
                                    node.childrenVisibility = !node.childrenVisibility;
                                }
                            }
                            else
                            {

                                vm.currentNode = node


                                var totalAliveChildren = $filter('filter')(node.children, {nodeStatus: "!deleted"}, true).length;
                                if (totalAliveChildren) {
                                    node.childrenVisibility = !node.childrenVisibility;
                                }
                            }
                        }
                        if (vm.getModule == 'items' && (vm.actionType == 'edit' || vm.actionType == 'create'))
                        {
                            vm.currentNode = [];

                            angular.forEach(vm.collectNodes, function (value, key) {
                                if (value == true) //get all records to be added
                                    vm.currentNode.push(key);
                            });
                            console.log("FSDFSDF");
                            console.log(vm.currentNode);

                        }
                    });


                }
                else
                {
                    if (vm.getModule == 'items' && (vm.actionType == 'edit' || vm.actionType == 'create'))
                    {
                        vm.currentNode = [];
                        angular.forEach(vm.collectNodes, function (value, key) {
                            if (value == true) //get all records to be added
                                vm.currentNode.push(key);
                        });

                    }
                }

            }
            else

            {
                vm.currentNode = node
                //console.log(vm.currentNode)
                var totalAliveChildren = $filter('filter')(node.children, {nodeStatus: "!deleted"}, true).length;
                if (totalAliveChildren) {
                    node.childrenVisibility = !node.childrenVisibility;
                }
            }

        };



        // vm.isEmptyObject = function(){

        // }
        vm.contextMenu = function (data, $event) {
            //if (vm.metadataForm.treetagName.$valid && vm.metadataForm.treetagDesc.$valid) {
            vm.showContextMenu = true;
            vm.contextMenuData = data;
            //$('#contextmenu-node').css({'border':'1px solid green','top':($event.pageY - 5)+"px",'left':($event.pageX - 5)+"px"});
            vm.cotextMenuStyle = {'top': $event.pageY - 5 + "px", 'left': $event.pageX - 5 + "px"};
            console.log(vm.contextMenuData)
            console.log(vm.cotextMenuStyle)
            console.log('after digest')
            angular.element($window).triggerHandler('resize');

            try {
                $scope.$digest()
            } catch (e) {

            }
        };
        vm.closeContextMenu = function () {
            vm.showContextMenu = false;
            console.log("close contexy menu")

        }
        vm.editTreeNode = function (data) {
            vm.currentNode = data
            vm.showContextMenu = false;
        }
        vm.addTreeNode = function (data) {
            if (angular.isUndefined(vm.metadataForm.treetagName) || (vm.metadataForm.treetagName.$valid && vm.metadataForm.treetagDesc.$valid)) {
                var newNodeIndex = 1;
                if (angular.isUndefined(data)) {
                    // if (!angular.isUndefined(vm.metadataValues) && vm.metadataValues.length > 0)
                    //Adding new node in the root level
                    var nodeCount = $filter('filter')(vm.metadataValues, {nodeStatus: "!deleted"}, true).length;
                    newNodeIndex = vm.metadataValues.push({
                        value: "New Node " + (nodeCount + 1),
                        description: "New Node " + (nodeCount + 1),
                        children: [],
                        childrenVisibility: 1,
                        id: vm.metadataValues.length + 1,
                        nodeStatus: "created"
                    });
                    // else
                    //     vm.metadataValues = [{
                    //         value: "New Node 1",
                    //         description: "New Node 1",
                    //         children: [],
                    //         childrenVisibility: 1,
                    //         id: 1
                    //     }];
                    vm.currentNode = vm.metadataValues[newNodeIndex - 1]
                } else {
                    //Adding new node under some node
                    var nodeCount = $filter('filter')(data.children, {nodeStatus: "!deleted"}, true).length;
                    var newName = data.value + '-' + (nodeCount + 1);

                    newNodeIndex = data.children.push({
                        value: newName,
                        description: newName,
                        children: [],
                        childrenVisibility: 1,
                        id: data.id + '-' + (nodeCount + 1),
                        nodeStatus: "created"
                    });
                    data.childrenVisibility = 1;
                    vm.currentNode = data.children[newNodeIndex - 1] //Making newly created node as current node

                }
                vm.showContextMenu = false;
                vm.firstClick = 1;
                console.log("^^^^" + vm.currentNode.id)
            } else {
                //console.log(vm.metadataForm.treetagName)
            }

        };
        vm.deleteTreeNode = function (data) {
            console.log(data.parent)
            // if (data.nodeStatus == 'created') { 
            var prevNode = deleteNode(vm.metadataValues, data['id']);
            if (prevNode === true) { //For deleting records in root level
                var remainingChild = $filter('filter')(vm.metadataValues, {nodeStatus: "!deleted"}, true);
                if (remainingChild.length > 0) {
                    vm.currentNode = remainingChild[remainingChild.length - 1]; //Return parent node obj for changing current node
                } else {
                    vm.currentNode = {}; //empty current node when all nodes are deleted
                }
            } else
                vm.currentNode = prevNode;
            vm.showContextMenu = false;

        };

        //Recursive function to find and delete the node based on id in the entire tree structure. 
        //The reason of traversing tree is to find next sibling/parent.
        //Make sure you understand the logic by reading comments before modifying this unless do not modify.
        var deleteNode = function (array, id) {
            //Checking for the node by checkig its id inside a loop from tree head
            for (var i = 0; i < array.length; ++i) {
                var obj = array[i];
                if (obj.id === id && obj.nodeStatus == "created") { //Deleting newly created node(Not yet saved in server). Delete that node and its children
                    // splice out 1 element starting at position i
                    array.splice(i, 1);
                    return true;
                } else if (obj.id === id && (obj.nodeStatus == "selected" || obj.nodeStatus == "updated")) { //When deleting existing node
                    obj.nodeStatus = "deleted"; //Just change status. This helps to delete the node data in db.
                    return true;
                }

                var remainingChild = $filter('filter')(obj.children, {nodeStatus: "!deleted"}, true);
                //Checking for children and call the method recursively
                if (remainingChild.length > 0 && deleteNode(obj.children, id)) {
                    obj.childrenVisibility = true;
                    var remainingChild = $filter('filter')(obj.children, {nodeStatus: "!deleted"}, true); //obj.children.length;
                    if (remainingChild.length > 0) { //check for alive child in current level
                        return remainingChild[remainingChild.length - 1]; //Return last alive child of current level
                    } else {
                        return obj; //Return parent node obj for changing current node
                    }
                }
            }
        };

        //Changes node status when we modify node name/description of existing nodes 
        vm.updateStatus = function () {
            if (vm.currentNode.nodeStatus == "selected")
                vm.currentNode.nodeStatus = "updated";
        }
    }

    return directive;
})
        .directive('ngRightClick', function ($parse) {
            return {
                require: "^metadataTree",
                scope: false,
                link: function (scope, element, attrs, ctrl) {
                    if (ctrl.mode == "edit") {
                        var fn = $parse(attrs.ngRightClick);
                        element.bind('contextmenu', function (event) {
                            console.log(angular.toJson(event, true))
                            scope.$apply(function () {
                                console.log("event apply")
                                event.preventDefault();
                                event.stopPropagation();
                                fn(scope, {
                                    $event: event
                                });
                            });
                        });
                    }
                }
            }
        });
