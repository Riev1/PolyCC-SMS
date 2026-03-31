<?php
require 'config.php';
require 'header.php';

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Logik Pendaftaran Kekal Sama
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $id_number = trim($_POST['id_number']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $class = trim($_POST['class']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($name) || empty($email) || empty($password)) {
        $error = "Sila isi semua ruangan yang diwajibkan.";
    } elseif ($password !== $confirm_password) {
        $error = "Kata laluan tidak sepadan!";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $check_email = $conn->query("SELECT * FROM users WHERE email='$email'");
        
        if ($check_email->num_rows > 0) {
            $error = "Alamat e-mel ini telah digunakan. Sila gunakan e-mel lain.";
        } else {
            $stmt = $conn->prepare("INSERT INTO users (name, email, id_number, phone, address, class_name, password) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssss", $name, $email, $id_number, $phone, $address, $class, $hashed_password);
            
            if ($stmt->execute()) {
                $success = "Pendaftaran Berjaya! Anda kini boleh log masuk.";
            } else {
                $error = "Ralat pangkalan data. Sila cuba lagi.";
            }
            $stmt->close();
        }
    }
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

    .glass-container-reg {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 3rem 0;
        min-height: 80vh;
        position: relative;
        z-index: 1;
    }

    .glass-card-reg {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(25px);
        -webkit-backdrop-filter: blur(25px);
        border: 1px solid rgba(255, 255, 255, 0.4);
        border-radius: 30px;
        box-shadow: 0 30px 60px rgba(0, 0, 0, 0.4);
        padding: 3rem;
        position: relative;
        overflow: hidden;
    }

    .reg-header {
        position: relative;
        z-index: 1;
        text-align: center;
        margin-bottom: 2.5rem;
    }

    .gempak-input-reg {
        background: rgba(241, 245, 249, 0.8) ;
        border: 2px solid transparent ;
        border-radius: 12px ;
        padding: 0.8rem 1rem 0.8rem 2.8rem ;
        font-weight: 500;
        transition: all 0.3s ease ;
    }
    .gempak-input-reg:focus {
        background: #ffffff ;
        border-color: #0ea5e9 ;
        box-shadow: 0 8px 20px rgba(14, 165, 233, 0.2) ;
        transform: translateY(-2px);
    }
    
    .input-icon-left-reg {
        position: absolute;
        left: 15px;
        top: 36px;
        color: #94a3b8;
        font-size: 1rem;
        z-index: 10;
        transition: color 0.3s;
    }
    .form-group-reg {
        position: relative;
        margin-bottom: 1.5rem;
        z-index: 1;
    }
    .form-group-reg:focus-within .input-icon-left-reg {
        color: #0ea5e9;
    }
    .form-label-reg {
        font-size: 0.85rem;
        font-weight: 700;
        color: #475569;
        margin-bottom: 0.4rem;
        display: block;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .btn-reg-gempak {
        background: linear-gradient(135deg, #0284c7, #06b6d4);
        color: white;
        border: none;
        border-radius: 15px;
        padding: 1.2rem;
        font-size: 1.1rem;
        font-weight: 700;
        letter-spacing: 1px;
        box-shadow: 0 10px 20px rgba(2, 132, 199, 0.3);
        transition: all 0.3s ease;
        position: relative;
        z-index: 1;
        overflow: hidden;
    }
    .btn-reg-gempak::after {
        content: ''; position: absolute; top: 0; left: -100%; width: 50%; height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
        transition: all 0.5s ease;
    }
    .btn-reg-gempak:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 30px rgba(2, 132, 199, 0.5);
        color: white;
    }
    .btn-reg-gempak:hover::after {
        left: 100%;
    }
</style>

<div class="orb orb-1"></div>
<div class="orb orb-2"></div>
<div class="orb orb-3"></div>

<div class="glass-container-reg fade-in-up">
    <div class="col-md-10 col-lg-8">
        <div class="glass-card-reg">
            
            <div class="reg-header">
                <div class="bg-info bg-gradient bg-opacity-10 p-4 rounded-circle d-inline-flex mb-3 shadow-sm border border-info border-opacity-50">
                    <i class="fas fa-satellite-dish fa-3x text-info"></i>
                </div>
                <h2 class="fw-bold text-dark mb-1">Pendaftaran Entiti Baru</h2>
                <p class="text-muted">Cipta profil anda untuk menerokai ekosistem PolyCC</p>
            </div>

            <?php 
            if($error) echo "<div class='alert alert-danger border-0 shadow-sm d-flex align-items-center mb-4 rounded-4' style='z-index:1; position:relative;'><div class='bg-danger rounded-circle p-1 me-3 d-flex'><i class='fas fa-times text-white small'></i></div><span class='fw-semibold'>$error</span></div>"; 
            if($success) echo "<div class='alert alert-success border-0 shadow-sm d-flex align-items-center mb-4 rounded-4' style='background:#cffafe; color:#083344; z-index:1; position:relative;'><div class='bg-info rounded-circle p-1 me-3 d-flex'><i class='fas fa-check text-white small'></i></div><span class='fw-semibold'>$success</span></div>"; 
            ?>
            
            <form method="POST" action="" onsubmit="return validateForm()">
                <div class="row">
                    <div class="col-md-6 form-group-reg">
                        <label class="form-label-reg">Nama Penuh</label>
                        <i class="fas fa-id-card input-icon-left-reg"></i>
                        <input type="text" name="name" id="name" class="form-control gempak-input-reg" placeholder="Cth: Ahmad Albab" required>
                    </div>
                    <div class="col-md-6 form-group-reg">
                        <label class="form-label-reg">E-mel Rasmi</label>
                        <i class="fas fa-envelope input-icon-left-reg"></i>
                        <input type="email" name="email" id="email" class="form-control gempak-input-reg" placeholder="Cth: ahmad@polycc.edu.my" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 form-group-reg">
                        <label class="form-label-reg">No. Matrik</label>
                        <i class="fas fa-hashtag input-icon-left-reg"></i>
                        <input type="text" name="id_number" id="id_number" class="form-control gempak-input-reg" placeholder="Cth: 04DIT21F1001" required>
                    </div>
                    <div class="col-md-4 form-group-reg">
                        <label class="form-label-reg">No. Telefon</label>
                        <i class="fas fa-phone-alt input-icon-left-reg"></i>
                        <input type="text" name="phone" id="phone" class="form-control gempak-input-reg" placeholder="Cth: 0123456789" required>
                    </div>
                    <div class="col-md-4 form-group-reg">
                        <label class="form-label-reg">Kelas</label>
                        <i class="fas fa-chalkboard input-icon-left-reg"></i>
                        <input type="text" name="class" id="class" class="form-control gempak-input-reg" placeholder="Cth: DIT4B" required>
                    </div>
                </div>

                <div class="form-group-reg">
                    <label class="form-label-reg">Alamat Tempat Tinggal</label>
                    <i class="fas fa-map-marker-alt input-icon-left-reg" style="top: 45px;"></i>
                    <textarea name="address" id="address" class="form-control gempak-input-reg" rows="2" placeholder="Masukkan alamat lengkap anda" required></textarea>
                </div>

                <div class="row mt-3">
                    <div class="col-md-6 form-group-reg">
                        <label class="form-label-reg">Kunci Kata Laluan</label>
                        <i class="fas fa-lock input-icon-left-reg"></i>
                        <input type="password" name="password" id="password" class="form-control gempak-input-reg" placeholder="Minimum 6 aksara" required>
                    </div>
                    <div class="col-md-6 form-group-reg">
                        <label class="form-label-reg">Sahkan Kunci</label>
                        <i class="fas fa-shield-alt input-icon-left-reg"></i>
                        <input type="password" name="confirm_password" id="confirm_password" class="form-control gempak-input-reg" placeholder="Ulang kata laluan" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-reg-gempak w-100 mt-3 mb-4">
                    Mulakan Pengembaraan <i class="fas fa-space-shuttle ms-2"></i>
                </button>

                <div class="text-center" style="position:relative; z-index:1;">
                    <p class="text-secondary fw-medium mb-0">Telah mendaftar sebelum ini? 
                        <a href="login.php" class="text-decoration-none fw-bold ms-1 text-info" style="position: relative; padding-bottom: 2px;">
                            Akses Akaun Anda
                        </a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function validateForm() {
    let pwd = document.getElementById("password").value;
    let cpwd = document.getElementById("confirm_password").value;

    if (pwd !== cpwd) {
        alert("Ralat: Kata laluan dan pengesahan kata laluan tidak sepadan!");
        return false;
    }
    if (pwd.length < 6) {
        alert("Ralat: Kata laluan mestilah sekurang-kurangnya 6 aksara.");
        return false;
    }
    return true;
}
</script>

<?php require 'footer.php'; ?>