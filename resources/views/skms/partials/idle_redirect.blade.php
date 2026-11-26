<script>
    /* ── Auto-Idle Kiosk: Arahkan ke Halaman Idle Setelah 3 Menit Tanpa Aktivitas ── */
    (function () {
        const IDLE_TIMEOUT_MS = 180000; // 3 menit
        const IDLE_URL = @json(route('skms.idle'));
        let idleTimer = null;

        function resetIdleTimer() {
            if (idleTimer) clearTimeout(idleTimer);
            idleTimer = setTimeout(function () {
                window.location.href = IDLE_URL;
            }, IDLE_TIMEOUT_MS);
        }

        ['mousemove', 'mousedown', 'keydown', 'touchstart', 'touchmove', 'scroll', 'click', 'pointerdown'].forEach(function (evt) {
            document.addEventListener(evt, resetIdleTimer, { passive: true });
        });

        resetIdleTimer();
    })();
</script>
