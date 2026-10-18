<!-- includes/footer.php -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5 class="mb-3"><?php echo getSetting('school_name'); ?></h5>
                    <p><?php echo getSetting('about_description'); ?></p>
                    <div class="social-links mt-3">
                        <a href="#" class="text-white me-3"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#" class="text-white me-3"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-white me-3"><i class="fab fa-github"></i></a>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <h5 class="mb-3">Security Operations</h5>
                    <p><i class="fas fa-map-marker-alt me-2"></i> <?php echo getSetting('school_address'); ?></p>
                    <p><i class="fas fa-phone me-2"></i> <?php echo getSetting('school_phone'); ?></p>
                    <p><i class="fas fa-envelope me-2"></i> <?php echo getSetting('school_email'); ?></p>
                </div>
                <div class="col-md-4 mb-4">
                    <h5 class="mb-3">Lab Rules</h5>
                    <p class="mb-2">Authorized targets only.</p>
                    <p class="mb-0">Local training environment, evidence-driven testing.</p>
                </div>
            </div>
            <hr class="bg-white">
            <div class="text-center py-3">
                <p class="mb-0">&copy; <?php echo date('Y'); ?> <?php echo getSetting('school_name'); ?>. All Rights Reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo BASE_URL; ?>assets/js/main.js"></script>
</body>
</html>