<?php
require_once '../includes/config.php';
require_once '../includes/header.php';

$commands = [
    'whoami' => "lab-user",
    'hostname' => "redteam-target",
    'id' => "uid=1000(lab-user) gid=1000(lab-user) groups=1000(lab-user)",
    'uname' => "Linux redteam-target 6.x training-kernel x86_64 GNU/Linux"
];
$selected_command = $_POST['command_id'] ?? '';
$command_output = $commands[$selected_command] ?? '';
?>

<main>
    <section class="lab-hero">
        <div class="container">
            <div class="lab-kicker mb-3">RCE / EXECUTION BOUNDARY</div>
            <div class="lab-status mb-4">SIMULATION MODE - NO OS COMMANDS</div>
            <h1 class="display-5 mb-3">RCE Simulator</h1>
            <p class="lead mb-0">Pelajari cara mendokumentasikan indikasi command execution tanpa menjalankan input user sebagai command pada Ubuntu.</p>
        </div>
    </section>
    <section class="py-5 bg-light">
        <div class="container">
            <div class="alert alert-danger">Ini bukan RCE nyata. Aplikasi tidak memanggil shell, tidak menjalankan <code>system()</code>, <code>exec()</code>, atau fungsi OS lainnya.</div>
            <div class="row g-4">
                <div class="col-lg-7">
                    <article class="card lab-card h-100 p-4">
                        <div class="lab-index mb-2">01 / SAFE EXERCISE</div>
                        <h2 class="h4">Select a synthetic command</h2>
                        <p class="text-muted">Pilih command yang sudah ditentukan. Output diambil dari map statis untuk latihan evidence dan reporting.</p>
                        <form method="post">
                            <label for="command_id" class="form-label">Command marker</label>
                            <select class="form-select mb-3" id="command_id" name="command_id">
                                <option value="">Choose a command marker</option>
                                <?php foreach ($commands as $command => $output): ?>
                                    <option value="<?php echo $command; ?>"><?php echo htmlspecialchars($command, ENT_QUOTES, 'UTF-8'); ?></option>
                                <?php endforeach; ?>
                            </select>
                            <button type="submit" class="btn btn-primary">Run Simulation</button>
                        </form>
                        <?php if ($command_output !== ''): ?>
                            <pre class="bg-dark text-light p-3 rounded mt-3 mb-0"><code><?php echo htmlspecialchars($command_output, ENT_QUOTES, 'UTF-8'); ?></code></pre>
                        <?php endif; ?>
                    </article>
                </div>
                <div class="col-lg-5">
                    <article class="card h-100 p-4">
                        <div class="lab-index mb-2">02 / REVIEW</div>
                        <h2 class="h4">What a real finding would require</h2>
                        <ul class="text-muted mb-0">
                            <li>Untrusted input reaches an execution sink.</li>
                            <li>Execution context and OS identity are proven.</li>
                            <li>Impact is documented without destructive actions.</li>
                            <li>Remediation removes the execution path.</li>
                        </ul>
                    </article>
                </div>
            </div>
        </div>
    </section>
</main>

<?php require_once '../includes/footer.php'; ?>
