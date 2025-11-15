@extends('client.layouts.master')
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

    <div>
        <div id="namaMapel" class="h4 mb-3">Mata Pelajaran: {{ $statusUjian->mataPelajaran->nama }}</div>
        <div class="row">
            <div class="col-md-3 d-none d-lg-inline">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="fs-4">Daftar Soal</div>
                    </div>
                    <div class="card-body">
                        <div class="daftarSoalContainer d-flex flex-wrap gap-2 justify-content-start">
                            <!-- tombol soal akan dibuat via JS -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div>
                            <a href="javascript:void(0)" id="soalKe" class="btn btn-primary me-3">0 dari 0 Soal</a>
                        </div>
                        <div>
                            <a href="javascript:void(0)" id="waktuUjian" class="btn btn-warning ms-1">00:00:00</a>
                            <a href="javascript:void(0)" id="btnSoalList" class="btn btn-info ms-1 d-lg-none">
                                <i class="ri-file-list-line ri-lg"></i>
                                <span class="d-none d-lg-inline ms-1">Daftar Soal</span>
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="mb-2">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <a href="javascript:void(0)" id="btnZoomReset" class="btn-sm me-2" title="Font Size Reset"><i class="ri-refresh-line ri-lg text-success"></i></a>
                                    <a href="javascript:void(0)" id="btnZoomOut" class="btn-sm" title="Font Size -"><i class="ri-indeterminate-circle-line ri-lg"></i></a>
                                    <a href="javascript:void(0)" id="btnZoomIn" class="btn-sm" title="Font Size +"><i class="ri-add-circle-line ri-lg"></i></a>
                                </div>
                                <span class="saveStatus"></span>
                            </div>
                        </div>
                        <div id="soalList"></div>
                        <div id="opsiJawabanList"></div>
                    </div>
                    <div class="card-footer d-flex justify-content-between">
                        <div><a href="javascript:void(0)" id="btnPrev" class="btn btn-secondary"><i class=" ri-arrow-left-line"></i> Sebelumnya</a></div>
                        <div>
                            <a href="javascript:void(0)" id="btnNext" class="btn btn-primary">Selenjutnya <i class="ri-arrow-right-line"></i></a>
                            <a href="javascript:void(0)" class="btn btn-success btnFinish d-none">Selesai</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Daftar Soal -->
        <div class="modal fade" id="modalSoalList" tabindex="-1" aria-labelledby="modalSoalListLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-light p-3">
                        <h5 class="modal-title" id="exampleModalLabel">Daftar Soal</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="daftarSoalContainer d-flex flex-wrap gap-2 justify-content-start">
                            <!-- tombol soal akan dibuat via JS -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection

    @section('script')
        <script>
            // ==========================================================
            // 🧩 VARIABEL AWAL
            // ==========================================================
            const waktuSekarang = "{{ date('Y-m-d H:i:s') }}"; // waktu server sekarang
            const alokasiWaktuSoal = parseInt("{{ $statusUjian->alokasi_waktu_soal }}"); // alokasi waktu per soal (menit)
            const alokasiWaktuPeserta = parseInt("{{ $statusUjian->alokasi_waktu_peserta }}"); // total waktu peserta (menit)
            const waktuMulai = "{{ $statusUjian->mode_waktu == 'waktu-peserta' ? $statusPesertaUjian->waktu_mulai : $statusUjian->waktu_ujian }}";
            const statusUjianId = "{{ $statusUjian->id }}";
            const statusPesertaUjianId = "{{ $statusPesertaUjian->id }}";

            // key penyimpanan lokal (unik per ujian)
            const soalStorageKey = `soalUjian_${statusUjianId}`;
            const jawabanStorageKey = `jawaban_${statusUjianId}`;
            const jawabanChangedKey = `jawabanChanged_${statusUjianId}`; // unik per ujian
            sessionStorage.setItem(jawabanChangedKey, 'false'); // default awal = tidak ada perubahan

            // variabel utama
            var realtimeSave = true;
            let currentNomor = 1;
            let fontSize = parseInt(sessionStorage.getItem('fontSize') || 16);
            let countdownInterval = null;
            let autosaveInterval = null;

            // ambil data jawaban tersimpan di localStorage
            let jawabanPeserta = JSON.parse(localStorage.getItem(jawabanStorageKey)) || {};

            // ==========================================================
            // 📦 AMBIL SOAL DARI SERVER
            // ==========================================================
            function ambilSoalDariServer() {
                sessionStorage.removeItem(soalStorageKey);

                $('#soalList').html(`<div class="text-center p-4">Memuat soal...</div>`);
                $.ajax({
                    url: "{{ url('ujian/ambil-soal') }}",
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        status_peserta_ujian_id: statusPesertaUjianId,
                    },
                    success: function(response) {
                        if (response.status && Array.isArray(response.soal)) {
                            const soalArray = response.soal;
                            sessionStorage.setItem(soalStorageKey, JSON.stringify(soalArray));
                            getSoal(1); // tampilkan soal pertama
                            tampilkanDaftarSoal(false);
                        } else {
                            disableUjian();
                            if (response.status_ujian == 1) {
                                $('.btnFinish').removeClass('d-none').removeClass('disabled').attr('disabled', false);
                            } else {
                                $('.btnFinish').addClass('d-none').addClass('disabled').attr('disabled', true);
                            }
                            $('#opsiJawabanList').html(``);
                            $('#soalList').html(`<div class="text-center h3 p-4">${response.message || 'Data tidak tersedia'}</div>`);
                        }
                    },
                    error: function(err) {
                        console.error("❌ Gagal memuat soal:", err);
                        $('#soalList').html(`<div class="text-danger">Terjadi kesalahan memuat soal.</div>`);
                    }
                });
            }

            // ==========================================================
            // 💾 SIMPAN & AMBIL JAWABAN
            // ==========================================================
            function simpanJawaban(soalId, value) {
                jawabanPeserta[soalId] = value;
                localStorage.setItem(jawabanStorageKey, JSON.stringify(jawabanPeserta));
                // console.log("Berhasil menyimpan jawaban.");

                // update status tombol daftar soal (jika modal sedang terbuka)
                if ($('#modalSoalList').hasClass('show')) {
                    tampilkanDaftarSoal();
                } else {
                    tampilkanDaftarSoal(false);
                }

                // tandai ada perubahan
                sessionStorage.setItem(jawabanChangedKey, 'true');
            }

            function ambilJawabanTersimpan(soalId) {
                return jawabanPeserta[soalId] || '';
            }

            // ==========================================================
            // 📤 KIRIM JAWABAN KE SERVER
            // ==========================================================
            function kirimJawabanKeServer() {
                const changedFlag = sessionStorage.getItem(jawabanChangedKey);
                if (changedFlag !== 'true') {
                    // console.log('⏸️ Tidak ada perubahan jawaban — tidak perlu kirim.');
                    return; // skip kirim kalau tidak ada perubahan
                }

                const jawabanLocal = JSON.parse(localStorage.getItem(jawabanStorageKey)) || {};
                const payload = Object.entries(jawabanLocal).map(([soal_id, jawaban]) => ({
                    soal_id: parseInt(soal_id),
                    jawaban: jawaban
                }));

                if (payload.length === 0) return;

                $.ajax({
                    url: "{{ url('ujian/simpan-jawaban') }}",
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        status_ujian_id: statusUjianId,
                        jawaban: JSON.stringify(payload)
                    },
                    success: function(res) {
                        if (res.status) {
                            // console.log("✅ Jawaban tersimpan otomatis:", res);

                            // reset flag perubahan
                            sessionStorage.setItem(jawabanChangedKey, 'false');
                            $('.btnFinish').removeClass('btn-danger').addClass('btn-success');
                            $('.saveStatus').text('✅ Jawaban tersimpan').fadeIn().delay(1500).fadeOut();
                        }
                    },
                    error: function(err) {
                        console.error("❌ Gagal menyimpan jawaban:", err);
                        $('.btnFinish').removeClass('btn-success').addClass('btn-danger');
                    }
                });
            }

            // ==========================================================
            // ⏱️ AUTO SAVE JAWABAN
            // ==========================================================
            function mulaiAutoSave(intervalMenit = 3) {
                const intervalMs = intervalMenit * 60 * 1000;
                kirimJawabanKeServer(); // kirim pertama kali saat mulai
                autosaveInterval = setInterval(kirimJawabanKeServer, intervalMs);
                // console.log(`⏱️ Autosave aktif setiap ${intervalMenit} menit.`);
            }

            // window.addEventListener('beforeunload', function(e) {
            //     kirimJawabanKeServer(); // simpan terakhir kali
            //     e.preventDefault();
            //     e.returnValue = '';
            // });

            // ==========================================================
            // 🧠 FUNGSI MENAMPILKAN SOAL
            // ==========================================================
            function getSoal(nomor = 1) {
                const soalData = sessionStorage.getItem(soalStorageKey);
                if (!soalData) {
                    $('#soalList').html(`<div class="text-center text-muted">Soal belum dimuat.</div>`);
                    return;
                }

                const soalList = JSON.parse(soalData);
                if (!Array.isArray(soalList) || soalList.length === 0) {
                    $('#soalList').html(`<div class="text-center text-muted">Soal belum tersedia.</div>`);
                    return;
                }

                const soal = soalList[nomor - 1];
                if (!soal) return;

                // simpan jawaban soal sebelumnya sebelum ganti soal
                if (currentNomor !== nomor) {
                    const prevSoal = soalList[currentNomor - 1];
                    if (prevSoal) {
                        const currentInput = $('[name="jawaban_input"]:checked, #jawaban_text, #jawaban_area');
                        if (currentInput.length > 0) {
                            simpanJawaban(prevSoal.id, currentInput.val());
                        }
                    }
                }

                // tampilkan teks soal
                $('#soalList').html(`
                    <div class="mb-3">
                        <div class="fw-bold">Soal ${nomor}.</div>
                        <div>${soal.soal_teks}</div>
                    </div>
                `);

                let opsiHtml = '';
                const jawabanTersimpan = ambilJawabanTersimpan(soal.id);

                // opsiHtml += `<div class="fw-bold">Jawaban: <span class="saveStatus" style="font-size: 14px !important;"></span></div>`;
                opsiHtml += `<div class="fw-bold">Jawaban:</div>`;
                // jenis soal pilihan ganda
                if (soal.jenis_soal === 'pilihan') {
                    if (soal.opsi_jawaban && soal.opsi_jawaban.length > 0) {
                        const opsiObj = soal.opsi_jawaban[0];
                        // const kunciTerurut = Object.keys(opsiObj).sort(); // urutkan "jawaban_1", "jawaban_2", ...
                        const kunciTerurut = Object.keys(opsiObj); // tidak diurutkan, mengikuti urutan asli dari objek
                        kunciTerurut.forEach((key, index) => {
                            const abjad = String.fromCharCode(65 + index);
                            const value = opsiObj[key];
                            const checked = (jawabanTersimpan === key) ? 'checked' : '';
                            opsiHtml += `
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="jawaban_input"
                                    id="opsi_${soal.id}_${index}" value="${key}" ${checked}>
                                <label class="form-check-label" for="opsi_${soal.id}_${index}">
                                    <span>${abjad}.</span> ${value}
                                </label>
                            </div>`;
                        });
                    } else {
                        opsiHtml = `<p class="text-muted fst-italic">Tidak ada opsi jawaban.</p>`;
                    }
                }
                // jenis soal isian singkat
                else if (soal.jenis_soal === 'isian_singkat') {
                    opsiHtml = `<div class="fw-bold">Jawaban:</div>`;
                    opsiHtml += `<input type="text" class="form-control" id="jawaban_text" name="jawaban_input"
                placeholder="Ketik jawaban singkat..." value="${jawabanTersimpan}">`;
                }
                // jenis soal uraian
                else {
                    opsiHtml = `<div class="fw-bold">Jawaban:</div>`;
                    opsiHtml += `<textarea class="form-control" id="jawaban_area" name="jawaban_input" rows="3"
                placeholder="Ketik jawaban Anda...">${jawabanTersimpan}</textarea>`;
                }

                $('#opsiJawabanList').html(opsiHtml);
                $('#soalKe').text(`${nomor} dari ${soalList.length} Soal`);

                // kontrol tombol navigasi
                $('#btnPrev').toggleClass('disabled', nomor === 1);
                $('#btnNext').toggleClass('d-none', nomor === soalList.length);
                $('.btnFinish').toggleClass('d-none', nomor !== soalList.length);


                applyFontSize();
                currentNomor = nomor;

                // 🎯 Highlight tombol nomor soal aktif
                $('.btnNomorSoal').removeClass('bg-dark text-light');
                $(`.btnNomorSoal_${nomor}`).addClass('bg-dark text-light');
            }

            // ==========================================================
            // ⏳ TIMER COUNTDOWN
            // ==========================================================
            function startCountdown() {
                const serverNow = new Date(waktuSekarang.replace(' ', 'T')); // waktu server dari backend
                const startTime = new Date(waktuMulai.replace(' ', 'T'));
                const endTime = new Date(startTime.getTime() + alokasiWaktuPeserta * 60000);

                // Simpan offset antara waktu lokal & waktu server (agar akurat)
                const localNow = Date.now();
                const serverOffset = localNow - serverNow.getTime();

                // Jalankan interval
                countdownInterval = setInterval(() => {
                    // Hitung waktu server real-time saat ini (berdasarkan offset)
                    const currentServerTime = new Date(Date.now() - serverOffset);
                    const remainingMs = endTime - currentServerTime;

                    if (remainingMs <= 0) {
                        clearInterval(countdownInterval);
                        waktuHabis();
                        return;
                    }

                    const hours = Math.floor((remainingMs / 1000 / 60 / 60) % 24);
                    const minutes = Math.floor((remainingMs / 1000 / 60) % 60);
                    const seconds = Math.floor((remainingMs / 1000) % 60);

                    // tampilkan ke tombol
                    $('#waktuUjian').text(
                        `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`
                    );

                    // jika sisa waktu <= 5 menit ubah warna jadi merah
                    if (remainingMs <= 5 * 60 * 1000) {
                        $('#waktuUjian').removeClass('btn-warning').addClass('btn-danger');
                    } else {
                        $('#waktuUjian').removeClass('btn-danger').addClass('btn-warning');
                    }

                }, 1000);
            }


            function waktuHabis() {
                sessionStorage.removeItem(soalStorageKey);

                $('#waktuUjian').text('Waktu Habis');
                disableUjian();
                kirimJawabanKeServer();
                akhiriUjian(true); // langsung akhiri tanpa konfirmasi
                setTimeout(() => {
                    $('.btnFinish').removeClass('d-none').removeClass('disabled').attr('disabled', false);
                    $('#opsiJawabanList').html(``);
                    $('#soalList').html(`<div class="text-center h3 p-4">Waktu Habis.!!</div>`);
                }, 1500);
            }

            function waktuHabis() {
                sessionStorage.removeItem(soalStorageKey);

                disableUjian();
                $('#waktuUjian').text('Waktu Habis');
                kirimJawabanKeServer();

                setTimeout(() => {
                    $('#soalList').html(`<div class="text-center h3 p-4">Waktu Habis.!!</div>`);
                    $('#opsiJawabanList').empty();
                    $('.btnFinish').removeClass('d-none disabled').prop('disabled', false);
                    akhiriUjian(true);
                }, 1000);
            }

            function disableUjian() {
                $('#btnNext, #btnPrev').addClass('disabled').attr('disabled', true);
                $('#opsiJawabanList input, #opsiJawabanList textarea').attr('disabled', true);
            }

            // ==========================================================
            // 🚪 AKHIRI UJIAN
            // ==========================================================
            function akhiriUjian(auto = false) {
                if (auto) {
                    $.post("{{ url('ujian/akhiri-ujian') }}", {
                        _token: "{{ csrf_token() }}",
                        status_ujian_id: statusUjianId
                    }).done(res => {
                        // if (res.status) window.location.href = "{{ url('home') }}";
                        if (res.status) {
                            Swal.fire({
                                title: "Waktu Habis.!!",
                                icon: "warning",
                                showCancelButton: false,
                                confirmButtonColor: "#3085d6",
                                cancelButtonColor: "#d33",
                                confirmButtonText: "OK"
                            }).then(result => {
                                if (result.isConfirmed) {
                                    window.location.href = "{{ url('home') }}";
                                }
                            });
                        }
                    });
                    return;
                }

                Swal.fire({
                    title: "Ujian selesai!",
                    text: "Yakin ingin mengakhiri ujian sekarang?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Ya, akhiri sekarang!"
                }).then(result => {
                    if (result.isConfirmed) {
                        kirimJawabanKeServer();
                        $.post("{{ url('ujian/akhiri-ujian') }}", {
                            _token: "{{ csrf_token() }}",
                            status_ujian_id: statusUjianId
                        }).done(res => {
                            if (res.status) {
                                console.log("✅ Ujian berhasil diakhiri:", res);
                                window.location.href = "{{ url('home') }}";
                            }
                        });
                    }
                });
            }

            // ==========================================================
            // 🔠 KONTROL UKURAN HURUF
            // ==========================================================
            function applyFontSize() {
                $('#soalList, #opsiJawabanList').css('font-size', `${fontSize}px`);
            }

            // ==========================================================
            // ⚙️ EVENT HANDLER
            // ==========================================================
            $(document).ready(function() {
                startCountdown();
                ambilSoalDariServer();
                applyFontSize();
                mulaiAutoSave(1); // auto save tiap 1 menit

                function debounce(fn, delay) {
                    let timeout;
                    return function(...args) {
                        clearTimeout(timeout);
                        timeout = setTimeout(() => fn.apply(this, args), delay);
                    };
                }

                // simpan jawaban tiap kali ada perubahan
                $(document).on('change', '[name="jawaban_input"]', debounce(function() {
                    const soalList = JSON.parse(sessionStorage.getItem(soalStorageKey));
                    if (!soalList) return;
                    const soal = soalList[currentNomor - 1];
                    if (soal) simpanJawaban(soal.id, $(this).val());

                    if (realtimeSave)
                        kirimJawabanKeServer();

                }, 500)); // simpan 0.5 detik setelah berhenti mengetik

                $('#btnNext').click(function() {
                    const soalList = JSON.parse(sessionStorage.getItem(soalStorageKey));
                    if (soalList && currentNomor < soalList.length) getSoal(currentNomor + 1);
                });

                $('#btnPrev').click(function() {
                    if (currentNomor > 1) getSoal(currentNomor - 1);
                });

                $('#btnZoomIn').click(function() {
                    fontSize += 1;
                    sessionStorage.setItem('fontSize', fontSize);
                    applyFontSize();
                });

                $('#btnZoomOut').click(function() {
                    if (fontSize > 10) {
                        fontSize -= 1;
                        sessionStorage.setItem('fontSize', fontSize);
                        applyFontSize();
                    }
                });

                $('#btnZoomReset').click(function() {
                    fontSize = 16;
                    sessionStorage.setItem('fontSize', fontSize);
                    applyFontSize();
                });

                $('.btnFinish').click(function() {
                    kirimJawabanKeServer();
                    akhiriUjian();
                });
            });

            // ==========================================================
            // 🧩 FUNGSI TAMPILKAN DAFTAR SOAL
            // ==========================================================
            function tampilkanDaftarSoal(showModal = true) {
                const soalData = sessionStorage.getItem(soalStorageKey);
                if (!soalData) {
                    $('.daftarSoalContainer').html(`<div class="text-center text-muted w-100">Soal belum dimuat.</div>`);
                    return;
                }

                const soalList = JSON.parse(soalData);
                if (!Array.isArray(soalList) || soalList.length === 0) {
                    $('.daftarSoalContainer').html(`<div class="text-center text-muted w-100">Soal belum tersedia.</div>`);
                    return;
                }

                const jawabanLocal = JSON.parse(localStorage.getItem(jawabanStorageKey)) || {};
                let html = '';

                soalList.forEach((soal, index) => {
                    const nomor = index + 1;
                    const sudahDijawab = !!jawabanLocal[soal.id];
                    const btnClass = sudahDijawab ? 'btn-success' : 'btn-outline-primary';
                    html += `
                        <button class="btn btn-md ${btnClass} btnNomorSoal btnNomorSoal_${nomor}" 
                                data-nomor="${nomor}" style="width: 45px; height: 45px;">
                            ${nomor}
                        </button>`;
                });

                $('.daftarSoalContainer').html(html);

                if (showModal) {
                    $('#modalSoalList').modal('show');
                }
            }

            // ==========================================================
            // 🎯 EVENT KLIK: BUKA MODAL DAFTAR SOAL
            // ==========================================================
            $('#btnSoalList').click(function() {
                tampilkanDaftarSoal();
            });

            // ==========================================================
            // 🎯 EVENT KLIK: PILIH SOAL DARI MODAL
            // ==========================================================
            $(document).on('click', '.btnNomorSoal', function() {
                const nomor = parseInt($(this).data('nomor'));
                $('#modalSoalList').modal('hide');
                getSoal(nomor);
            });
        </script>

        @include('client.idle')
    @endsection
