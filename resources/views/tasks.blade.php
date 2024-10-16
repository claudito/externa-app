<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Tareas</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
        integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap4.css">
</head>

<body>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1>Tareas</h1>
                <hr>
                <div class="form-group">
                    <button class="btn btn-primary btn-agregar">Agregar</button>
                </div>
                <table id="consulta" class="table">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Title</th>
                            <th>Descripción</th>
                            <th>Fecha de Vencimiento</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>


    <!-- Modal -->
    <form id="registro" autocomplete="off">
        <!-- Modal -->
        <div class="modal fade" id="modal-registro" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" name="id">
                        <input type="hidden" name="user_id" value="{{ $user->id }}">
                        <div class="form-group">
                            <label>Titulo</label>
                            <input type="text" name="title" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label>Descripción</label>
                            <input type="text" name="description" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label>Fecha de Vencimiento</label>
                            <input type="date" name="last_date" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label>Estado</label>
                            <select name="status_id" class="form-control" required>
                                <option value="">Seleccionar</option>
                                @foreach ($estados as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary btn-submit">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous">
    </script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap4.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            $('#consulta').DataTable({
                ajax: {
                    'url': '{{ route('tasks.index') }}',
                    'type': 'GET'
                },
                columns: [{
                        data: 'id'
                    },
                    {
                        data: 'titulo'
                    },
                    {
                        data: 'descripcion'
                    },
                    {
                        data: 'fecha_vencimiento'
                    },
                    {
                        data: 'estado'
                    },
                    {
                        data: null,
                        render: function(data) {
                            return ` 
                                <button  data-id="${data.id}" class="btn btn-primary btn-edit">Edit</button>
                                <button  data-id="${data.id}" class="btn btn-danger btn-delete">Eliminar</button>
                            `;
                        }
                    }
                ]
            });
        })

        //Cargar Modal Agregar
        $(document).on('click', '.btn-agregar', function(e) {
            $('#registro')[0].reset();
            $('#modal-registro input[name="id"]').val('');
            $('#modal-registro').modal('show');
            $('#modal-registro .modal-title').html('Agregar');
            $('#modal-registro .btn-submit').html('Agregar');
            e.preventDefault();
        });

        //Cargar Modal Actualizar
        $(document).on('click', '.btn-edit', function(e) {
            $('#registro')[0].reset();
            $('#modal-registro input[name="id"]').val('');
            id = $(this).data('id');
            var url = '{{ route('tasks.show', ':id') }}';
            url = url.replace(':id', id);
            $.ajax({
                url: url,
                type: 'GET',
                data: {
                    '_token': '{{ csrf_token() }}'
                },
                dataType: 'JSON',
                success: function(data) {
                    $('#modal-registro input[name="id"]').val(data.id);
                    $('#modal-registro input[name="title"]').val(data.title);
                    $('#modal-registro input[name="description"]').val(data.description);
                    $('#modal-registro input[name="last_date"]').val(data.last_date);
                    $('#modal-registro select[name="status_id"]').val(data.status_id);
                }
            });

            $('#modal-registro').modal('show');
            $('#modal-registro .modal-title').html('Actualizar');
            $('#modal-registro .btn-submit').html('Actualizar');
            e.preventDefault();
        });

        //Agregar - Actualizar
        $(document).on('submit', '#registro', function(e) {
            parametros = $(this).serialize();
            $.ajax({
                url: '{{ route('tasks.store') }}',
                type: 'POST',
                data: parametros,
                dataType: 'JSON',
                beforeSend: function() {
                    Swal.fire({
                        title: "Cargando",
                        text: "Espere un momento, no cierre la ventana.",
                        showConfirmButton: false
                    });
                },
                success: function(data) {
                    Swal.fire({
                        title: data.message,
                        text: "...",
                        icon: data.status,
                        showConfirmButton: false
                    });
                    $('#modal-registro').modal('hide');
                    $('#consulta').DataTable().ajax.reload()
                }
            });
            e.preventDefault();
        });

        //Agregar - Actualizar
        $(document).on('click', '.btn-delete', function(e) {
            id = $(this).data('id');
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    var url = '{{ route('tasks.delete', ':id') }}';
                    url = url.replace(':id', id);
                    $.ajax({
                        url: url,
                        type: 'DELETE',
                        data: {
                            '_token': '{{ csrf_token() }}'
                        },
                        dataType: 'JSON',
                        beforeSend: function() {
                            Swal.fire({
                                title: "Cargando",
                                text: "Espere un momento, no cierre la ventana.",
                                showConfirmButton: false
                            });
                        },
                        success: function(data) {
                            Swal.fire({
                                title: data.message,
                                text: "...",
                                icon: data.status,
                                showConfirmButton: false
                            });
                            $('#modal-registro').modal('hide');
                            $('#consulta').DataTable().ajax.reload()
                        }
                    });
                }
            });
            e.preventDefault();
        });
    </script>
</body>

</html>
