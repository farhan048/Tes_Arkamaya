@extends('admin.layouts.app')
@section('title', 'Clients')
@section('main-content')

    
    <x-page-layout>
        @slot('pageTitle')Clients @endslot
        @slot('breadcrumb')
        <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
        <li class="breadcrumb-item active">Data Clients</li>
        @endslot

        @slot('title')Data Clients @endslot
        @slot('button')           
        <button class="btn btn-outline-primary mb-3" onclick="create()"><i class="fas fa-plus"></i> Tambah Client</button>
        @endslot
        @slot('table')
        <x-dataTables>
            @slot('columns')
            <th>Nama Client</th>
            <th>Address</th>
            <th>Action</th>
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
                    <label for="name">Name Client</label>
                    <input type="text" class="form-control" id="name" placeholder="Enter name">
                  </div>
                  <div class="form-group">
                    <label for="address" class="control-label">Address</label>
                    <textarea class="form-control" name="address" id="address" cols="30" rows="5"></textarea>
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
  
        lengthMenu: [ 5, 10, 25, 50, 75, 100 ],
        ajax: "",
        columns: [
            { data: 'DT_RowIndex', searchable: false, orderable: false},
            { data: 'client_name', name: 'client_name'},
            { data: 'address', name: 'address'},
            { data: 'action', name: 'action', searchable: false, orderable: false}
            
            ]
    })
</script>
<script>
    function create(){
        submit_method = 'create';
            // $('#form')[0].reset();
            $('.form-group').removeClass('has-error'); // clear error class
            $('.help-block').empty(); // clear error string
            $('#modal_form').modal('show'); // show bootstrap modal
            $('.modal-title').text('Tambah Data Client');
            $('#id').val('');
  
    }
        function edit(id){
            submit_method = 'edit';
            $('#form')[0].reset();
            $('.form-group').removeClass('has-error'); // clear error class
            $('.help-block').empty(); // clear error string

            var url = "{{ route('client.edit',":id") }}";
            url = url.replace(':id', id);
            
            $.get(url, function (data) {
                $('#name').val(data.data.client_name);
                document.getElementById('address').value = data.data.address;
                $('#id').val(data.data.id);
                $('#modal_form').modal('show');
                $('.modal-title').text('Edit Data Client');
            });
        }
        function submit() {
            var id         = $('#id').val();
            var name       = $('#name').val();
            var address    = $('#address').val();
            $('#btnSave').text('Menyimpan...');
            $('#btnSave').attr('disabled', true);
            var pesan;

            if(submit_method == 'create') {
                pesan ='Data Client berhasil ditambahkan';
            } else {
                pesan ='Data Client berhasil diperbaharui';
            }

            $.ajax({
                url: "{{ route('client.store') }}",
                type: 'POST',
                dataType: 'json',
                data: {
                    "_token": "{{ csrf_token() }}",
                    id: id,
                    client_name: name,
                    address: address
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
            var url = "{{ route('client.delete',":id") }}";
            url = url.replace(':id', id);
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
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
</script>
@endpush