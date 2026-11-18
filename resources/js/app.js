import './bootstrap';
import 'bootstrap';
import $ from 'jquery';

window.$ = window.jQuery = $;

// jQuery Ajax untuk pengecekan sisa kursi secara real-time
$(function () {
    const $courseSelect = $('#course_id');
    const $availabilityArea = $('#availability');

    if ($courseSelect.length && $availabilityArea.length) {
        $courseSelect.on('change', function () {
            const courseId = $(this).val();

            if (!courseId) {
                $availabilityArea.html('<div class="alert alert-info py-2 px-3 mb-0 text-sm">Silakan pilih kursus untuk melihat sisa ketersediaan kursi.</div>');
                return;
            }

            // Tampilkan status memuat
            $availabilityArea.html('<div class="text-muted text-sm py-1"><span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Memeriksa ketersediaan kursi...</div>');

            // Kirim GET request menggunakan $.ajax() dengan dataType: 'json'
            $.ajax({
                url: `/courses/${courseId}/availability`,
                method: 'GET',
                dataType: 'json',
                success: function (data) {
                    if (data && data.remaining !== undefined) {
                        const badgeClass = data.remaining > 0 ? 'bg-success' : 'bg-danger';
                        $availabilityArea.html(`
                            <div class="alert alert-light border py-2 px-3 mb-0 d-flex justify-content-between align-items-center">
                                <span>Kursus: <strong>${data.name}</strong></span>
                                <span class="badge ${badgeClass} text-white">${data.remaining} kursi tersisa</span>
                            </div>
                        `);
                    } else {
                        $availabilityArea.html('<div class="alert alert-warning py-2 px-3 mb-0 text-sm">Informasi kuota kursus tidak ditemukan.</div>');
                    }
                },
                error: function () {
                    $availabilityArea.html('<div class="alert alert-danger py-2 px-3 mb-0 text-sm">Gagal memuat ketersediaan kursi. Silakan coba beberapa saat lagi.</div>');
                }
            });
        });

        // Tampilkan status awal saat halaman dimuat
        if ($courseSelect.val()) {
            $courseSelect.trigger('change');
        } else {
            $availabilityArea.html('<div class="alert alert-info py-2 px-3 mb-0 text-sm">Silakan pilih kursus untuk melihat sisa ketersediaan kursi.</div>');
        }
    }
});
