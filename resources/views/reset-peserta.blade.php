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
                            <a href="javascript:void(0)" class="btn btn-danger btnBulkReset" id="btnBulkReset"><i class="ri-refresh-line me-1"></i> Reset Peserta</a>
                        </div>
                        <div class="col-sm-auto">
                            <div class="d-flex gap-1 flex-wrap">
                                <button type="button" class="btn btn-info" onclick="reload()"><i class="ri-refresh-line ri-lg"></i></button>
                            </div>
                        </div>
                    </div>

                    <div class="card-header border-0 d-flex align-items-center pt-0 pb-0">
                        <div class="col-auto me-2">
                            <select id="ruang_id" class="form-control form-select" onchange="reload()">
                                <option value="">Semua Ruang</option>
                                @foreach ($getRuang as $ruang)
                                    <option value="{{ $ruang->id }}">{{ $ruang->nama }}</option>
                                @endforeach
                            </select>
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
                        dt.ruang_id = $('#ruang_id').val();

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
            $(".check-all").prop('checked', false);
        }

        $('body').on('click', '.btnReset', function() {
            var id = $(this).data('id');
            $.ajax({
                type: 'POST',
                data: {
                    '_token': "{{ csrf_token() }}"
                },
                url: "{{ $cUrl }}/reset/" + id,
                success: function(data) {
                    if (data.status) {
                        reload();
                        Swal.fire({
                            title: "Sukses.!",
                            text: 'Peserta berhasil direset.',
                            icon: "success",
                        });
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('Error.! Peserta gagal direset');
                }
            });
        });

        $('.btnBulkReset').click(function() {
            var id = [];
            $(".data-check:checked").each(function() {
                id.push(this.value);
            });
            if (id.length > 0) {
                if (confirm("Yakin akan mereset peserta yang dipilih.?")) {
                    $.ajax({
                        type: 'POST',
                        data: {
                            '_token': "{{ csrf_token() }}",
                            'id': id
                        },
                        url: "{{ $cUrl }}/reset/" + id,
                        success: function(data) {
                            if (data.status) {
                                reload();
                                Swal.fire({
                                    title: "Sukses.!",
                                    text: 'Peserta berhasil direset.',
                                    icon: "success",
                                });
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            alert('Error.! Peserta gagal direset');
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
@endsection
