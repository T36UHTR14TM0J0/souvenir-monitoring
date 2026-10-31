/**
 * Password Strength & Match Checker
 * Reusable — untuk halaman Create/Edit User
 *
 * Cara pakai: pastikan elemen berikut ada:
 *   - input[type=password]#password
 *   - input[type=password]#password_confirmation
 *   - #passwordStrength (container)
 *   - #passwordStrengthText
 *   - #passwordMatchText
 */
(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {
        const passwordInput = document.getElementById('password');
        const confirmInput  = document.getElementById('password_confirmation');
        const strengthBars  = document.querySelectorAll('#passwordStrength .password-strength-bar');
        const strengthText  = document.getElementById('passwordStrengthText');
        const matchText     = document.getElementById('passwordMatchText');

        if (!passwordInput || !confirmInput) return;

        /* ============================================
           TOGGLE PASSWORD VISIBILITY (global function)
           ============================================ */
        window.togglePassword = function (inputId, button) {
            const input = document.getElementById(inputId);
            const icon  = button.querySelector('i');
            if (!input || !icon) return;

            const isPassword = input.type === 'password';
            input.type = isPassword ? 'text' : 'password';
            icon.classList.toggle('fa-eye', !isPassword);
            icon.classList.toggle('fa-eye-slash', isPassword);
        };

        /* ============================================
           PASSWORD STRENGTH
           ============================================ */
        function updatePasswordStrength() {
            const val = passwordInput.value;
            let score = 0;

            if (val.length >= 8)          score++;
            if (/[A-Z]/.test(val))        score++;
            if (/[0-9]/.test(val))        score++;
            if (/[^A-Za-z0-9]/.test(val)) score++;

            strengthBars.forEach(bar => {
                bar.classList.remove('active-weak', 'active-medium', 'active-strong');
            });

            if (val.length === 0) {
                strengthText.textContent = '';
                strengthText.style.color = '';
                return;
            }

            let className, text, color;
            if (score <= 1) {
                className = 'active-weak';
                text = 'Password lemah';
                color = 'var(--color-danger)';
            } else if (score <= 3) {
                className = 'active-medium';
                text = 'Password sedang';
                color = 'var(--color-warning)';
            } else {
                className = 'active-strong';
                text = 'Password kuat';
                color = 'var(--color-success)';
            }

            for (let i = 0; i < score; i++) {
                strengthBars[i]?.classList.add(className);
            }
            strengthText.textContent = text;
            strengthText.style.color = color;
        }

        /* ============================================
           PASSWORD MATCH
           ============================================ */
        function updatePasswordMatch() {
            const val = confirmInput.value;

            if (val.length === 0) {
                matchText.textContent = '';
                matchText.style.color = '';
                return;
            }

            if (val === passwordInput.value) {
                matchText.textContent = '✓ Password cocok';
                matchText.style.color = 'var(--color-success)';
            } else {
                matchText.textContent = '✗ Password tidak cocok';
                matchText.style.color = 'var(--color-danger)';
            }
        }

        /* ============================================
           EVENTS
           ============================================ */
        passwordInput.addEventListener('input', function () {
            updatePasswordStrength();
            if (confirmInput.value.length > 0) {
                updatePasswordMatch();
            }
        });

        confirmInput.addEventListener('input', updatePasswordMatch);

        // Init (jika ada nilai awal, misal karena old() input)
        if (passwordInput.value) updatePasswordStrength();
        if (confirmInput.value) updatePasswordMatch();
    });
})();