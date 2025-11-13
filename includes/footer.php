    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-6 mt-12">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p class="text-sm">พัฒนาระบบโดย กลุ่มบริหารกิจการนักเรียน <?php echo SCHOOL_NAME; ?></p>
        </div>
    </footer>

    <script>
        // Update date and time
        function updateDateTime() {
            const now = new Date();
            const dateOptions = { year: 'numeric', month: 'long', day: 'numeric' };
            const timeOptions = { hour: '2-digit', minute: '2-digit', second: '2-digit' };

            const dateEl = document.getElementById('current-date');
            const timeEl = document.getElementById('current-time');

            if (dateEl) {
                dateEl.textContent = now.toLocaleDateString('th-TH', dateOptions);
            }
            if (timeEl) {
                timeEl.textContent = now.toLocaleTimeString('th-TH', timeOptions);
            }
        }

        updateDateTime();
        setInterval(updateDateTime, 1000);

        // Toast notification
        function showToast(message, type = 'success') {
            const toast = document.createElement('div');
            toast.className = `toast ${type}`;
            toast.textContent = message;
            document.body.appendChild(toast);

            setTimeout(() => {
                toast.remove();
            }, 3000);
        }

        // Set today's date as default for date inputs
        document.addEventListener('DOMContentLoaded', () => {
            const today = new Date().toISOString().split('T')[0];
            document.querySelectorAll('input[type="date"]:not([value])').forEach(input => {
                input.value = today;
            });
        });
    </script>
</body>
</html>
