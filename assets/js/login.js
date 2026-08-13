/**
 * login.js
 * Login page specific JavaScript.
 * - Password show/hide toggle
 * - Client-side form validation
 * - Auto-dismiss flash messages
 */

document.addEventListener('DOMContentLoaded', () => {

    // ── 1. Password Show / Hide Toggle ─────────────────────────────────────
    const pwToggle   = document.getElementById('pw-toggle');
    const pwInput    = document.getElementById('password');
    const pwIconShow = document.getElementById('pw-icon-show'); // eye (password hidden)
    const pwIconHide = document.getElementById('pw-icon-hide'); // eye-off (password visible)

    if (pwToggle && pwInput) {
        pwToggle.addEventListener('click', () => {
            const isHidden = pwInput.type === 'password';
            pwInput.type = isHidden ? 'text' : 'password';
            pwIconShow.classList.toggle('hidden', isHidden);
            pwIconHide.classList.toggle('hidden', !isHidden);
            pwInput.focus();
        });
    }

    // ── 2. Client-side Validation ──────────────────────────────────────────
    const loginForm   = document.getElementById('login-form');
    const emailInput  = document.getElementById('email');
    const emailError  = document.getElementById('email-error');
    const passwordError = document.getElementById('password-error');
    const signInBtn   = document.getElementById('sign-in-btn');

    if (loginForm) {
        loginForm.addEventListener('submit', (e) => {
            let valid = true;

            // Validate email
            const emailVal = emailInput?.value.trim() ?? '';
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailVal || !emailRegex.test(emailVal)) {
                emailError?.classList.remove('hidden');
                emailInput?.classList.add('border-destructive');
                valid = false;
            } else {
                emailError?.classList.add('hidden');
                emailInput?.classList.remove('border-destructive');
            }

            // Validate password
            const pwVal = pwInput?.value ?? '';
            if (!pwVal) {
                passwordError?.classList.remove('hidden');
                pwInput?.classList.add('border-destructive');
                valid = false;
            } else {
                passwordError?.classList.add('hidden');
                pwInput?.classList.remove('border-destructive');
            }

            if (!valid) {
                e.preventDefault();
                return;
            }

            // Show loading state
            if (signInBtn) {
                signInBtn.disabled = true;
                signInBtn.innerHTML = `
                    <svg class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 12a9 9 0 1 1-6.219-8.56"/>
                    </svg>
                    Signing in...
                `;
            }
        });

        // Clear error state on input
        [emailInput, pwInput].forEach(input => {
            input?.addEventListener('input', () => {
                input.classList.remove('border-destructive');
                const errorEl = input.id === 'email' ? emailError : passwordError;
                errorEl?.classList.add('hidden');
            });
        });
    }

    // ── 3. Auto-dismiss Flash Messages ────────────────────────────────────
    const flashMessages = document.querySelectorAll('#flash-error, #flash-success');
    flashMessages.forEach(msg => {
        setTimeout(() => {
            msg.style.transition = 'opacity 0.5s ease';
            msg.style.opacity = '0';
            setTimeout(() => msg.remove(), 500);
        }, 4000);
    });

    // ── 4. Auto-focus Email Field ──────────────────────────────────────────
    // (already handled by autofocus HTML attr, but this is a fallback)
    if (emailInput && !emailInput.value) {
        emailInput.focus();
    }

});
