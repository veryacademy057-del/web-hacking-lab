<?php require_once '../includes/config.php'; ?>

<?php
if (!isset($_GET['id'])) {
    redirect(BASE_URL . 'pages/news.php');
}

 $id = $_GET['id'];
 $sql = "SELECT * FROM news WHERE id = $id";
 $result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 0) {
    redirect(BASE_URL . 'pages/news.php');
}

 $news = mysqli_fetch_assoc($result);
?>

<?php require_once '../includes/header.php'; ?>

<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <article>
                    <img src="<?php echo BASE_URL . $news['image']; ?>" class="img-fluid rounded mb-4" alt="<?php echo $news['title']; ?>">
                    <h1 class="fw-bold"><?php echo $news['title']; ?></h1>
                    <p class="text-muted mb-4"><i class="far fa-calendar me-2"></i><?php echo date('d F Y', strtotime($news['created_at'])); ?></p>
                    <div class="content">
                        <?php echo nl2br($news['content']); ?>
                    </div>
                    <hr>
                    <a href="<?php echo BASE_URL; ?>pages/news.php" class="btn btn-outline-primary"><i class="fas fa-arrow-left me-2"></i>Kembali ke Berita</a>
                </article>
            </div>
        </div>
    </div>
</section>

<?php require_once '../includes/footer.php'; ?>