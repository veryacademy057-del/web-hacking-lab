<?php require_once '../includes/config.php'; ?>
<?php require_once '../includes/header.php'; ?>

<section class="py-5">
    <div class="container">
        <h2 class="section-title text-center">Insight & Artikel</h2>
        <p class="text-center text-muted mx-auto" style="max-width: 760px;">Kumpulan pemikiran, pembaruan industri, dan panduan praktis yang kami bagikan untuk membantu tim teknologi dan bisnis memahami lanskap keamanan siber saat ini.</p>
        <div class="row mt-4 g-4">
            <?php
            $sql = "SELECT * FROM news ORDER BY created_at DESC";
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
            ?>
            <div class="col-md-4">
                <div class="card h-100">
                    <img src="<?php echo $row['image']; ?>" class="card-img-top" alt="<?php echo $row['title']; ?>" style="height: 220px; object-fit: cover;">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title"><?php echo $row['title']; ?></h5>
                        <p class="card-text text-muted flex-grow-1"><?php echo substr($row['content'], 0, 150) . '...'; ?></p>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted"><?php echo date('d M Y', strtotime($row['created_at'])); ?></small>
                            <a href="<?php echo BASE_URL; ?>pages/news_detail.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-primary">Baca</a>
                        </div>
                    </div>
                </div>
            </div>
            <?php
                }
            } else {
                echo "<p class='text-center col-12'>Belum ada insight.</p>";
            }
            ?>
        </div>
    </div>
</section>

<?php require_once '../includes/footer.php'; ?>