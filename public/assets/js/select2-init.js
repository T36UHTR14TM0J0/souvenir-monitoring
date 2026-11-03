/**
 * Global Select2 Initialization & Synchronization
 * Menggunakan theme Bootstrap-5 agar 100% konsisten dengan desain form aplikasi
 */
(function ($) {
    'use strict';

    if (!$) return;

    function initSelect2(context) {
        var $root = context ? $(context) : $(document);
        var $selects = $root.find('select:not(.no-select2)');

        $selects.each(function () {
            var $select = $(this);

            // Jangan inisialisasi ulang jika sudah Select2
            if ($select.hasClass('select2-hidden-accessible')) {
                return;
            }

            var placeholderText = $select.attr('placeholder') || 
                                  $select.data('placeholder') || 
                                  $select.find('option[value=""]').first().text().trim() || 
                                  '';

            // Modal parent jika ada di dalam modal bootstrap
            var $modal = $select.closest('.modal');
            var dropdownParent = $modal.length ? $modal : undefined;

            $select.select2({
                theme: 'bootstrap-5',
                width: $select.data('width') ? $select.data('width') : '100%',
                dropdownParent: dropdownParent,
                placeholder: placeholderText ? { id: '', text: placeholderText } : undefined,
                allowClear: false,
                language: {
                    noResults: function () {
                        return 'Tidak ada data ditemukan';
                    },
                    searching: function () {
                        return 'Mencari...';
                    }
                }
            });

            // Sinkronisasi event change ke native event agar listener vanilla JS tetap berjalan
            $select.on('select2:select select2:unselect select2:clear', function () {
                var evt = document.createEvent('HTMLEvents');
                evt.initEvent('change', true, false);
                this.dispatchEvent(evt);
            });

            // Sinkronkan class is-invalid jika select memiliki error validasi
            if ($select.hasClass('is-invalid')) {
                $select.next('.select2-container').addClass('is-invalid');
            }

            // Saat select berubah, jika status is-invalid hilang di native select, bersihkan juga di select2
            $select.on('change', function () {
                if (!$select.hasClass('is-invalid')) {
                    $select.next('.select2-container').removeClass('is-invalid');
                }
            });
        });
    }

    // Inisialisasi saat DOM siap
    $(document).ready(function () {
        initSelect2();
    });

    // Inisialisasi otomatis setelah request AJAX selesai
    $(document).ajaxComplete(function () {
        initSelect2();
    });

    // Expose ke global window
    window.initSelect2 = initSelect2;

})(window.jQuery);
