<!-- Footer Section -->
            <footer class="mt-5 text-center">
                <div class="card">
                    <div class="card-body">
                        <p class="mb-1">© 2024 Career Portal Admin Panel</p>
                        <p class="text-muted mb-0">Designed with <i class="fas fa-heart text-danger"></i> for better recruitment</p>
                    </div>
                </div>
            </footer>
        </main>
    </div>
    
    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.8/umd/popper.min.js"></script>
    
    <!-- Custom Admin Scripts -->
    <script>
    $(document).ready(function() {
        // Toggle sidebar on mobile
        $('.toggle-sidebar').click(function() {
            $('.sidebar').toggleClass('active');
        });
        
        // Close sidebar when clicking outside on mobile
        $(document).on('click', function(e) {
            if ($(window).width() < 768) {
                if (!$(e.target).closest('.sidebar').length && !$(e.target).closest('.toggle-sidebar').length) {
                    $('.sidebar').removeClass('active');
                }
            }
        });
    });
    </script>
</body>
</html>