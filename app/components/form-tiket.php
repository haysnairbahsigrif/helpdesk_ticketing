<section class="panel">
    <h2>Tambah tiket</h2>
    <p class="form-description">Isi nama dan jelaskan kendala yang dialami.</p>
    
    <form action="/actions/tambah-tiket.php" method="post">
        <div class="form-group">
            <label for="nama_pengguna">Nama pengguna</label>
            <input
                type="text"
                id="nama_pengguna"
                name="nama_pengguna"
                placeholder="Contoh: Firgi"
                maxlength="100"
                autocomplete="name"
                required>
        </div>

        <div class="form-group">
            <label for="keluhan">Keluhan</label>
            <textarea
                id="keluhan"
                name="keluhan"
                rows="5"
                placeholder="Contoh: Komputer tidak bisa terhubung ke internet."
                required></textarea>
        </div>

        <button type="submit" class="button-primary">
            Tambah tiket
        </button>
    </form>
</section>