<!-- Auth Modal Overlay -->
<div id="authOverlay" class="auth-overlay hidden">
  <div class="auth-modal">
    <div class="auth-header">
      <button type="button" class="auth-tab auth-tab-active" data-tab="login">Login</button>
      <button type="button" class="auth-tab" data-tab="register">Register</button>
      <button type="button" class="auth-close" id="authCloseBtn">&times;</button>
    </div>

    <div class="auth-body">
      <div id="authError" class="auth-error"></div>

      <!-- Login form -->
      <form id="loginForm" class="auth-form">
        <label>
          Email
          <input type="email" name="email" required>
        </label>
        <label>
          Password
          <input type="password" name="password" required>
        </label>
        <button type="submit">Login</button>
      </form>

      <!-- Register form -->
      <form id="registerForm" class="auth-form hidden">
        <label>
          Username
          <input type="text" name="username" required>
        </label>
        <label>
          Email
          <input type="email" name="email" required>
        </label>
        <label>
          Password
          <input type="password" name="password" required>
        </label>
        <label>
          Confirm Password
          <input type="password" name="password2" required>
        </label>
        <button type="submit">Create Account</button>
      </form>
    </div>
  </div>
</div>
