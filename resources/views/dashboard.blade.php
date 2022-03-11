@extends('layouts.app', ['activePage' => 'pins_generator', 'titlePage' => __('PINs Generator')])

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="container-fluid">
                <div class="card card-plain">
                    <div class="card-header card-header-primary">
                        <div class="row">
                            <div class="col-8">
                                <h4 class="card-title">Generated PINs List</h4>
                            </div>
                            <div class="col-4">
                                <button type="button" class="btn btn-default float-right"
                                        data-toggle="modal" data-target="#generatePINs">
                                    <i class="fa fa-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card-body">
                                <table id="generated-pins-table" class="table table-bordered yajra-datatable" style="width: 100%;">
                                    <thead>
                                    <tr>
                                        <th class="text-center" width="10%">No</th>
                                        <th class="text-center" width="30%">Name</th>
                                        <th class="text-center" width="30%">Email</th>
                                        <th class="text-center" width="30%">PIN</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="generatePINs" tabindex="-1" role="dialog" aria-labelledby="generatePINsLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="generatePINsLabel">Generate PINs</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form action="" method="post" id="generatePINsForm" enctype='multipart/form-data'>
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Enter username">
                        </div>

                        <div class="form-group">
                            <label for="email">Email address</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Enter user Email">
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="submitForm">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function () {
            let table = $('#generated-pins-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('pins_generator.list') }}",
                },
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'name', name: 'name'},
                    {data: 'email', name: 'email'},
                    {data: 'pin', name: 'pin'},
                ]
            });


            $('#name, #email').attr('required', true);

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#generatePINsForm').on('submit', function (e){
                e.preventDefault();
                let formData = new FormData(this);
                $.ajax({
                    type: "POST",
                    url: "{{ route('pins_generator.store') }}",
                    contentType: false,
                    cache: false,
                    processData: false,
                    data: formData,
                    success: function(msg) {
                        table.draw();
                        $('#generatePINs').modal('toggle');
                        $('#generatePINs form')[0].reset();
                    }
                });
            });
        });
    </script>
@endsection
