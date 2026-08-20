<?php
declare(strict_types=1);

require_once __DIR__ . '/db_connect.php';

function caztech_process_escape(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function caztech_render_process_result(string $title, string $message, bool $success, int $status_code = 200): void
{
    http_response_code($status_code);
    $back_url = '../index.php#contact';
    $safe_title = caztech_process_escape($title);
    $safe_message = caztech_process_escape($message);
    $status_label = $success ? 'Message sent successfully' : 'Message could not be sent';
    $icon_class = $success ? 'success' : 'error';
    $countdown = 6;
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <meta name="theme-color" content="#071426">
      <title><?php echo $safe_title; ?> | CAZTech Solutions</title>
      <style>
        :root {
          color-scheme: dark;
          --page-bg: #071426;
          --card-bg: rgba(13, 35, 63, .92);
          --card-border: rgba(148, 163, 184, .24);
          --text: #f8fafc;
          --muted: #a8b6c9;
          --primary: #8db7e6;
          --primary-dark: #173759;
          --success: #5ee6a8;
          --error: #ff9c9c;
        }
        * { box-sizing: border-box; }
        body {
          margin: 0;
          min-height: 100vh;
          font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
          color: var(--text);
          background:
            radial-gradient(circle at 15% 10%, rgba(68, 126, 190, .25), transparent 34%),
            radial-gradient(circle at 90% 85%, rgba(45, 91, 142, .28), transparent 36%),
            var(--page-bg);
        }
        .result-page {
          min-height: 100vh;
          display: grid;
          place-items: center;
          padding: 24px;
        }
        .result-card {
          width: min(100%, 570px);
          padding: clamp(28px, 6vw, 58px);
          text-align: center;
          border: 1px solid var(--card-border);
          border-radius: 30px;
          background: linear-gradient(145deg, rgba(18, 48, 84, .96), var(--card-bg));
          box-shadow: 0 30px 90px rgba(0, 0, 0, .38), 0 0 0 1px rgba(255, 255, 255, .03) inset;
        }
        .brand {
          display: inline-flex;
          align-items: center;
          gap: 12px;
          color: var(--text);
          font-size: .8rem;
          font-weight: 800;
          letter-spacing: .12em;
          text-decoration: none;
          text-transform: uppercase;
        }
        .brand img {
          width: 54px;
          height: 54px;
          object-fit: contain;
          border-radius: 15px;
          background: rgba(255, 255, 255, .96);
          padding: 7px;
          box-shadow: 0 10px 24px rgba(0, 0, 0, .2);
        }
        .result-icon {
          width: 78px;
          height: 78px;
          display: grid;
          place-items: center;
          margin: 34px auto 22px;
          border-radius: 24px;
          border: 1px solid;
        }
        .result-icon.success {
          color: var(--success);
          border-color: rgba(94, 230, 168, .35);
          background: rgba(94, 230, 168, .12);
          box-shadow: 0 0 35px rgba(94, 230, 168, .12);
        }
        .result-icon.error {
          color: var(--error);
          border-color: rgba(255, 156, 156, .35);
          background: rgba(255, 156, 156, .12);
          box-shadow: 0 0 35px rgba(255, 156, 156, .12);
        }
        .result-icon svg { width: 38px; height: 38px; }
        .eyebrow {
          margin: 0;
          color: var(--primary);
          font-size: .72rem;
          font-weight: 800;
          letter-spacing: .18em;
          text-transform: uppercase;
        }
        h1 {
          margin: 12px 0 0;
          font-size: clamp(1.8rem, 5vw, 2.65rem);
          line-height: 1.08;
          letter-spacing: -.04em;
        }
        .message {
          max-width: 410px;
          margin: 18px auto 0;
          color: var(--muted);
          font-size: 1rem;
          line-height: 1.75;
        }
        .actions {
          display: flex;
          flex-wrap: wrap;
          justify-content: center;
          gap: 12px;
          margin-top: 30px;
        }
        .button {
          min-height: 46px;
          display: inline-flex;
          align-items: center;
          justify-content: center;
          gap: 9px;
          padding: 0 21px;
          border-radius: 13px;
          font-size: .9rem;
          font-weight: 800;
          text-decoration: none;
          transition: transform .2s ease, background .2s ease, border-color .2s ease;
        }
        .button:hover { transform: translateY(-2px); }
        .button:focus-visible { outline: 3px solid rgba(141, 183, 230, .55); outline-offset: 3px; }
        .button-primary { color: #081525; background: var(--primary); }
        .button-primary:hover { background: #b4d2f2; }
        .button-secondary { color: var(--text); border: 1px solid var(--card-border); background: rgba(255, 255, 255, .06); }
        .button-secondary:hover { background: rgba(255, 255, 255, .12); }
        .redirect-note {
          margin: 22px 0 0;
          color: #8194aa;
          font-size: .76rem;
        }
        .redirect-note strong { color: var(--primary); }
        @media (prefers-reduced-motion: reduce) {
          .button { transition: none; }
          .button:hover { transform: none; }
        }
      </style>
    </head>
    <body>
      <main class="result-page">
        <section class="result-card" aria-labelledby="result-title" aria-describedby="result-message">
          <a class="brand" href="../index.php" aria-label="Back to CAZTech homepage">
            <img src="../image/CAZTECH.png" alt="CAZTech logo">
            <span>CAZTech Solutions</span>
          </a>

          <div class="result-icon <?php echo $icon_class; ?>" aria-hidden="true">
            <?php if ($success): ?>
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
            <?php else: ?>
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 8v4m0 4h.01"/></svg>
            <?php endif; ?>
          </div>

          <p class="eyebrow"><?php echo caztech_process_escape($status_label); ?></p>
          <h1 id="result-title"><?php echo $safe_title; ?></h1>
          <p id="result-message" class="message"><?php echo $safe_message; ?></p>

          <div class="actions">
            <a class="button button-primary" href="<?php echo $back_url; ?>">Back to page <span aria-hidden="true">→</span></a>
            <a class="button button-secondary" href="../index.php">View homepage</a>
          </div>

          <p class="redirect-note" aria-live="polite">Returning to the contact section in <strong id="redirect-countdown"><?php echo $countdown; ?></strong> seconds.</p>
        </section>
      </main>
      <script>
        (() => {
          const target = <?php echo json_encode($back_url, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>;
          const countdown = document.getElementById('redirect-countdown');
          let seconds = <?php echo $countdown; ?>;
          const timer = window.setInterval(() => {
            seconds -= 1;
            if (countdown) countdown.textContent = String(seconds);
            if (seconds <= 0) {
              window.clearInterval(timer);
              window.location.href = target;
            }
          }, 1000);
        })();
      </script>
    </body>
    </html>
    <?php
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../index.php#contact');
    exit;
}

$name = trim((string) ($_POST['name'] ?? ''));
$business = trim((string) ($_POST['business'] ?? ''));
$project = trim((string) ($_POST['project'] ?? ''));
$rating = max(1, min(5, (int) ($_POST['rating'] ?? 5)));
$form_type = trim((string) ($_POST['form_type'] ?? 'contact'));

if ($form_type === 'review') {
    if ($name === '' || $project === '') {
        header('Location: ../index.php?review=error#testimonials');
        exit;
    }

    $role = 'Client';
    $stmt = $conn->prepare('INSERT INTO testimonials (name, business, role, review, rating, approved) VALUES (?, ?, ?, ?, ?, 0)');
    $inserted = false;

    if ($stmt) {
        $stmt->bind_param('ssssi', $name, $business, $role, $project, $rating);
        $inserted = $stmt->execute();
        if (!$inserted) {
            error_log('CAZTech review insert failed: ' . $stmt->error);
        }
        $stmt->close();
    } else {
        error_log('CAZTech review statement preparation failed: ' . $conn->error);
    }

    $conn->close();
    header('Location: ../index.php?review=' . ($inserted ? 'success' : 'error') . '#testimonials');
    exit;
}

if ($name === '' || $business === '' || $project === '') {
    $conn->close();
    caztech_render_process_result('Please complete the form', 'Please provide your name, email address, and message before sending your inquiry.', false, 422);
    exit;
}

$stmt = $conn->prepare('INSERT INTO leads (name, business, project_type) VALUES (?, ?, ?)');
if (!$stmt) {
    error_log('CAZTech contact statement preparation failed: ' . $conn->error);
    $conn->close();
    caztech_render_process_result('Message not saved', 'We could not save your message right now. Please try again later.', false, 500);
    exit;
}

$stmt->bind_param('sss', $name, $business, $project);
$inserted = $stmt->execute();
if (!$inserted) {
    error_log('CAZTech contact insert failed: ' . $stmt->error);
}
$stmt->close();
$conn->close();

if ($inserted) {
    caztech_render_process_result('Thank you for reaching out!', 'Your message has been received successfully. Our team will review your inquiry and get back to you soon.', true);
} else {
    caztech_render_process_result('Message not saved', 'We could not save your message right now. Please try again later.', false, 500);
}
?>