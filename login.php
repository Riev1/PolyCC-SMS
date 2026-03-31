<?php
require 'config.php';
require 'header.php';

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, name, role, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user_name'] = $row['name'];
            $_SESSION['user_role'] = $row['role']; 
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Katalaluan tidak sah. Sila cuba lagi.";
        }
    } else {
        $error = "Akaun dengan e-mel ini tidak wujud.";
    }
    $stmt->close();
}
?>

<style>
    body {
        background: linear-gradient(-45deg, #020617, #1e3a8a, #0891b2, #3b82f6);
        background-size: 400% 400%;
        animation: cosmicGradient 15s ease infinite;
        position: relative;
        overflow-x: hidden;
    }
    
    @keyframes cosmicGradient {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }

    .orb {
        position: fixed;
        border-radius: 50%;
        filter: blur(80px);
        z-index: -1;
        animation: floatOrb 10s infinite ease-in-out alternate;
    }
    .orb-1 { width: 350px; height: 350px; background: rgba(56, 189, 248, 0.4); top: 5%; left: 10%; }
    .orb-2 { width: 450px; height: 450px; background: rgba(139, 92, 246, 0.3); bottom: 5%; right: 5%; animation-delay: -5s; }
    .orb-3 { width: 300px; height: 300px; background: rgba(6, 182, 212, 0.4); top: 40%; left: 60%; animation-delay: -2s; }

    @keyframes floatOrb {
        0% { transform: translateY(0px) scale(1); }
        100% { transform: translateY(-60px) scale(1.1); }
    }

    .glass-container {
        min-height: 80vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem 0;
        position: relative;
        z-index: 1;
    }
    .glass-card {
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(25px);
        -webkit-backdrop-filter: blur(25px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 30px;
        box-shadow: 0 30px 60px rgba(0, 0, 0, 0.4);
        overflow: hidden;
        max-width: 1000px;
        width: 100%;
    }
    .brand-panel {
        padding: 4rem;
        display: flex;
        flex-direction: column;
        justify-content: center;
        border-right: 1px solid rgba(255, 255, 255, 0.1);
    }
    .form-panel {
        background: rgba(255, 255, 255, 0.95);
        padding: 4rem 3rem;
    }

    .gempak-input {
        background: rgba(241, 245, 249, 0.8) ;
        border: 2px solid transparent ;
        border-radius: 15px ;
        padding: 1rem 1.25rem 1rem 3.5rem ;
        font-weight: 500;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) t;
    }
    .gempak-input:focus {
        background: #ffffff ;
        border-color: #06b6d4 ;
        box-shadow: 0 10px 25px rgba(6, 182, 212, 0.2) ;
        transform: translateY(-2px);
    }
    .input-icon-left {
        position: absolute;
        left: 20px;
        top: 50%;
        transform: translateY(-50%);
        color: #64748b;
        font-size: 1.2rem;
        z-index: 10;
        transition: color 0.3s;
    }
    .form-group-gempak:focus-within .input-icon-left {
        color: #06b6d4;
    }

    .btn-login-gempak {
        background: linear-gradient(135deg, #0ea5e9, #0284c7);
        color: white;
        border: none;
        border-radius: 15px;
        padding: 1.2rem;
        font-weight: 700;
        letter-spacing: 1.5px;
        box-shadow: 0 10px 20px rgba(2, 132, 199, 0.4);
        transition: all 0.3s ease;
    }
    .btn-login-gempak:hover {
        transform: translateY(-3px) scale(1.02);
        box-shadow: 0 15px 30px rgba(2, 132, 199, 0.6);
        color: white;
    }
</style>

<div class="orb orb-1"></div>
<div class="orb orb-2"></div>
<div class="orb orb-3"></div>

<div class="glass-container fade-in-up">
    <div class="row g-0 glass-card">
        
        <div class="col-lg-5 brand-panel d-none d-lg-flex text-white">
            <div style="z-index: 1;">
                <div class="bg-white bg-opacity-20 p-4 rounded-circle d-inline-flex mb-4 shadow-lg border border-white border-opacity-25" style="backdrop-filter: blur(10px);">
                    <i class="fas fa-rocket fa-3x text-info"></i>
                </div>
                <h1 class="fw-bold display-5 mb-3 text-white">PolyCC<br>Smart System</h1>
                <p class="lead text-white-50">Welcome back to your cosmic journey of learning and excellence.</p>
                <hr class="border-info opacity-50 my-4 border-2">
                <div class="d-flex align-items-center text-info small">
                    <i class="fas fa-user-shield fs-4 me-3"></i>
                    <span>Sistem disulitkan sepenuhnya (End-to-End Encryption)</span>
                </div>
            </div>
        </div>

        <div class="col-lg-7 form-panel">
            <div class="text-center mb-5 d-lg-none">
                <div class="bg-info bg-gradient p-3 rounded-circle d-inline-flex mb-3 shadow">
                    <i class="fas fa-rocket fa-2x text-white"></i>
                </div>
                <h2 class="fw-bold text-dark">Log Masuk</h2>
            </div>

            <h2 class="fw-bold text-dark mb-1 d-none d-lg-block">Akses Portal</h2>
            <p class="text-muted mb-4 d-none d-lg-block">Sila masukkan kelayakan anda untuk meneruskan.</p>

            <?php if($error): ?>
                <div class="alert alert-danger border-0 shadow-sm d-flex align-items-center p-3 mb-4 rounded-4" style="background-color: #fee2e2; color: #991b1b;">
                    <div class="bg-danger rounded-circle p-1 me-3 d-flex"><i class="fas fa-times text-white small"></i></div>
                    <span class="fw-semibold"><?php echo $error; ?></span>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group-gempak position-relative mb-4">
                    <i class="fas fa-envelope input-icon-left"></i>
                    <input type="email" name="email" class="form-control gempak-input w-100" placeholder="E-mel Rasmi" required>
                </div>

                <div class="form-group-gempak position-relative mb-4">
                    <i class="fas fa-lock input-icon-left"></i>
                    <input type="password" name="password" class="form-control gempak-input w-100" placeholder="Kata Laluan" required>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-5 small">
                    <div class="form-check">
                        <input class="form-check-input shadow-sm border-info" type="checkbox" id="rememberMe">
                        <label class="form-check-label text-secondary fw-medium" for="rememberMe">Ingat Saya</label>
                    </div>
                    <a href="#" class="text-decoration-none fw-bold text-info">Lupa Kata Laluan?</a>
                </div>

                <button type="submit" class="btn btn-login-gempak w-100 mb-4">
                    Log Masuk Sekarang <i class="fas fa-arrow-right ms-2"></i>
                </button>

                <div class="text-center">
                    <p class="text-secondary fw-medium mb-0">Pengguna baru? 
                        <a href="register.php" class="text-decoration-none fw-bold ms-1 text-info" style="position: relative; padding-bottom: 2px;">
                            Bina Akaun
                            <span style="position: absolute; bottom: 0; left: 0; width: 100%; height: 2px; background: #0891b2; transform: scaleX(0); transform-origin: right; transition: transform 0.3s ease;"></span>
                        </a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require 'footer.php'; ?>