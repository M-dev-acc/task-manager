<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Tasks</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.css" />
  
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.js"></script>
</head>
<body>
    <header></header>

    <main class="container">
        <section class="card">
            <header class="card-header d-flex">
                <h2 class="card-title">Tasks</h2> 
                <button class="btn btn-primary ms-auto" type="button" data-bs-toggle="modal" data-bs-target="#addTaskModal">Add task</button>
            </header>
            <div class="card-body">
                <table class="table" id="tasksDataTable"></table>
            </div>
        </section>
    </main>

    <footer></footer>

    <div class="modal fade" id="addTaskModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addTaskModalTitle" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="addTaskModalTitle">Add</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <form method="post" id="addTaskForm">
                        <div class="mb-3">
                            <label for="nameInput" class="form-label">Name</label>
                            <input type="text" name="name" id="nameInput" class="form-control">
                        </div>
                        
                        <div class="my-3">
                            <input type="submit" value="Add new task" class="btn btn-primary " >
                        </div>
                    </form>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editTaskModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editTaskModalTitle" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editTaskModalTitle">Edit task</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <form method="post" id="editTaskForm">
                        <input type="hidden" name="task_id" id="taskIdInput">
                        <div class="mb-3">
                            <label for="nameInput" class="form-label">Name</label>
                            <input type="text" name="name" id="editNameInput" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="nameInput" class="form-label">Status</label>
                            <input type="checkbox" name="status" value="1" id="editStatusInput" class="form-check-input">
                        </div>
                        
                        <div class="my-3">
                            <input type="submit" value="Add new task" class="btn btn-primary " >
                        </div>
                    </form>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/index.js') }}"></script>
    <script>
        $(document).ready(function () {
            isUserLoggedIn();
            const baseUrl = window.location.origin;
            const authToken = localStorage.getItem('auth-token');


            $('#tasksDataTable').DataTable({
                ajax: {
                    'url': `${baseUrl}/api/v1/tasks`,
                    'type': 'GET',
                    'headers': {
                        'Authorization': 'Bearer ' + authToken,
                    },
                    'dataSrc': "tasks"
                },
                "columns": [
                    { "data": "id", "title": "Id" }, 
                    { "data": "name", "title": "Task" }, 
                    { "data": "isCompleted", "title": "Status" },
                    {
                        "data": null,
                        "render": function (data, type, row) {
                            return '<button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editTaskModal" data-task-id="' + data.id + '">Edit</button>';
                        }
                    },
                    {
                        "data": null,
                        "render": function (data, type, row) {
                            return '<button class="btn btn-danger btn-sm" onclick="deleteTask(' + data.id + ')">Delete</button>';
                        }
                    }
                ]
            });

            setupFormSubmit({
                selector: '#addTaskForm', 
                requestType: 'POST', 
                actionUrl: "{{ route('api.auth.tasks.store') }}",
                headers: {
                    'Authorization': 'Bearer ' + authToken,
                },
                successCallback: function (response) {

                    alert(response.message);
                    $('button[data-bs-dismiss="modal"]').click();
                }
            });

            $('#editTaskModal').on('show.bs.modal', function (event) {
                const baseUrl = window.location.origin;
                const editTaskButton = event.relatedTarget;
                const taskId = editTaskButton.dataset.taskId;

                sendAjaxRequest({
                    url: `${baseUrl}/api/v1/tasks/${taskId}`,
                    type: 'GET',
                    headers: {
                        'Authorization': 'Bearer ' + authToken,
                    },
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        const taskObject = response.task;

                        $('#editNameInput').val(taskObject.name);
                        $('#taskIdInput').val(taskObject.id);

                        const taskStatus = (taskObject.isCompleted) ? true : false;
                        $('#editStatusInput').attr('checked', taskStatus);
                           
                    },
                    error: function (error) {
                        const errorResponse = error.responseText;
                        const responseObject = JSON.parse(errorResponse);
                        
                        console.error(error.responseText);
                        alert(responseObject.message);
                    }
                });
             
                
            });

            
            $("#editTaskForm").on('submit', function (event) {
                event.preventDefault();
                
                const submittedForm = event.target;
                const taskId = $('#taskIdInput').val();
                const formInupts = {};

                $(submittedForm).serializeArray().forEach(input => {
                    formInupts[input.name] = input.value;
                });
                console.log(formInupts);
                sendAjaxRequest({
                    url: `${baseUrl}/api/v1/tasks/${taskId}`,
                    type: 'PATCH',
                    data: $(submittedForm).serializeArray(),
                    headers: {
                        'Authorization': 'Bearer ' + authToken,
                    },
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        alert(response.message);                            
                    },
                    error: function (error) {
                        const errorResponse = error.responseText;
                        const responseObject = JSON.parse(errorResponse);
                        
                        console.error(error.responseText);
                        alert(responseObject.message);
                    }
                });
            });
        })
    </script>
</body>
</html>