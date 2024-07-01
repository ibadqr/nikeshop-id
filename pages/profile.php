<?php
session_start();
require '../php/db.php';

// Pastikan hanya pengguna yang login yang dapat mengakses halaman profil
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Handle form submission untuk ganti password
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['change_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Query untuk mengambil password user saat ini dari database
    $query = "SELECT password FROM users WHERE id=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        $stored_password = $user['password'];

        // Periksa apakah password saat ini sesuai
        if (password_verify($current_password, $stored_password)) {
            // Periksa apakah password baru dan konfirmasi password cocok
            if ($new_password === $confirm_password) {
                // Update password baru ke database
                $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
                $update_query = "UPDATE users SET password=? WHERE id=?";
                $update_stmt = $conn->prepare($update_query);
                $update_stmt->bind_param("si", $hashed_password, $user_id);
                if ($update_stmt->execute()) {
                    $password_change_success = "Password berhasil diubah.";
                } else {
                    $password_change_error = "Gagal mengubah password.";
                }
            } else {
                $password_change_error = "Password baru dan konfirmasi password tidak cocok.";
            }
        } else {
            $password_change_error = "Password saat ini salah.";
        }
    } else {
        $password_change_error = "User tidak ditemukan.";
    }
}

// Query untuk mengambil data pengguna kecuali password dan role
$query = "SELECT id, username, email, profile_image FROM users WHERE id=?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Profil</h5>
                    </div>
                    <div class="card-body">
                        <!-- Tampilkan gambar profil jika ada -->
                        <?php if ($user['profile_image']): ?>
                        <img src="<?php echo $user['profile_image']; ?>" class="img-fluid rounded-circle mb-3" alt="Profile Image" style="max-width: 100px; img-align: center;">
                        <?php endif; ?>
                        <p><strong>ID:</strong> <?php echo $user['id']; ?></p>
                        <p><strong>Username:</strong> <?php echo $user['username']; ?></p>
                        <p><strong>Email:</strong> <?php echo $user['email']; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Ganti Password</h5>
                    </div>
                    <div class="card-body">
                        <form action="" method="post">
                            <div class="form-group">
                                <label for="current_password">Password Saat  Ini:</label>
                                <input type="password" id="current_password" name="current_password" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="new_password">Password Baru:</label>
                                <input type="password" id="new_password" name="new_password" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="confirm_password">Konfirmasi Password Baru:</label>
                                <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
                            </div>
                            <button type="submit" name="change_password" class="btn btn-primary">Simpan</button>
                        </form>
                        <?php if (isset($password_change_success)): ?>
                            <div class="alert alert-success mt-3"><?php echo $password_change_success; ?></div>
                        <?php endif; ?>
                        <?php if (isset($password_change_error)): ?>
                            <div class="alert alert-danger mt-3"><?php echo $password_change_error; ?></div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
<?php
} else {
    // Jika tidak ada data pengguna ditemukan
    echo "User not found.";
}
?>