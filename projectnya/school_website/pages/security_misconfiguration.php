<?php
require_once '../includes/config.php';
require_once '../includes/header.php';

$checks = [
    'debug' => ['title' => 'Debug output exposure', 'result' => 'Finding: display_errors should be disabled outside local development.'],
    'permissions' => ['title' => 'Upload directory permissions', 'result' => 'Finding: verify that upload directories are not writable by unintended users.'],
    'defaults' => ['title' => 'Default configuration review', 'result' => 'Finding: replace default credentials and remove unused services before deployment.']
];
$selected_check = $_POST['check_id'] ?? '';
$check_result = $checks[$selected_check] ?? null;
?>

<main>
    <section class="lab-hero">
        <div class="container">
            <div class="lab-kicker mb-3">A05 / CONFIGURATION REVIEW</div>
            <div class="lab-status mb-4">LOCAL CONFIGURATION TARGET</div>
            <h1 class="display-5 mb-3">Security Misconfiguration</h1>
            <p class="lead mb-0">Review debug exposure, default settings, file permissions, and environment boundaries without changing production systems.</p>
        </div>
    </section>
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-7">
                    <article class="card lab-card h-100 p-4">
                        <div class="lab-index mb-2">01 / OBSERVE</div>
                        <h2 class="h4">Local findings</h2>
                        <ul class="text-muted">
                            <li>PHP error display is enabled in the shared configuration.</li>
                            <li>Local database credentials use a blank root password.</li>
                            <li>Upload directories are created with permissive permissions.</li>
                            <li>Diagnostic files must not be web-readable in a deployed lab.</li>
                        </ul>
                        <form method="post" class="mt-3">
                            <label for="check_id" class="form-label">Select a local check</label>
                            <select class="form-select mb-3" id="check_id" name="check_id">
                                <option value="">Choose a configuration check</option>
                                <?php foreach ($checks as $id => $check): ?>
                                    <option value="<?php echo $id; ?>"><?php echo htmlspecialchars($check['title'], ENT_QUOTES, 'UTF-8'); ?></option>
                                <?php endforeach; ?>
                            </select>
                            <button type="submit" class="btn btn-outline-warning">Run Configuration Check</button>
                        </form>
                        <?php if ($check_result): ?>
                            <div class="alert alert-warning mt-3 mb-0"><?php echo htmlspecialchars($check_result['result'], ENT_QUOTES, 'UTF-8'); ?></div>
                        <?php endif; ?>
                    </article>
                </div>
                <div class="col-lg-5">
                    <article class="card h-100 p-4">
                        <div class="lab-index mb-2">02 / EVIDENCE</div>
                        <h2 class="h4">Record evidence</h2>
                        <p class="text-muted">Capture the route, configuration value, observed response, impact, and recommended production setting. Do not publish secrets or stack traces.</p>
                        <pre class="bg-dark text-light p-3 rounded small">Environment: localhost
Debug output: review
Upload perms: review
Production exposure: prohibited</pre>
                    </article>
                </div>
            </div>
            <article class="card mt-4 p-4">
                <div class="lab-index mb-2">03 / REMEDIATE</div>
                <h2 class="h4">Remediation checklist</h2>
                <div class="row text-muted">
                    <div class="col-md-6"><p>Disable display_errors outside local development.</p><p>Use a dedicated database user with least privilege.</p></div>
                    <div class="col-md-6"><p>Restrict upload and log directory permissions.</p><p>Keep secrets and debug artifacts outside the web root.</p></div>
                </div>
            </article>
        </div>
    </section>
</main>

<?php require_once '../includes/footer.php'; ?>
