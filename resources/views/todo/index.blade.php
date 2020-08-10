<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.1/css/bootstrap.min.css" >

    <title>Todo List!</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css" />
    
    <style>
        .checked{text-decoration: line-through;}
    </style>
  </head>
  <body>

    <div class="container mt-5">
        <div class="row">
            <div class="col-lg-6 offset-lg-3">
                <div class="card">
                    <div class="card-header font-weight-bold text-success">
                        Yapılacaklar
                    </div>
                    <ul class="list-group list-group-flush" id="todos">
                       
                    </ul>
                </div>

                <div class="card mt-1">
                    <div class="card-body">
                        <div class="input-group input-group-lg">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1">Todo</span>
                            </div>
                            <input type="text" class="form-control" id="todo" placeholder="Todo...." autofocus>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row" id="error">
        </div>
        <div class="row" id="success">
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" ></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.1/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="{{ asset('js/notify.min.js') }}"></script>
    <script>
        $(()=>{
            console.clear();
            console.log("%cTodo starting...", "color:green;font-style: italic;font-size:20px");

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            //todo add
            $('#todo').keyup(function(event){
                if(event.keyCode == 13){
                    $.ajax({
                        url         : 'todo-add',
                        type        : 'POST',
                        dataType    : 'json', 
                        data        : {todo:$('#todo').val().trim()},
                        error       : function(response) {
                            $("#error").text(JSON.stringify(response));
                        },
                        success     : function(response){
                            if(response.result){
                                $.notify('Başarıyla eklendi', 'success');
                                $('#todo').val('');
                                getTodo();
                            }else{
                                $.notify('İşlem Başarısız..')
                            }
                            //$('#success').text(JSON.stringify(response))
                        }

                    });
                }
            });

            //todo delete
            $(document).on('click', '.todo-delete', function(){
                const $this = $(this);
                const id = $(this).data('id');

                $.ajax({
                    url         : 'todo-delete',
                    type        : 'POST',
                    dataType    : 'json', 
                    data        : {id:id},
                    error       : function(response) {
                        $("#error").text(JSON.stringify(response));
                    },
                    success     : function(response){
                        //$('#success').text(JSON.stringify(response))

                        if(response.result){
                            getTodo();
                            $.notify('İşlem başarılı..', 'success');
                        }else{
                            $.notify('İşlem başarısız', 'error');
                        }
                    }
                });

            });

            // todo status change 
            $(document).on('change', '.todo-status', function(){
                const $this     = $(this);
                const id        = $this.data('id');
                const status    = $this.attr('data-status') == 'complete' ? 'uncomplete' : 'complete';

                $.ajax({
                    url         : 'todo-status-change',
                    type        : 'POST',
                    dataType    : 'json', 
                    data        : {id:id, status:status},
                    error       : function(response) {
                        $("#error").text(JSON.stringify(response));
                    },
                    success     : function(response){
                        $('#success').text(JSON.stringify(response))

                        if(response.result){
                            getTodo();
                            $.notify('İşlem başarılı..', 'success');
                        }else{
                            $.notify('İşlem başarısız', 'error');
                        }
                    }
                });
            });

            getTodo();
        });

        function getTodo(){
            $.ajax({
                url         : 'get-todos',
                dataType    : 'json', 
                error       : function(response) {
                    $("#error").text(JSON.stringify(response));
                },
                success     : function(response){
                    //console.log(response)

                    let dataHTML        = '';
                    let completeChecked = '';
                    response.forEach((todo, index, todos)=>{
                        completeChecked = todo.status == 'complete' ?  'checked' : '';

                        dataHTML += `<li class="list-group-item d-flex justify-content-between align-items-center ml-2">
                                        <input type="checkbox" class="form-check-input todo-status" data-status="${todo.status}" 
                                                id="todo-checkbox-${todo.id}" data-id="${todo.id}" ${completeChecked}> 
                                        <label class="${completeChecked} pt-3" for="todo-checkbox-${todo.id}">${todo.todo}</label>
                                        <span style="cursor:pointer" class="todo-delete" data-id="${todo.id}"><i class="fas fa-trash-alt text-danger"></i></span>
                                    </li>`;
                    });

                    $('#todos').html(dataHTML);
                }

            });
        }
    </script>
  </body>
</html>