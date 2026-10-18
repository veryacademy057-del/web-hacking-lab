<?php require_once '../includes/config.php'; ?>
<?php require_once '../includes/header.php'; ?>

<section class="py-5">
    <div class="container">
        <h2 class="section-title text-center">Case Files</h2>
        <p class="text-center text-muted mx-auto" style="max-width: 760px;">Berikut beberapa contoh proyek fiktif yang menggambarkan jenis skenario kerja yang biasa kami lakukan untuk klien dari sektor keuangan, retail, dan teknologi.</p>
        <div class="row g-4 mt-4">
            <?php
            $sql = "SELECT * FROM gallery ORDER BY created_at DESC";
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
            ?>
            <div class="col-md-4">
                <div class="card h-100">
                    <img src="<?php echo $row['image']; ?>" class="card-img-top" alt="<?php echo $row['title']; ?>" style="height: 220px; object-fit: cover;">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $row['title']; ?></h5>
                        <p class="card-text text-muted"><?php echo $row['description']; ?></p>
                    </div>
                </div>
            </div>
            <?php
                }
            } else {
                echo "<p class='text-center col-12'>Belum ada portofolio.</p>";
            }
            ?>
        </div>
    </div>
</section>

<?php require_once '../includes/footer.php'; ?>