<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Dealer Mobil</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: linear-gradient(135deg, #0156BF 0%, #3674B5 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }

        .container {
            background-color: #FFFFFF;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(29, 22, 22, 0.16);
            max-width: 500px;
            width: 100%;
        }

        .logo {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo-icon {
            font-size: 48px;
            margin-bottom: 10px;
        }

        .logo-text {
            color: #0156BF;
            font-size: 24px;
            font-weight: bold;
        }

        h2 {
            color: #1D1616;
            margin-bottom: 10px;
            font-size: 28px;
        }

        .subtitle {
            color: #616161;
            margin-bottom: 30px;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #1D1616;
            font-weight: 600;
            font-size: 14px;
        }

        .input-wrapper {
            position: relative;
        }

        input {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid #E9E9E9;
            border-radius: 8px;
            font-size: 15px;
            transition: all 0.3s ease;
            color: #1D1616;
        }

        input:focus {
            outline: none;
            border-color: #0156BF;
            box-shadow: 0 0 0 3px rgba(1, 86, 191, 0.1);
        }

        input::placeholder {
            color: #616161;
        }

        .password-strength {
            margin-top: 8px;
            height: 4px;
            background-color: #E9E9E9;
            border-radius: 2px;
            overflow: hidden;
            display: none;
        }

        .password-strength-bar {
            height: 100%;
            width: 0%;
            transition: all 0.3s ease;
            background-color: #FF0000;
        }

        .password-strength.show {
            display: block;
        }

        .password-strength-bar.weak {
            width: 33%;
            background-color: #FF0000;
        }

        .password-strength-bar.medium {
            width: 66%;
            background-color: #FFDE00;
        }

        .password-strength-bar.strong {
            width: 100%;
            background-color: #9DDE8B;
        }

        .error {
            color: #FF0000;
            font-size: 13px;
            margin-top: 6px;
            display: none;
        }

        .error.show {
            display: block;
        }

        .success {
            background-color: #9DDE8B;
            color: #1D1616;
            padding: 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: none;
            font-weight: 500;
            text-align: center;
        }

        .success.show {
            display: block;
        }

        .button {
            width: 100%;
            padding: 14px;
            background-color: #0156BF;
            color: #FFFFFF;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 10px;
            transition: all 0.3s ease;
        }

        .button:hover {
            background-color: #014a9d;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(1, 86, 191, 0.3);
        }

        .button:active {
            transform: translateY(0);
        }

        .button:disabled {
            background-color: #E9E9E9;
            color: #616161;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .loading {
            display: none;
            text-align: center;
            margin-top: 15px;
            color: #616161;
            font-size: 14px;
        }

        .loading.show {
            display: block;
        }

        .spinner {
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 2px solid #E9E9E9;
            border-top-color: #0156BF;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
            margin-right: 8px;
            vertical-align: middle;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .requirements {
            background-color: #EDEDED;
            padding: 15px;
            border-radius: 8px;
            margin-top: 20px;
            font-size: 13px;
        }

        .requirements-title {
            color: #1D1616;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .requirement-item {
            color: #616161;
            margin: 5px 0;
            padding-left: 20px;
            position: relative;
        }

        .requirement-item:before {
            content: "•";
            position: absolute;
            left: 5px;
            color: #0156BF;
        }

        .divider {
            height: 1px;
            background-color: #E9E9E9;
            margin: 25px 0;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="logo">
            <div class="logo-text">BMS</div>
        </div>

        <h2>Reset Password</h2>
        <p class="subtitle">Buat password baru untuk akun Anda</p>

        <div id="successMessage" class="success">
            <span>✓</span> <span id="successText"></span>
        </div>

        <form id="resetForm">
            <input type="hidden" id="token" value="{{ $token }}">
            <input type="hidden" id="email" value="{{ $email }}">

            <div class="form-group">
                <label for="password">Password Baru</label>
                <div class="input-wrapper">
                    <input type="password" id="password" name="password" required minlength="8"
                        placeholder="Masukkan password baru">
                </div>
                <div class="password-strength" id="passwordStrength">
                    <div class="password-strength-bar" id="passwordStrengthBar"></div>
                </div>
                <div id="passwordError" class="error"></div>
            </div>

            <div class="form-group">
                <label for="password_confirmation">Konfirmasi Password</label>
                <div class="input-wrapper">
                    <input type="password" id="password_confirmation" name="password_confirmation" required
                        minlength="8" placeholder="Masukkan ulang password baru">
                </div>
                <div id="confirmError" class="error"></div>
            </div>

            <div class="requirements">
                <div class="requirements-title">Password harus memenuhi:</div>
                <div class="requirement-item">Minimal 8 karakter</div>
                <div class="requirement-item">Kombinasi huruf dan angka lebih aman</div>
                <div class="requirement-item">Hindari penggunaan password yang mudah ditebak</div>
            </div>

            <button type="submit" class="button" id="submitBtn">Reset Password</button>

            <div class="loading" id="loading">
                <span class="spinner"></span>
                <span>Memproses permintaan Anda...</span>
            </div>
        </form>
    </div>

    <script>
        const passwordInput = document.getElementById('password');
        const passwordConfirmation = document.getElementById('password_confirmation');
        const passwordStrength = document.getElementById('passwordStrength');
        const passwordStrengthBar = document.getElementById('passwordStrengthBar');
        const submitBtn = document.getElementById('submitBtn');
        const loading = document.getElementById('loading');
        const successMessage = document.getElementById('successMessage');
        const successText = document.getElementById('successText');


        passwordInput.addEventListener('input', function() {
            const password = this.value;

            if (password.length > 0) {
                passwordStrength.classList.add('show');

                let strength = 0;
                if (password.length >= 8) strength++;
                if (password.match(/[a-z]/) && password.match(/[A-Z]/)) strength++;
                if (password.match(/[0-9]/)) strength++;
                if (password.match(/[^a-zA-Z0-9]/)) strength++;

                passwordStrengthBar.className = 'password-strength-bar';

                if (strength <= 1) {
                    passwordStrengthBar.classList.add('weak');
                } else if (strength <= 2) {
                    passwordStrengthBar.classList.add('medium');
                } else {
                    passwordStrengthBar.classList.add('strong');
                }
            } else {
                passwordStrength.classList.remove('show');
            }
        });


        document.getElementById('resetForm').addEventListener('submit', async function(e) {
            e.preventDefault();


            document.getElementById('passwordError').classList.remove('show');
            document.getElementById('confirmError').classList.remove('show');
            successMessage.classList.remove('show');

            const password = passwordInput.value;
            const passwordConf = passwordConfirmation.value;
            const token = document.getElementById('token').value;


            let hasError = false;

            if (password.length < 8) {
                document.getElementById('passwordError').textContent = 'Password minimal 8 karakter';
                document.getElementById('passwordError').classList.add('show');
                hasError = true;
            }

            if (password !== passwordConf) {
                document.getElementById('confirmError').textContent = 'Konfirmasi password tidak sesuai';
                document.getElementById('confirmError').classList.add('show');
                hasError = true;
            }

            if (hasError) return;


            submitBtn.disabled = true;
            loading.classList.add('show');

            try {
                const response = await fetch('/api/password/reset', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        token: token,
                        password: password,
                        password_confirmation: passwordConf
                    })
                });

                const data = await response.json();

                if (response.ok) {
                    successText.textContent = data.message;
                    successMessage.classList.add('show');
                    document.getElementById('resetForm').reset();
                    passwordStrength.classList.remove('show');


                    setTimeout(() => {
                        successText.textContent = 'Mengalihkan ke halaman login...';
                        setTimeout(() => {
                            window.location.href = '/';
                        }, 1000);
                    }, 2000);
                } else {
                    if (data.errors) {
                        if (data.errors.password) {
                            document.getElementById('passwordError').textContent = data.errors.password[0];
                            document.getElementById('passwordError').classList.add('show');
                        }
                        if (data.errors.password_confirmation) {
                            document.getElementById('confirmError').textContent = data.errors
                                .password_confirmation[0];
                            document.getElementById('confirmError').classList.add('show');
                        }
                    } else {
                        alert(data.message || 'Terjadi kesalahan saat reset password');
                    }
                }
            } catch (error) {
                alert('Terjadi kesalahan: ' + error.message);
            } finally {
                submitBtn.disabled = false;
                loading.classList.remove('show');
            }
        });
    </script>
</body>

</html>
