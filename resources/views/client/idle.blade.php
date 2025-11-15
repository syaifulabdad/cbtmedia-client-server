<script>
    // ===========================
    // 🔒 DETEKSI IDLE & VISIBILITY + COUNTER
    // ===========================

    // Konfigurasi waktu (dalam milidetik)
    const waktuIdleLimit = (2 * 60 * 1000); // contoh: 2 menit
    const waktuHiddenLimit = (10 * 1000); // contoh: 10 detik tab tersembunyi
    const batasPeringatanHidden = 3; // berapa kali tab disembunyikan sebelum peringatan

    let idleTimer = null;
    let hiddenTimer = null;
    let isIdle = false;
    let isHiddenTooLong = false;

    // Fungsi: reset timer idle setiap ada aktivitas
    function resetIdleTimer() {
        clearTimeout(idleTimer);
        if (isIdle) {
            console.log('🟢 Pengguna aktif kembali.');
            isIdle = false;
            $('#statusUjian').text('Aktif').removeClass('bg-danger').addClass('bg-success');
        }
        idleTimer = setTimeout(() => {
            isIdle = true;
            console.warn('⚠️ Pengguna idle (tidak ada aktivitas).');
            $('#statusUjian').text('Idle').removeClass('bg-success').addClass('bg-warning');
            // kirimStatus('idle'); // opsional
        }, waktuIdleLimit);
    }

    // Fungsi: ambil jumlah tabHidden dari localStorage
    function getHiddenCount() {
        return parseInt(localStorage.getItem('tabHiddenCount') || '0', 10);
    }

    // Fungsi: simpan jumlah tabHidden ke localStorage
    function setHiddenCount(count) {
        localStorage.setItem('tabHiddenCount', count);
    }

    // Fungsi: kirim status ke server (misal untuk log peringatan)
    function kirimStatus(status) {
        $.ajax({
            url: '/ujian/log-status', // ganti sesuai endpoint kamu
            type: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                status_peserta_ujian_id: statusPesertaUjianId,
                status: status,
                idle: isIdle,
                hidden: isHiddenTooLong,
            },
            success: (res) => console.log('📡 Status dikirim:', res),
            error: (err) => console.error('🚫 Gagal kirim status:', err)
        });
    }

    // Fungsi: deteksi perubahan visibilitas tab
    function handleVisibilityChange() {
        if (document.hidden) {
            console.warn('🔕 Tab ujian disembunyikan.');
            $('#statusUjian').text('Tersembunyi').removeClass('bg-success bg-warning').addClass('bg-danger');

            clearTimeout(hiddenTimer);
            hiddenTimer = setTimeout(() => {
                isHiddenTooLong = true;

                // Tambah counter tabHidden
                let hiddenCount = getHiddenCount() + 1;
                setHiddenCount(hiddenCount);

                console.error(`🚫 Tab disembunyikan terlalu lama! (${hiddenCount}x)`);

                // Update tampilan
                $('#statusUjian').text(`Disembunyikan terlalu lama! (${hiddenCount}x)`)
                    .removeClass('bg-success bg-warning').addClass('bg-danger');

                // Kirim log ke server
                kirimStatus('hidden-too-long');

                // Jika sudah 3x, beri peringatan kuat
                if (hiddenCount >= batasPeringatanHidden) {
                    Swal.fire({
                        icon: "warning",
                        title: "Oops...",
                        text: "Anda terlalu sering membuka aplikasi lain.! Harap fokus pada ujian atau sistem akan menghentikan ujian anda.!!",
                    });
                    // kirimStatus('peringatan-tab-berulang');
                }

                if (hiddenCount >= (batasPeringatanHidden * 2)) {
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "Anda terlalu sering membuka aplikasi lain.! ujian anda dihentikan sistem.!!",
                    });
                    // kirimStatus('peringatan-tab-berulang');
                }
            }, waktuHiddenLimit);
        } else {
            console.log('🟢 Tab ujian terlihat kembali.');
            $('#statusUjian').text('Aktif').removeClass('bg-danger').addClass('bg-success');
            clearTimeout(hiddenTimer);
            isHiddenTooLong = false;
        }
    }

    // Inisialisasi event listener
    function initIdleAndVisibilityWatcher() {
        // aktivitas yang dianggap “aktif”
        ['mousemove', 'keydown', 'mousedown', 'scroll', 'touchstart'].forEach(evt => {
            document.addEventListener(evt, resetIdleTimer, true);
        });

        // deteksi perubahan tab
        document.addEventListener('visibilitychange', handleVisibilityChange);

        // mulai timer awal
        resetIdleTimer();

        // reset counter jika reload halaman
        if (!localStorage.getItem('tabHiddenCount')) {
            setHiddenCount(0);
        }

        console.log('✅ Deteksi idle & visibilitas diaktifkan.');
    }

    // panggil saat halaman siap
    $(document).ready(function() {
        initIdleAndVisibilityWatcher();
    });
</script>
