<?php require_once '../includes/config.php'; ?>
<?php require_once '../includes/header.php'; ?>

<section class="py-5">
    <div class="container">
        <h2 class="section-title text-center">Penetration Testing Methodology</h2>
        <p class="text-center text-muted mx-auto" style="max-width: 760px;">Gunakan capability lab untuk memahami discovery, validasi, evidence, reporting, remediation, dan retest pada aplikasi web.</p>
        <div class="row g-4 mt-4">
            <?php
            $sql = "SELECT * FROM programs ORDER BY name ASC";
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
            ?>
            <div class="col-md-4">
                <div class="card h-100 p-4">
                    <div class="mb-3"><i class="fas <?php echo $row['icon']; ?> fa-3x text-primary"></i></div>
                    <h4 class="card-title"><?php echo $row['name']; ?></h4>
                    <p class="card-text text-muted"><?php echo $row['description']; ?></p>
                </div>
            </div>
            <?php
                }
            } else {
                echo "<p class='text-center col-12'>Belum ada capability tersedia.</p>";
            }
            ?>
        </div>
    </div>
</section>

<?php require_once '../includes/footer.php'; ?>