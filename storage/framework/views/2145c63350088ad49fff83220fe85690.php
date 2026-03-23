

<?php $__env->startSection('title', 'Connexion'); ?>

<?php $__env->startSection('content'); ?>
<div class="flex-1 flex flex-col items-center justify-center px-4 py-10">
    <div class="w-full max-w-[400px] fade-in">
        <!-- Logo -->
        <a href="<?php echo e(url('/')); ?>" class="flex items-center justify-center gap-2 mb-10">
            <span class="text-2xl">🔨</span>
            <span class="text-xl font-extrabold tracking-tight text-gray-900">ProxiPro</span>
        </a>

        <!-- Carte -->
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-8">
            <h1 class="text-xl font-bold text-center mb-1">Connectez-vous</h1>
            <p class="text-sm text-gray-500 text-center mb-7">Accédez à votre espace personnel</p>

            <!-- Boutons sociaux -->
            <div class="space-y-2.5 mb-6">
                <a href="<?php echo e(route('social.redirect', 'google')); ?>" class="flex items-center justify-center gap-3 w-full py-2.5 px-4 border border-gray-200 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                    <svg class="w-[18px] h-[18px]" viewBox="0 0 24 24">
                        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                    </svg>
                    Continuer avec Google
                </a>
                <a href="<?php echo e(route('social.redirect', 'facebook')); ?>" class="flex items-center justify-center gap-3 w-full py-2.5 px-4 border border-gray-200 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                    <svg class="w-[18px] h-[18px]" fill="#1877F2" viewBox="0 0 24 24">
                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                    </svg>
                    Continuer avec Facebook
                </a>
            </div>

            <!-- Séparateur -->
            <div class="flex items-center gap-3 mb-6">
                <div class="flex-1 h-px bg-gray-200"></div>
                <span class="text-xs text-gray-400 font-medium">ou</span>
                <div class="flex-1 h-px bg-gray-200"></div>
            </div>

            <!-- Messages -->
            <?php if(session('error')): ?>
            <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg flex items-start gap-2">
                <svg class="w-5 h-5 text-red-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/>
                </svg>
                <p class="text-sm text-red-700 font-medium"><?php echo e(session('error')); ?></p>
            </div>
            <?php endif; ?>

            <?php if(session('success')): ?>
            <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded-lg flex items-start gap-2">
                <svg class="w-5 h-5 text-green-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-sm text-green-700 font-medium"><?php echo e(session('success')); ?></p>
            </div>
            <?php endif; ?>

            <?php if($errors->any()): ?>
            <div class="mb-4 p-3 bg-red-50 border border-red-100 rounded-lg">
                <p class="text-sm text-red-600">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php echo e($error); ?>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </p>
            </div>
            <?php endif; ?>

            <?php if(session('status')): ?>
            <div class="mb-4 p-3 bg-green-50 border border-green-100 rounded-lg">
                <p class="text-sm text-green-700"><?php echo e(session('status')); ?></p>
            </div>
            <?php endif; ?>

            <!-- Formulaire -->
            <form method="POST" action="<?php echo e(route('login')); ?>" class="space-y-4" id="loginForm">
                <?php echo csrf_field(); ?>

                <!-- Anti-bot: Honeypot -->
                <div style="position:absolute;left:-9999px;top:-9999px;" aria-hidden="true" tabindex="-1">
                    <input type="text" name="website_url" value="" autocomplete="off" tabindex="-1">
                </div>

                <!-- reCAPTCHA v3 hidden token -->
                <input type="hidden" name="g-recaptcha-response" id="login-recaptcha-token">
                
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">E-mail</label>
                    <input 
                        id="email" name="email" type="email" required value="<?php echo e(old('email')); ?>" 
                        placeholder="nom@exemple.com" autofocus
                        class="w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder:text-gray-400 <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                    >
                    <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-xs text-red-500 mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="password" class="text-sm font-medium text-gray-700">Mot de passe</label>
                        <?php if(Route::has('password.request')): ?>
                        <a href="<?php echo e(route('password.request')); ?>" class="text-xs text-blue-600 hover:text-blue-700 font-medium">Oublié ?</a>
                        <?php endif; ?>
                    </div>
                    <div class="relative">
                        <input 
                            id="password" name="password" type="password" required 
                            placeholder="••••••••"
                            class="w-full px-3 py-2.5 pr-10 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder:text-gray-400 <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                        >
                        <button type="button" onclick="togglePassword()" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <svg id="eye-icon" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </button>
                    </div>
                    <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-xs text-red-500 mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="remember" <?php echo e(old('remember') ? 'checked' : ''); ?> class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <span class="text-sm text-gray-600">Se souvenir de moi</span>
                </label>

                <button type="submit" id="loginSubmitBtn" class="w-full py-2.5 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition disabled:opacity-50 disabled:cursor-not-allowed">
                    Se connecter
                </button>

                <p class="text-xs text-gray-400 text-center">
                    Protégé par reCAPTCHA.
                    <a href="https://policies.google.com/privacy" class="underline" target="_blank">Confidentialité</a>
                </p>
            </form>
        </div>

        <!-- Lien inscription -->
        <p class="text-center text-sm text-gray-500 mt-6">
            Pas encore de compte ? <a href="<?php echo e(route('register')); ?>" class="font-semibold text-blue-600 hover:text-blue-700">Créer un compte</a>
        </p>
        
        <p class="text-center text-xs text-gray-400 mt-4">
            <a href="<?php echo e(url('/legal/terms')); ?>" class="hover:text-gray-600 underline">Conditions</a> · <a href="<?php echo e(url('/legal/privacy')); ?>" class="hover:text-gray-600 underline">Confidentialité</a>
        </p>
    </div>
</div>

<script>
    function togglePassword() {
        const f = document.getElementById('password');
        f.type = f.type === 'password' ? 'text' : 'password';
    }

    // reCAPTCHA v3 for login
    const recaptchaSiteKey = '<?php echo e(config("services.recaptcha.site_key")); ?>';
    
    if (recaptchaSiteKey) {
        const recaptchaScript = document.createElement('script');
        recaptchaScript.src = `https://www.google.com/recaptcha/api.js?render=${recaptchaSiteKey}`;
        recaptchaScript.async = true;
        recaptchaScript.defer = true;
        document.head.appendChild(recaptchaScript);
        
        const loginForm = document.getElementById('loginForm');
        if (loginForm) {
            loginForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const btn = document.getElementById('loginSubmitBtn');
                btn.disabled = true;
                btn.textContent = 'V\u00e9rification...';
                
                if (typeof grecaptcha !== 'undefined') {
                    grecaptcha.ready(function() {
                        grecaptcha.execute(recaptchaSiteKey, { action: 'login' })
                            .then(function(token) {
                                document.getElementById('login-recaptcha-token').value = token;
                                loginForm.submit();
                            })
                            .catch(function() {
                                btn.disabled = false;
                                btn.textContent = 'Se connecter';
                                loginForm.submit();
                            });
                    });
                } else {
                    loginForm.submit();
                }
            });
        }
    }
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.auth', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\PC\Desktop\MASSIWANI V2\resources\views/auth/login.blade.php ENDPATH**/ ?>