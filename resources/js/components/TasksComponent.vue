<style scoped>
    .action-link {
        cursor: pointer;
    }
</style>

<template>
    <div class="card shadow mb-4">
        <div class="card card-default">
            <div class="card-header">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span>
                        Tasks
                    </span>
                    <a class="action-link" tabindex="-1" @click="showCreateTaskForm">
                        Create New Task
                    </a>
                </div>
            </div>

            <div class="card-body">

                <!-- Current Tasks -->
                <p class="mb-0" v-if="tasks.length === 0">
                    No tasks data to display at the moment.
                </p>
                <table class="table table-bordered mb-0" id="dataTable" cellspacing="0" v-if="tasks.length > 0">
                    <thead>
                    <tr>
                        <th>Task</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tfoot>
                    <tr>
                        <th>Task</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    </tfoot>

                    <tbody>
                    <tr v-for="todo in tasks">
                        <!-- Task -->
                        <td style="vertical-align: middle;">
                            {{ todo.task }}
                        </td>

                        <td v-if="todo.is_done == 1" style="vertical-align: middle;">
                            Done
                        </td>
                        <td v-else style="vertical-align: middle;">
                            Pending
                        </td>

                        <!-- Edit Button -->
                        <td style="vertical-align: middle;">
                            <button v-if="todo.is_done == 0" class="action-link text-success" tabindex="-1" @click="confirmComplete(todo)"
                                    title="Mark Complete">
                                <i class="fa fa-check"></i>
                            </button>
                            <button v-else class="action-link text-danger" tabindex="-1" @click="confirmComplete(todo)"
                                    title="Mark Incomplete">
                                <i class="fa fa-minus"></i>
                            </button>
                            <button class="action-link text-info" tabindex="-1" @click="edit(todo)" title="Edit Task">
                                <i class="fa fa-edit"></i>
                            </button>
                            <button class="action-link text-danger" @click="delete_task(todo)" title="Delete Task">
                                <i class="fa fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    </tbody>
                </table>


            </div>
        </div>

        <!-- Create Task Modal -->
        <div class="modal fade" id="modal-create-task" tabindex="-1" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">
                            Create Task
                        </h4>

                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    </div>

                    <div class="modal-body">
                        <!-- Form Errors -->
                        <div class="alert alert-danger" v-if="createForm.errors.length > 0">
                            <p class="mb-0"><strong>Whoops!</strong> Something went wrong!</p>
                            <br>
                            <ul>
                                <li v-for="error in createForm.errors">
                                    {{ error }}
                                </li>
                            </ul>
                        </div>

                        <!-- Create Task Form -->
                        <form role="form">
                            <!-- First Name -->
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label">Task</label>

                                <div class="col-md-9">
                                    <input id="create-task"
                                           type="text"
                                           class="form-control"
                                           name="first_name"
                                           v-validate="'required'"
                                           @keyup.enter="store"
                                           v-model="createForm.task">
                                    <i v-show="errors.has('task')" class="fa fa-warning"></i>
                                    <span v-show="errors.has('task')"
                                          class="help is-danger">{{ errors.first('task') }}</span>

                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Modal Actions -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

                        <button type="button" class="btn btn-primary" @click="store">
                            Create
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Task Modal -->
        <div class="modal fade" id="modal-edit-task" tabindex="-1" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">
                            Edit Task
                        </h4>

                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    </div>

                    <div class="modal-body">
                        <!-- Form Errors -->
                        <div class="alert alert-danger" v-if="editForm.errors.length > 0">
                            <p class="mb-0"><strong>Whoops!</strong> Something went wrong!</p>
                            <br>
                            <ul>
                                <li v-for="error in editForm.errors">
                                    {{ error }}
                                </li>
                            </ul>
                        </div>

                        <!-- Edit Task Form -->
                        <form role="form">
                            <!-- Task -->
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label">Task</label>

                                <div class="col-md-9">
                                    <input id="edit-task"
                                           type="text"
                                           class="form-control"
                                           name="task"
                                           v-validate="'required'"
                                           :onkeyup="update"
                                           v-model="editForm.task">
                                    <i v-show="errors.has('task')" class="fa fa-warning"></i>
                                    <span v-show="errors.has('task')"
                                          class="help is-danger">{{ errors.first('task') }}</span>

                                </div>
                            </div>

                        </form>
                    </div>

                    <!-- Modal Actions -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

                        <button type="button" class="btn btn-primary" @click="update">
                            Save Changes
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Task Modal -->
        <div class="modal fade" id="modal-delete-task" tabindex="-1" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">
                            Delete Task
                        </h4>

                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    </div>

                    <div class="modal-body">
                        <!-- Form Errors -->
                        <div class="alert alert-danger" v-if="editForm.errors.length > 0">
                            <p class="mb-0"><strong>Whoops!</strong> Something went wrong!</p>
                            <br>
                            <ul>
                                <li v-for="error in deleteForm.errors">
                                    {{ error }}
                                </li>
                            </ul>
                        </div>

                        <!-- Delete Task Form -->
                        <form role="form">

                            <!-- Task -->
                            <div class="form-group">
                                <label class="col-form-label">Please type in <b>{{deleteForm.display_task}}</b> in the
                                    textbox to confirm deletion.</label>

                                <div class="col-md-9">
                                    <input id="delete-task"
                                           type="text"
                                           class="form-control"
                                           name="task"
                                           v-validate="'required'"
                                           v-model="deleteForm.task">
                                    <i v-show="errors.has('task')" class="fa fa-warning"></i>
                                    <span v-show="errors.has('task')"
                                          class="help is-danger">{{ errors.first('task') }}</span>

                                </div>
                            </div>

                        </form>
                    </div>

                    <!-- Modal Actions -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

                        <button type="button" class="btn btn-primary" :disabled="isDisabled"
                                @click="destroy(deleteForm)">
                            Confirm Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Complete Task Modal -->
        <div class="modal fade" id="modal-complete-task" tabindex="-1" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">
                            Complete Task
                        </h4>

                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    </div>

                    <div class="modal-body">
                        <!-- Form Errors -->
                        <div class="alert alert-danger" v-if="editForm.errors.length > 0">
                            <p class="mb-0"><strong>Whoops!</strong> Something went wrong!</p>
                            <br>
                            <ul>
                                <li v-for="error in deleteForm.errors">
                                    {{ error }}
                                </li>
                            </ul>
                        </div>

                        <!-- Delete Task Form -->
                        <form role="form">

                            <!-- Task -->
                            <div class="form-group">
                                <label v-if="is_done==0" class="col-form-label">Do you want to mark this task as completed?</label>
                                <label v-else class="col-form-label">Do you want to mark this task as incompleted?</label>
                            </div>

                        </form>
                    </div>

                    <!-- Modal Actions -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

                        <button  v-if="is_done==0" type="button" class="btn btn-primary"
                                @click="completeTask(complete_task)">
                            Complete Task
                        </button>
                        <button  v-else type="button" class="btn btn-primary"
                                @click="completeTask(complete_task)">
                            Mark as Incomplete
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        /*
         * The component's data.
         */
        data() {
            return {
                tasks: [],
                complete_task:'',
                is_done:'',
                selected_tasks: [],
                createForm: {
                    errors: [],
                    task: ''
                },
                editForm: {
                    errors: [],
                    id: '',
                    task: '',
                    _method: 'PUT'
                },
                deleteForm: {
                    errors: [],
                    id: '',
                    task: '',
                    display_task: '',
                },

            };
        },
        computed: {
            isDisabled() {
                return this.deleteForm.display_task != this.deleteForm.task;
            }
        },
        /**
         * Prepare the component (Vue 1.x).
         */
        ready() {
            this.prepareComponent();
        },
        /**
         * Prepare the component (Vue 2.x).
         */
        mounted() {
            this.prepareComponent();
        },
        methods: {
            /**
             * Prepare the component.
             */
            prepareComponent() {
                this.getTasks();
                $('#modal-create-task').on('shown.bs.modal', () => {
                    $('#create-task').focus();
                });
                $('#modal-edit-task').on('shown.bs.modal', () => {
                    $('#edit-task').focus();
                });
            },
            /**
             * Get all of the tasks.
             */
            getTasks() {
                axios.get('/api/tasks')
                    .then(response => {
                        this.tasks = response.data;
                    });
            },
            /**
             * Show the form for creating new tasks.
             */
            showCreateTaskForm() {
                $('#modal-create-task').modal('show');
            },

            /**
             * Create a new task.
             */
            store() {
                axios.post('/api/tasks',this.createForm)
                    .then(response => {
                        this.getTasks();
                        this.editForm.errors = [];
                        this.editForm.errors.clear()
                    });
                $('#modal-create-task').modal('hide');

            },
            /**
             * Confirm complete task.
             */
            confirmComplete(task){
                this.complete_task = task.id;
                this.is_done = task.is_done;
                $('#modal-complete-task').modal('show');
            },

            /**
             * Complete task.
             */
            completeTask(task_id){
                axios.put('/api/tasks/' + task_id + '/complete')
                    .then(response => {
                        this.getTasks();
                    });
                $('#modal-complete-task').modal('hide');
            },

            /**
             * Edit task.
             */
            edit(task) {
                this.editForm.id = task.id;
                this.editForm.task = task.task;
                $('#modal-edit-task').modal('show');
            },
            /**
             * Update the task being edited.
             */
            update() {
                axios.put('/api/tasks/' + this.editForm.id + '/update',this.editForm)
                    .then(response => {
                        this.getTasks();
                        this.editForm.errors = [];
                        this.editForm.errors.clear()
                    });
                $('#modal-edit-task').modal('hide');
            },

            /**
             * Delete task.
             */
            delete_task(task) {
                this.deleteForm.id = task.id;
                this.deleteForm.display_task = task.task;
                $('#modal-delete-task').modal('show');
            },

            /**
             * Destroy task
             */
            destroy(task) {
                axios.delete('/api/tasks/' + task.id)
                    .then(response => {
                        this.getTasks();
                        this.deleteForm.errors = [];
                        this.deleteForm.errors.clear()
                    });
                $('#modal-delete-task').modal('hide');
            },

        }
    }
</script>