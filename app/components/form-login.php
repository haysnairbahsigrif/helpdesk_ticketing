<section class="panel login-panel">
    <h2 id="judul-login">Masuk ke Helpdesk</h2>
    <p class="form-description">
        Gunakan akun Anda untuk mengakses layanan IT Support.
    </p>

    <form aria-labelledby="judul-login">
        <div class="form-group">
            <label for="email">Email</label>
            <input
                type="email"
                id="email"
                name="email"
                placeholder="nama@example.test"
                autocomplete="username"
                required
            >
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input
                type="password"
                id="password"
                name="password"
                autocomplete="current-password"
                required
            >
        </div>

        <button type="button" class="button-primary login-button">
            Masuk
        </button>
    </form>
</section>