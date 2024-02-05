@extends('layouts.master')
@section('title')
    {{ $title }}
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            Dashboard
        @endslot
        @slot('title')
            {{ $title }}
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div>
                <div class="card">
                    <div class="card-header  border-0 d-flex align-items-center">
                        <div class="col-sm">
                            <a href="javascript:void(0)" class="btn btn-primary btnCreate" id="btnCreate"><i class="ri-add-line me-1"></i> Tambah</a>
                        </div>
                        <div class="col-sm-auto">
                            <div class="d-flex gap-1 flex-wrap">
                                <button type="button" class="btn btn-info" onclick="reload()"><i class="ri-refresh-line ri-lg"></i></button>
                            </div>
                        </div>
                    </div>

                    <!-- end card header -->
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="dataTables" class="table table-nowrap table-bordered table-striped align-middle">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        @foreach ($dataTable as $key => $value)
                                            <th>
                                                @php echo $value['label'] ?? ucwords(strtolower(str_replace(['_id', '_'], ['', ' '], isset(explode('.', $key)[1]) ? explode('.', $key)[1] : $key))) @endphp
                                            </th>
                                        @endforeach
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-data" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-light p-3">
                    <h5 class="modal-title" id="exampleModalLabel">&nbsp;</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
                </div>
                <div class="modal-body">
                    <form id="form-data" class="tablelist-form" autocomplete="off">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" class="form-control" name="id">
                        @foreach ($formData as $key => $value)
                            @isset($value['groupStart'])
                                <div class="card card-info">
                                    <div class="card-header">
                                        <span class="card-title">{{ $value['groupStart'] }}</span>
                                    </div>
                                    <div class="card-body">
                                    @endisset

                                    <div class="row mb-2">
                                        <label for="" class="col-sm-4 col-form-label">{!! $value['label'] ?? ucwords(strtolower(str_replace(['_id', '_'], ['', ' '], $key))) !!}</label>
                                        <div class="{{ isset($value['colWidth']) ? $value['colWidth'] : 'col-sm-8' }}">
                                            @if (isset($value['type']) && $value['type'] == 'select')
                                                @if (isset($value['options']))
                                                    <select name="{{ $key }}{{ isset($value['multiple']) && $value['multiple'] ? '[]' : null }}" class="form-control {{ isset($value['class']) ? $value['class'] : null }} {{ isset($value['select2']) ? 'select2' : null }}" {{ isset($value['multiple']) && $value['multiple'] ? 'multiple' : null }}>
                                                        @if (is_array($value['options']))
                                                            @foreach ($value['options'] as $optionKey => $optionValue)
                                                                <option value="{{ $optionKey }}">{{ $optionValue }}</option>
                                                            @endforeach
                                                        @else
                                                            {!! $value['options'] !!}
                                                        @endif
                                                    </select>
                                                @endif
                                            @elseif (isset($value['type']) && $value['type'] == 'textarea')
                                                <textarea name="{{ $key }}" class="form-control ">{{ isset($value['value']) ? $value['value'] : null }}</textarea>
                                            @else
                                                <input type="{{ isset($value['type']) ? $value['type'] : 'text' }}" name="{{ $key }}" value="{{ isset($value['value']) ? $value['value'] : null }}" class="form-control ">
                                            @endif
                                            <span class="invalid-feedback"></span>
                                        </div>
                                    </div>
                                    @isset($value['groupEnd'])
                                    </div>
                                </div>
                            @endisset
                        @endforeach
                    </form>
                </div>
                <div class="modal-footer">
                    <div class="hstack gap-2 justify-content-end">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <a href="javascript:void(0)" class="btn btn-success btnSave"><i class="bx bx-save"></i> Simpan</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        var table;
        var ordering = {{ isset($dataTableOrder) ? 'true' : 'false' }};

        $(function() {
            table = $('#dataTables').DataTable({
                initComplete: function() {
                    var api = this.api();
                    $('#dataTables_filter input')
                        .off('.DT')
                        .on('keyup.DT', function(e) {
                            if (e.keyCode == 13) {
                                api.search(this.value).draw();
                            }
                        });
                },
                "language": {
                    "paginate": {
                        "previous": "<",
                        "next": ">",
                    },
                    "emptyTable": "<div class='m-4 text-bold'>Tidak ada data yang ditampilkan.</div>"
                },
                oLanguage: {
                    sProcessing: 'loading...',
                    sSearch: '',
                    sSearchPlaceholder: 'Search',
                    sLengthMenu: '_MENU_',
                },
                "paging": true,
                "searching": true,
                "ordering": ordering,
                "info": true,
                "autoWidth": false,
                "lengthChange": true,
                "bDestroy": true,
                "responsive": true,
                'processing': true,
                'serverSide': true,
                "ajax": {
                    "url": "{{ $cUrl }}/data-tables",
                    "type": "GET",
                    data: function(dt) {
                        dt._token = "{{ csrf_token() }}";
                        @isset($dataTableFilter)
                            @foreach ($dataTableFilter as $key => $value)
                                dt.{{ isset(explode('.', $key)[1]) ? explode('.', $key)[1] : $key }} = $('#{{ isset(explode('.', $key)[1]) ? explode('.', $key)[1] : $key }}').val();
                            @endforeach
                        @endisset
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        width: "30px",
                        className: "text-center nowrap"
                    },

                    @foreach ($dataTable as $key => $value)
                        {
                            data: "{{ isset(explode('.', $key)[1]) ? explode('.', $key)[1] : $key }}",
                            name: "{{ $key }}",
                            orderable: {{ isset($value['orderable']) && $value['orderable'] ? 'true' : 'false' }},
                            searchable: {{ isset($value['searchable']) && $value['searchable'] ? 'true' : 'false' }},
                            width: "{{ isset($value['width']) && $value['width'] ? $value['width'] : null }}",
                            className: "{{ isset($value['className']) && $value['className'] ? $value['className'] : null }}",
                        },
                    @endforeach

                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        width: "10px",
                        className: "text-center nowrap"
                    },
                ],
                @isset($dataTableOrder)
                    order: [
                        @php
                            foreach ($dataTableOrder as $orderName) {
                                $no = 1;
                                foreach ($dataTable as $key => $value) {
                                    $order = explode(' ', $orderName);
                                    if ($key == $order[0]) {
                                        echo "[$no,'$order[1]'],";
                                    }
                                    ++$no;
                                }
                            }
                        @endphp
                    ]
                @endisset

            });
        });

        function reload() {
            table.ajax.reload(null, false);
        }

        $('body').on('click', '.btnDelete', function() {
            var id = $(this).data('id');
            if (confirm("Yakin akan menghapus data ini.?")) {
                $.ajax({
                    type: 'DELETE',
                    data: {
                        '_token': "{{ csrf_token() }}"
                    },
                    url: "{{ $cUrl }}/destroy/" + id,
                    success: function(data) {
                        if (data.status) {
                            reload();
                            Swal.fire({
                                title: "Sukses.!",
                                text: 'Data berhasil dihapus.',
                                icon: "success",
                            });
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        alert('Error.! Data gagal dihapus');
                    }
                });
                return false;
            }
        });

        $('.btnBulkDelete').click(function() {
            var id = [];
            $(".data-check:checked").each(function() {
                id.push(this.value);
            });
            if (id.length > 0) {
                if (confirm("Yakin akan menghapus data ini.?")) {
                    $.ajax({
                        type: 'DELETE',
                        data: {
                            '_token': "{{ csrf_token() }}",
                            'id': id
                        },
                        url: "{{ $cUrl }}/destroy/" + id,
                        success: function(data) {
                            if (data.status) {
                                reload();
                                Swal.fire({
                                    title: "Sukses.!",
                                    text: 'Data berhasil dihapus.',
                                    icon: "success",
                                });
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            alert('Error.! Data gagal dihapus');
                        }
                    });
                    return false;
                }
            } else {
                alert('Pilih salah satu data');
            }
        });

        $(".check-all").click(function() {
            $('input:checkbox').not(this).prop('checked', this.checked);
        });
    </script>

    <script>
        $('.btnCreate').click(function() {
            save = 'add';
            $('[name="id"]').val(null);
            $('#form-data')[0].reset();
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').empty();
            $('[name="nama"]').focus();

            $('.modal-title').html('Tambah');
            $('#modal-data').modal('show');
        });

        $('body').on('click', '.btnEdit', function() {
            $('#form-data')[0].reset();
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').empty();

            var id = $(this).data('id');
            $('[name="id"]').val(id);

            $.get("{{ $cUrl }}/edit/" + id, function(data) {
                $('[name="nama"]').focus();
                $('[name="id"]').val(id);

                @foreach ($formData as $key => $value)
                    @if (isset($value['type']))
                        @if (!in_array($value['type'], ['file', 'password', 'select2']))
                            $('[name="{{ $key }}"]').val(data.{{ $key }});
                        @endif

                        @if (isset($value['select2']) && $value['select2'])
                            $('[name="{{ $key }}"]').val(data.{{ $key }}).trigger('change');
                        @endif
                    @else
                        $('[name="{{ $key }}"]').val(data.{{ $key }});
                    @endif
                @endforeach

                $('.modal-title').html('Edit');
                $('#modal-data').modal('show');
            });
        });

        $('.btnSave').click(function() {
            $('.btnSave').attr('disabled', true).html('menyimpan...');
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').empty();

            var formData = $('#form-data').serialize();
            $.ajax({
                data: formData,
                url: "{{ $cUrl }}/store",
                type: "POST",
                dataType: 'json',
                success: function(data) {
                    if (data.status) {
                        $('#form-data').trigger("reset");
                        $('.modal').modal('hide');
                        reload();
                        Swal.fire({
                            title: data.message,
                            text: data.token ? data.token : null,
                            icon: "success",
                        });
                    } else {
                        for (var i = 0; i < data.error_string.length; i++) {
                            if (data.error_string[i]) {
                                $('[name="' + data.inputerror[i] + '"]').addClass('is-invalid').next(
                                    '.invalid-feedback').html(
                                    data.error_string[i]);
                            }
                        }
                    }
                    $('.btnSave').attr('disabled', false).html('<i class="bx bx-save"></i> Simpan');
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert(errorThrown);
                    $('.btnSave').attr('disabled', false).html('<i class="bx bx-save"></i> Simpan');

                }
            });
        });
    </script>
@endsection
