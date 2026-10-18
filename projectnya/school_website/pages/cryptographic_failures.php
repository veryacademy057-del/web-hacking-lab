<?php
require_once '../includes/config.php';
require_once '../includes/header.php';

$data_checks = [
    'storage' => 'Review whether sensitive files are stored outside the public web root.',
    'transport' => 'Review whether HTTPS and secure cookie attributes are enabled in deployment.',
    'retention' => 'Review retention and deletion rules for assessment artifacts.'
];
$selected_data_check = $_POST['data_check'] ?? '';
$data_result = $data_checks[$selected_data_check] ?? '';
?>

<main>
    <section class="lab-hero">
        <div class="container">
            <div class="lab-kicker mb-3">A02 / DATA PROTECTION REVIEW</div>
            <div class="lab-status mb-4">SYNTHETIC DATA ONLY</div>
            <h1 class="display-5 mb-3">Cryptographic Failures</h1>
            <p class="lead mb-0">Trace where sensitive data is stored, transported, and served, then document whether confidentiality and integrity controls are present.</p>
        </div>
    </section>
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-6">
                    <article class="card lab-card h-100 p-4">
                        <div class="lab-index mb-2">01 / DATA FLOW</div>
                        <h2 class="h4">Review the document path</h2>
                        <p class="text-muted">The assessment form stores uploaded documents under a web-accessible directory. Use a harmless synthetic file and verify whether the file URL can be requested directly.</p>
                        <form method="post" class="mt-3">
                            <label for="data_check" class="form-label">Select a data-protection check</label>
                            <select class="form-select mb-3" id="data_check" name="data_check">
                                <option value="">Choose a review item</option>
                                <?php foreach ($data_checks as $id => $label): ?>
                                    <option value="<?php echo $id; ?>"><?php echo htmlspecialchars(ucfirst($id), ENT_QUOTES, 'UTF-8'); ?></option>
                                <?php endforeach; ?>
                            </select>
                            <button type="submit" class="btn btn-outline-warning">Run Data Review</button>
                        </form>
                        <?php if ($data_result !== ''): ?>
                            <div class="alert alert-warning mt-3 mb-0"><?php echo htmlspecialchars($data_result, ENT_QUOTES, 'UTF-8'); ?></div>
                        <?php endif; ?>
                    </article>
                </div>
                <div class="col-lg-6">
                    <article class="card lab-card h-100 p-4">
                        <div class="lab-index mb-2">02 / EVIDENCE</div>
                        <h2 class="h4">Capture a safe finding</h2>
                        <p class="text-muted">Record storage location, access control, transport protection, retention, and whether real personal data was excluded from the exercise.</p>
                        <pre class="bg-dark text-light p-3 rounded small">Data: synthetic only
Storage: inspect
Direct URL: verify
Encryption: document</pre>
                    </article>
                </div>
            </div>
            <article class="card mt-4 p-4">
                <div class="lab-index mb-2">03 / REMEDIATE</div>
                <h2 class="h4">Remediation checklist</h2>
                <div class="row text-muted">
                    <div class="col-md-6"><p>Store sensitive files outside the public web root.</p><p>Serve downloads through an authorization-checked handler.</p></div>
                    <div class="col-md-6"><p>Use TLS and secure cookie settings in deployment.</p><p>Define retention and deletion rules for assessment artifacts.</p></div>
                </div>
            </article>
        </div>
    </section>
</main>

<?php require_once '../includes/footer.php'; ?>
