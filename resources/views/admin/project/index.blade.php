@extends('admin.layouts.app')
@section('title', 'Project')
@section('main-content')

    
    <x-page-layout>
        @slot('pageTitle')Project @endslot
        @slot('breadcrumb')
        <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
        <li class="breadcrumb-item active">Data Project</li>
        @endslot

        @slot('title')Data Project @endslot
        @slot('button')           
        <button class="btn btn-outline-primary mb-3" onclick="create()"><i class="fas fa-plus"></i> Tambah Project</button>  
        <form id="search-form" action="{{ route('project') }}">
        <div class="row row-cols-lg-auto g-3 align-items-center">
            <div class="col-1">
            <h3>Filter</h3>
            </div>
            {{-- <div class="col-3">
                <div class="form-group">
                    <input class="form-control rounded-3"  type="text" name="byName" id="byName">
                </div>
            </div> --}}
            <div class="form-group">
                <select class="form-control rounded-3" aria-label="Default select example" id="byClient" name="byClient">
                    <option selected>Client</option>
                    @foreach ($client as $item)
                    <option value="{{$item->id}}">{{$item->client_name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-2">
                <div class="form-group">
                    <select class="form-control rounded-3" aria-label="Default select example" id="byStatus" name="byStatus">
                    <option selected>All Status</option>
                    <option value="OPEN">Open</option>
                    <option value="DOING">Doing</option>
                    <option value="DONE">Done</option>
                    </select>
                </div>
            </div>
            </form>
            <div class="col-3">
                <button class="btn btn-outline-success mb-3" onclick="event.preventDefault(); document.getElementById('search-form').submit();"><i class="fas fa-search"></i> Search</button>  
                <a href="{{route ('project')}}" type="button" class="btn btn-outline-danger mb-3"><i class="fas fa-trash"></i> Clear</a>  
            </div>
          </div>
        @endslot
        @slot('table')
        <x-dataTables>
            @slot('columns')
            <th><button class="btn btn-outline-danger btn-xs" id="multi_delete"><i class="fas fa-trash"></i> Hapus</button></th>
            <th>Action</th>
            <th>Nama Project</th>
            <th>Client</th>
            <th>Project Start</th>
            <th>Project End</th>
            <th>Project status</th>
            @endslot
        </x-dataTables>
        @endslot
        @slot('modal')
        <div class="modal-header">
            <h4 class="modal-title">Default Modal</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form id="form" method="post" class="form-horizontal" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id" id="id">
                <div class="form-group">
                    <label for="name">Project Name</label>
                    <input type="text" class="form-control" id="name" placeholder="Enter name">
                </div>
                <div class="form-group">
                    <label for="start">Project Start</label>
                    <input type="date" class="form-control" id="Project_start" placeholder="Enter start">
                </div>
                <div class="form-group">
                    <label for="end">Project End</label>
                    <input type="date" class="form-control" id="Project_end" placeholder="Enter end">
                </div>
                <div class="form-group">
                    <select class="form-control rounded-3" aria-label="Default select example" id="Client">
                        <option selected>Client</option>
                        @foreach ($client as $item)
                        <option value="{{$item->id}}">{{$item->client_name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                     <select class="form-control rounded-3" aria-label="Default select example" id="Status">
                        <option selected>Status</option>
                        <option value="OPEN">Open</option>
                        <option value="DOING">Doing</option>
                        <option value="DONE">Done</option>
                     </select>
                </div>
            </form>
          </div>      
        @endslot
    </x-page-layout>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    var table = $('#datatables').DataTable({
        
        processing: true,
        serverSide: true,
        responsive: true,
        pageLength: 5,
        searching: false,
        lengthMenu: [ 5, 10, 25, 50, 75, 100 ],
        ajax: "",
        columns: [
            { data: 'DT_RowIndex', searchable: false, orderable: false},
            { data: 'checkbox', name: 'checkbox', searchable: false, orderable: false},
            { data: 'action', name: 'action', searchable: false, orderable: false},
            { data: 'project', name: 'project'},
            { data: 'Client Name', name: 'Client Name'},
            { data: 'start', name: 'start'},
            { data: 'end', name: 'end'},
            { data: 'status', name: 'status'},   
        ],
            "columnDefs": [
                {
                    
                    "className": 'text-center',
                    
                }
            ]
    })
</script>
<script>
    function index() {
        var url = "{{ route('project') }}";
        location.reload();
    }
    function create(){
        submit_method = 'create';
            $('#form')[0].reset();
            $('.form-group').removeClass('has-error'); // clear error class
            $('.help-block').empty(); // clear error string
            $('#modal_form').modal('show'); // show bootstrap modal
            $('.modal-title').text('Tambah Data Project');
            $('#id').val('');
  
    }
    function edit(id){
            submit_method = 'edit';
            $('#form')[0].reset();
            $('.form-group').removeClass('has-error'); // clear error class
            $('.help-block').empty(); // clear error string

            var url = "{{ route('project.edit',":id") }}";
            url = url.replace(':id', id);
            
            $.get(url, function (data) {
                $('#name').val(data.data.project_name);
                $('#Project_start').val(data.data.project_start);
                $('#Project_end').val(data.data.project_end);
                $('#Client').val(data.data.status);
                $('#Status').val(data.data.project_status);
                $('#id').val(data.data.id);
                $('#modal_form').modal('show');
                $('.modal-title').text('Edit Data Project');
            });
        }
        function submit() {
            var id       = $('#id').val();
            var name     = $('#name').val();
            var start    = $('#Project_start').val();
            var end      = $('#Project_end').val();
            var Client   = $('#Client').val();
            var Status   = $('#Status').val();
            $('#btnSave').text('Menyimpan...');
            $('#btnSave').attr('disabled', true);
            var pesan;

            if(submit_method == 'create') {
                pesan ='Data Project berhasil ditambahkan';
            } else {
                pesan ='Data Project berhasil diperbaharui';
            }

            $.ajax({
                url: "{{ route('project.store') }}",
                type: 'POST',
                dataType: 'json',
                data: {
                    "_token": "{{ csrf_token() }}",
                    id              : id,
                    project_name    : name,
                    client_id       : Client,
                    project_start   : start,
                    project_end     : end,
                    project_status  : Status,
                },
                success: function (data) {
                    if(data.status) {
                        $('#modal_form').modal('hide');
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: pesan,
                            showConfirmButton: false,
                            timer: 1500
                    });
                        table.draw();
                    }
                    else{
                        for (var i = 0; i < data.inputerror.length; i++) 
                        {
                            $('[name="'+data.inputerror[i]+'"]').parent().parent().addClass('has-error'); //select parent twice to select div form-group class and add has-error class
                            $('[name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]); //select span help-block class set text error string
                        }
                    }
                    $('#btnSave').text('Simpan');
                    $('#btnSave').attr('disabled',false); //set button enable 
                }, 
                error: function(data){
                    var error_message="";
                    error_message +=" ";
                    $.each( data.responseJSON.errors, function( key, value ) {
                        error_message +=" "+value+" ";
                    });

                    error_message +=" ";
                    Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'error',
                            title: 'ERROR !',
                            text: error_message,
                            showConfirmButton: false,
                            timer: 2000
                        });
                    $('#btnSave').text('Simpan');
                    $('#btnSave').attr('disabled', false);
                },
            });
        }
        function destroy(id) {
            var url = "{{ route('project.delete',":id") }}";
            url = url.replace(':id', id);
        Swal.fire({
            title             : "Hapus Data",
            text              : "Apakah Anda yakin akan hapus data ini!?",
            icon              : "warning",
            showCancelButton  : true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor : "#d33",
            confirmButtonText : "Ya, Hapus!"
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url    : url,
                    type   : "delete",
                    data: {
                    "_token": "{{ csrf_token() }}",
                    "id":id
                    },
                    dataType: "JSON",
                    success: function(data) {
                        $('#datatables').DataTable().ajax.reload();
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: 'Data berhasil dihapus',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                })
            }
        })
    }
    $(document).on('click', '#multi_delete', function() {
       var url = "{{ route('project.multi-delete') }}";
       var id = [];
       $('.data_checkbox:checked').each(function() {
                 id.push($(this).val());
            });
       if (id.length == 0) {
        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'error',
                            title: 'ERROR !',
                            text: "Pilih Data Yang Akan Dihapus",
                            showConfirmButton: false,
                            timer: 2000
                });
       } else {
        Swal.fire({
            title             : "Hapus Data",
            text              : "Apakah Anda yakin akan hapus data ini!?",
            icon              : "warning",
            showCancelButton  : true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor : "#d33",
            confirmButtonText : "Ya, Hapus!"
        }).then((result) => {
        if (result.isConfirmed) {
                $.ajax({
                    url    : url,
                    type   : "Get",
                    data: {
                    "id":id
                    },
                    dataType: "JSON",
                    success: function(data) {
                        $('#datatables').DataTable().ajax.reload();
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: 'Data berhasil dihapus',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                })     
            }
        }) 
    }       
});        
</script>
@endpush