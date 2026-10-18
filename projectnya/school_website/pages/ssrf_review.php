<?php
require_once '../includes/config.php';
require_once '../includes/header.php';

$mock_targets = [
    'health' => '{"status":"ok","service":"mock-internal","network":"isolated-lab"}',
    'training-note' => 'Synthetic lab note - no real secrets or internal data.',
    'metrics' => '{"requests":12,"findings":3,"environment":"training-only"}'
];
$selected_target = $_POST['mock_target'] ?? '';
$mock_response = $mock_targets[$selected_target] ?? '';
?>

<main>
    <section class="lab-hero">
        <div class="container">
            <div class="lab-kicker mb-3">A10 / ARCHITECTURE REVIEW</div>
            <div class="lab-status mb-4">ALLOWLISTED MOCK SERVICE ONLY</div>
            <h1 class="display-5 mb-3">SSRF Review</h1>
            <p class="lead mb-0">Determine whether a URL is fetched by the server or merely rendered by the browser. This track documents the trust boundary without probing internal networks.</p>
        </div>
    </section>
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-7">
                    <article class="card lab-card h-100 p-4">
                        <div class="lab-index mb-2">01 / DECISION TREE</div>
                        <h2 class="h4">Is it actually SSRF?</h2>
                        <ol class="text-muted">
                            <li>Find a feature that accepts a URL.</li>
                            <li>Trace whether PHP fetches it or the browser does.</li>
                            <li>Identify redirect, DNS, scheme, and host validation.</li>
                            <li>Document the result without contacting private services.</li>
                        </ol>
                    </article>
                </div>
                <div class="col-lg-5">
                    <article class="card h-100 p-4">
                        <div class="lab-index mb-2">02 / SAFE SIMULATOR</div>
                        <h2 class="h4">Mock internal service</h2>
                        <p class="text-muted">Pilih resource sintetis di bawah. Simulator hanya mengambil nilai dari allowlist lokal; tidak menerima URL bebas dan tidak melakukan request jaringan.</p>
                        <form method="post" class="mt-3">
                            <label for="mock_target" class="form-label">Mock target</label>
                            <select class="form-select mb-3" id="mock_target" name="mock_target">
                                <option value="">Choose a synthetic resource</option>
                                <option value="health">/health</option>
                                <option value="training-note">/training-note</option>
                                <option value="metrics">/metrics</option>
                            </select>
                            <button type="submit" class="btn btn-primary">Run Safe Simulation</button>
                        </form>
                        <?php if ($mock_response !== ''): ?>
                            <pre class="bg-dark text-light p-3 rounded small mt-3 mb-0"><?php echo htmlspecialchars($mock_response, ENT_QUOTES, 'UTF-8'); ?></pre>
                        <?php endif; ?>
                    </article>
                </div>
            </div>
            <article class="card mt-4 p-4">
                <div class="lab-index mb-2">03 / REMEDIATE</div>
                <h2 class="h4">If server-side fetching is added later</h2>
                <div class="row text-muted">
                    <div class="col-md-6"><p>Allowlist schemes and approved hosts.</p><p>Validate DNS results and block private/link-local ranges.</p></div>
                    <div class="col-md-6"><p>Control redirects, timeouts, and response size.</p><p>Never test cloud metadata or internal services in this lab.</p></div>
                </div>
            </article>
        </div>
    </section>
</main>

<?php require_once '../includes/footer.php'; ?>
