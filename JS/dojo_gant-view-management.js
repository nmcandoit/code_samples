/*************************************************************************

                Sample of a widget for Dojo to manage events
                             with a GANTT view

     Features:
     - View with events on a timeline with durations
     - Change start, end, duration, or math method for each event
     - Zoom into the view to change the scale

     IMPORTANT: this is one of a group of file to create the widget

************************************************************************/

define(["dojo/_base/declare",
        "dojo/text!./templates/GanttWidget.html", // UI Component Template
        "icm/base/_BaseWidget",
        "dojox/gantt/GanttChart",
        "dojox/gantt/GanttProjectItem",
        "dojox/gantt/GanttTaskItem",
        "dojo/query",
        "dojo/on",
        "./DialogTaskWidget",
        "dojo/Deferred",
        "dojo/dom",
        "dojo/dom-construct",
        "dojo/dom-class",
        "dojo/dom-style",
        "dojo/domReady!"],

    function (
        declare,
        template,
        _BaseWidget,
        GanttChart,
        GanttProjectItem,
        GanttTaskItem,
        query,
        on,
        DialogTaskWidget,
        Deferred,
        dom,
        domConstruct,
        domClass,
        domStyle
    ) {

        return declare("CasePlanViewWidgetDojoModule.GanttWidget", [_BaseWidget], {
            templateString: template,
            widgetsInTemplate: true,

            postCreate: function () {
                this.inherited(arguments);
                this.refreshView();
            },//END Post Create

            refreshView: function() {
                console.log("main refreshView launched");

                if (this.ganttChart) domConstruct.empty(this.gantt);

                this.ganttChart = new GanttChart({
                    readOnly: true,        // optional: determine if gantt chart is editable"
                    height: 500,            // optional: chart height in pixel, default is 400px
                    width: 900,            // optional: chart width in pixel, default is 600px
                    withResource: false,      // optional: display the resource chart or not
                    withTaskId: true
                }, this.gantt);              //"gantt" is the node container id of gantt chart widget

                if (!this.edit) {
                    this.editButton.setDisabled(false);
                    this.mainSaveButton.setDisabled(true);
                    this.mainCancelButton.setDisabled(true);
                } else {
                    this.editButton.setDisabled(true);
                    this.mainSaveButton.setDisabled(false);
                    this.mainCancelButton.setDisabled(false);
                }

                this.procedures = this.data.procedures.query();
                for (var i = 0; i < this.procedures.length; i++) {

                    const procedure = this.procedures[i];
                    this.procedure = this.procedures[i];

                    //TODO : change startDate
                    const project = new GanttProjectItem({
                        id: i ,
                        name: procedure.caseTypeId,
                        //TODO: change to a correct date TO DISCUSS
                        startDate: new Date(2018,7,1)
                    });

                    const tasks = procedure.tasks.query();

                    const proceduresOnly = false;

                    if (proceduresOnly !== true ) {

                        for (var cptTask = 0; cptTask < tasks.length; cptTask++) {
                            const task = tasks[cptTask];

                            let startDate = new Date(task.computedStartDate);
                            let endDate = new Date(task.computedEndDate);
                            let timeDiff = Math.abs(endDate.getTime() - startDate.getTime());
                            // milliseconds -> seconds -> hour -> days + 1 to include start and end days -> 8 hours per day
                            this.ganttTaskDuration  = ((Math.ceil(timeDiff / (1000 * 3600 * 24)))+1)*8;

                            //TODO: change startTime and duration (by default : 8hours per day)
                            const taskToAdd = new GanttTaskItem({
                                id: task.taskTypeId,
                                name: task.symbolicName,
                                //TODO: put correct duration and startTime
                                startTime: new Date(task.computedStartDate),
                                duration: this.ganttTaskDuration,
                                percentage: 100
                            });

                            project.addTask(taskToAdd);
                        }

                    } else {
                        //TODO: for procedure only
                    }

                    this.ganttChart.addProject(project);
                }
                if (this.dialogTaskWidget) this.dialogTaskWidget.destroy();
                this.dialogTaskWidget = new DialogTaskWidget({store: this.store, data: this.data});
                //TODO : check
                this.dialogTaskWidget.on('saveTask',this.saveTask.bind(this));

                this.initGantt();
            },

            initGantt: function () {

                this.ganttChart.init();
                setTimeout(function () {
                    this.emit('ganttLoaded');
                }.bind(this), 0);

            },

            EditButtonClick: function () {
                const confirmContinue = confirm("Do you want to edit this procedure? Other users will not be able to work on it until you check it in.");
                if (confirmContinue === true) {
                    this.editButton.setDisabled(true);
                    this.mainSaveButton.setDisabled(false);
                    this.mainCancelButton.setDisabled(false);
                    //Test
                    this.emit('checkout',this.data);
                    //this.refreshView(edit=1);
                }
            },

            mainSaveButtonClick: function () {
                const confirmSave = confirm("You are about to save your changes. Continue?");
                if (confirmSave === true) {
                    //this.refreshView();
                    this.emit('saveAll' , this.data);
                }
            },

            mainCancelButtonClick: function () {
                const confirmCancel = confirm("Are you sure you want to cancel? All your changes will be discarded.");
                if (confirmCancel === true) {
                    //this.refreshView();
                    this.emit('cancelCheckout' , this.data);
                }
            },

            setTaskOnClick: function () {
                for (var cptproceduresaccess = 0; cptproceduresaccess < this.procedures.length; cptproceduresaccess++) {
                    //TODO: change for the correct property
                    if (this.procedures[cptproceduresaccess].id == 0) {
                        var ProcedureAccess = this.procedures[cptproceduresaccess];
                        var ProcedureNodes= query(".ganttProjectItem");
                        var ProcedureNode = ProcedureNodes.filter(procedure => procedure.id == parseInt(ProcedureAccess.id))[0];
                        var ProcedureSubNode = query(".ganttImageProgressFilled", ProcedureNode);
                        domStyle.set(ProcedureSubNode[0], "background", "rgba(65,120,190,1)");

                        var ProcedureAccessTasks = ProcedureAccess.tasks.query();
                        this.procTest = ProcedureAccessTasks;
                        var taskIdArray = [];
                        for (var cptstyle = 0; cptstyle < ProcedureAccessTasks.length; cptstyle++) {
                            taskIdArray.push(ProcedureAccessTasks[cptstyle].taskTypeId);

                            var TasksNodes = query(".ganttTaskItemControl");
                            var TaskNode = TasksNodes.filter(task => task.id == ProcedureAccessTasks[cptstyle].taskTypeId)[0];
                            var TaskSubNode = query(".ganttImageTaskProgressFilled", TaskNode);
                            domStyle.set(TaskSubNode[0], "background", "rgba(28,190,74,1)");

                            var TasksNameNodes = query(".ganttTaskTaskNameItem");
                            var TaskNameNode = TasksNameNodes.filter(task => task.id == ProcedureAccessTasks[cptstyle].taskTypeId)[0];
                            if (this.edit) {
                                domClass.add(TaskNameNode, "cursorPointer");
                            } else {
                                domClass.add(TaskNameNode, "notEditable");
                            }
                        }

                    }
                }

                var stringIdArray ="#" + taskIdArray.join(", #");

                on(query(stringIdArray), "click", function (event) {
                    const taskId = event.target.id;
                    var task = {};

                    for (var cptProcedure = 0; cptProcedure < this.procedures.length; cptProcedure++) {
                        var procedure = this.procedures[cptProcedure];

                        for (var cpttask = 0; cpttask < procedure.tasks.query().length; cpttask++) {
                            var tasks = procedure.tasks.query();
                            if (taskId == tasks[cpttask].taskTypeId) {
                                task = tasks[cpttask];
                                break;
                            }
                        }
                    }
                    this.dialogTaskWidget.showDialog(task ,  this.procTest);

                }.bind(this));
            },

            saveTask: function(plan) {
                this.edit = true;
                this.data.procedures = this.store.constructProceduresStore(plan.procedures);
                this.refreshView();

            },

            _eoc_: null

        });
    });
