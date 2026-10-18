<?php
require_once '../includes/config.php';
require_once '../includes/header.php';

$component_checks = [
    'php' => 'Record the PHP runtime version and compare it with supported releases.',
    'bootstrap' => 'Record the Bootstrap CDN version and verify its integrity and advisory status.',
    'fontawesome' => 'Record the Font Awesome CDN version and remove it if unused.'
];
$selected_component = $_POST['component_id'] ?? '';
$component_result = $component_checks[$selected_component] ?? '';
?>

<main>
    <section class="lab-hero">
        <div class="container">
            <div class="lab-kicker mb-3">A06 / DEPENDENCY INVENTORY</div>
            <div class="lab-status mb-4">REVIEW MODE</div>
            <h1 class="display-5 mb-3">Vulnerable Components</h1>
            <p class="lead mb-0">Build a component inventory, compare versions with official advisories, and decide whether a dependency is exposed, supported, and necessary.</p>
        </div>
    </section>
    <section class="py-5 bg-light">
        <div class="container">
            <article class="card lab-card p-4">
                <div class="lab-index mb-2">01 / INVENTORY</div>
                <h2 class="h4">Component register</h2>
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead><tr><th>Component</th><th>Evidence source</th><th>Review action</th></tr></thead>
                        <tbody>
                            <tr><td>PHP runtime</td><td>Laragon / CLI version</td><td>Compare with supported releases</td></tr>
                            <tr><td>MySQL or MariaDB</td><td>Database server version</td><td>Review vendor advisories</td></tr>
                            <tr><td>Bootstrap</td><td>CDN link in shared header</td><td>Pin and monitor version</td></tr>
                            <tr><td>Font Awesome</td><td>CDN link in shared header</td><td>Remove unused assets</td></tr>
                        </tbody>
                    </table>
                </div>
            </article>
            <div class="row g-4 mt-1">
                <div class="col-lg-6">
                    <article class="card h-100 p-4">
                        <div class="lab-index mb-2">02 / TRIAGE</div>
                        <h2 class="h4">Risk questions</h2>
                        <ul class="text-muted mb-0">
                            <li>Is the component supported?</li>
                            <li>Is the vulnerable feature enabled?</li>
                            <li>Can the dependency be removed?</li>
                            <li>Is the version pinned and reviewed?</li>
                        </ul>
                    </article>
                </div>
                <div class="col-lg-6">
                    <article class="card h-100 p-4">
                        <div class="lab-index mb-2">03 / REMEDIATE</div>
                        <h2 class="h4">Change safely</h2>
                        <p class="text-muted">Use official release notes, test in the local lab, record the before/after version, and verify that security controls and UI behavior still work.</p>
                        <form method="post">
                            <label for="component_id" class="form-label">Choose a component to review</label>
                            <select class="form-select mb-3" id="component_id" name="component_id">
                                <option value="">Choose a component</option>
                                <?php foreach ($component_checks as $id => $label): ?>
                                    <option value="<?php echo $id; ?>"><?php echo htmlspecialchars(ucfirst($id), ENT_QUOTES, 'UTF-8'); ?></option>
                                <?php endforeach; ?>
                            </select>
                            <button type="submit" class="btn btn-outline-warning">Run Component Review</button>
                        </form>
                        <?php if ($component_result !== ''): ?>
                            <div class="alert alert-warning mt-3 mb-0"><?php echo htmlspecialchars($component_result, ENT_QUOTES, 'UTF-8'); ?></div>
                        <?php endif; ?>
                    </article>
                </div>
            </div>
        </div>
    </section>
</main>

<?php require_once '../includes/footer.php'; ?>
