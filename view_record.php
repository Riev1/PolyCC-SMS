<?php
require 'config.php';
require 'header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$target_student_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($_SESSION['user_role'] === 'student' && $target_student_id !== $_SESSION['user_id']) {
    echo "<div class='alert alert-danger mt-5 shadow-sm'><i class='fas fa-ban me-2'></i> Akses Ditolak. Anda hanya boleh melihat rekod anda sendiri.</div>";
    require 'footer.php';
    exit();
}

$success = "";
$error = "";

if (isset($_POST['delete_record_id']) && $_SESSION['user_role'] === 'admin') {
    $del_id = intval($_POST['delete_record_id']);

    $stmt_file = $conn->prepare("SELECT file_path FROM student_records WHERE id = ?");
    $stmt_file->bind_param("i", $del_id);
    $stmt_file->execute();
    $res_file = $stmt_file->get_result()->fetch_assoc();
    $stmt_file->close();

    if ($res_file && !empty($res_file['file_path']) && file_exists($res_file['file_path'])) {
        unlink($res_file['file_path']);
    }

    $stmt_del = $conn->prepare("DELETE FROM student_records WHERE id = ?");
    $stmt_del->bind_param("i", $del_id);
    if ($stmt_del->execute()) {
        $success = "Rekod dan fail lampiran telah berjaya dipadam sepenuhnya.";
    } else {
        $error = "Ralat: Gagal memadam rekod.";
    }
    $stmt_del->close();
}

if (isset($_POST['tambah_rekod']) && $_SESSION['user_role'] === 'admin') {
    $record_category = $_POST['record_category'];
    $created_by = $_SESSION['user_id'];
    $file_path = NULL; 
    

    $record_type = $record_category; 
    $description = "";

    if (strpos($record_category, 'Jawatan') !== false) {
        if ($record_category === 'Jawatan JPP') $record_type = "JPP: " . $_POST['jpp_role'];
        if ($record_category === 'Jawatan JPKK') $record_type = "JPKK: " . $_POST['jpkk_role'];
        if ($record_category === 'Jawatan Kelab Multimedia') $record_type = "Kelab Multimedia: " . $_POST['multimedia_role'];
        
        $description = "Sesi Akademik: " . $_POST['sesi_polycc'];
    } else {
        $description = trim($_POST['description']);
    }

    if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/'; 
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true); 
        
        $file_name = time() . '_' . basename($_FILES['attachment']['name']);
        $target_file = $upload_dir . $file_name;
        
        if (move_uploaded_file($_FILES['attachment']['tmp_name'], $target_file)) {
            $file_path = $target_file;
        } else {
            $error = "Gagal memuat naik lampiran.";
        }
    }

    if (!empty($description) && empty($error)) {
        $stmt = $conn->prepare("INSERT INTO student_records (student_id, record_type, description, file_path, created_by) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("isssi", $target_student_id, $record_type, $description, $file_path, $created_by);
        if ($stmt->execute()) {
            $success = "Data berjaya direkodkan!";
        } else {
            $error = "Gagal menyimpan rekod.";
        }
        $stmt->close();
    }
}

$stmt = $conn->prepare("SELECT name, email, id_number, phone, class_name FROM users WHERE id = ? AND role = 'student'");
$stmt->bind_param("i", $target_student_id);
$stmt->execute();
$student_info = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$student_info) {
    echo "<div class='alert alert-warning mt-5'>Pelajar tidak dijumpai.</div>";
    require 'footer.php';
    exit();
}

$records = $conn->query("SELECT * FROM student_records WHERE student_id = $target_student_id ORDER BY created_at DESC");
?>

<div class="row">
    <div class="col-lg-4 mb-4">
        <div class="card border-0 shadow-sm rounded-4 position-sticky" style="top: 20px;">
            <div class="card-body p-4 text-center">
                <div class="bg-primary bg-gradient text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3 shadow" style="width: 80px; height: 80px; font-size: 30px; font-weight: bold;">
                    <?php echo substr($student_info['name'], 0, 1); ?>
                </div>
                <h4 class="fw-bold mb-1"><?php echo htmlspecialchars($student_info['name']); ?></h4>
                <p class="text-muted mb-3"><span class="badge bg-dark">ID: <?php echo htmlspecialchars($student_info['id_number']); ?></span></p>
                <hr>
                <div class="text-start text-muted">
                    <p class="mb-2"><i class="fas fa-envelope text-primary me-2"></i> <?php echo htmlspecialchars($student_info['email']); ?></p>
                    <p class="mb-2"><i class="fas fa-phone text-primary me-2"></i> <?php echo htmlspecialchars($student_info['phone']); ?></p>
                    <p class="mb-0"><i class="fas fa-chalkboard-teacher text-primary me-2"></i> Kelas: <?php echo htmlspecialchars($student_info['class_name']); ?></p>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        
        <?php if ($_SESSION['user_role'] === 'admin'): ?>
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-header bg-dark text-white p-3 border-bottom d-flex align-items-center rounded-top-4">
                <i class="fas fa-edit me-2"></i> <h5 class="mb-0 fw-bold">Tambah Rekod / Fail Pelajar</h5>
            </div>
            <div class="card-body p-4 bg-light bg-opacity-50">
                <?php 
                if($success) echo "<div class='alert alert-success py-2 shadow-sm'><i class='fas fa-check-circle'></i> $success</div>"; 
                if($error) echo "<div class='alert alert-danger py-2 shadow-sm'>$error</div>"; 
                ?>
                <form method="POST" action="" enctype="multipart/form-data">
                    <input type="hidden" name="tambah_rekod" value="1">
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold text-dark">Kategori Utama</label>
                        <select name="record_category" id="record_category" class="form-select border-primary shadow-sm" required onchange="tukarBorang()">
                            <option value="" disabled selected>-- Pilih Kategori --</option>
                            <optgroup label="Akademik & Disiplin">
                                <option value="Result Semester">Result Semester</option>
                                <option value="Kesalahan Disiplin">Kesalahan Disiplin</option>
                            </optgroup>
                            <optgroup label="Sijil & Pencapaian">
                                <option value="Sijil Penyertaan / AJK">Sijil Penyertaan / AJK Program</option>
                                <option value="Anugerah Khas">Anugerah / Pencapaian Khas</option>
                            </optgroup>
                            <optgroup label="Jawatan Pelajar (Organisasi)">
                                <option value="Jawatan JPP">Majlis Perwakilan Pelajar (JPP)</option>
                                <option value="Jawatan JPKK">Jawatankuasa Kamsis/Asrama (JPKK)</option>
                                <option value="Jawatan Kelab Multimedia">Kelab Multimedia</option>
                            </optgroup>
                        </select>
                    </div>

                    <div class="mb-3 d-none" id="jpp_section">
                        <label class="form-label fw-bold text-dark">Pilih Jawatan JPP</label>
                        <select name="jpp_role" id="jpp_role" class="form-select border-success">
                            <option value="Yang Dipertua (YDP)">Yang Dipertua (YDP)</option>
                            <option value="Timbalan Yang Dipertua (TYDP)">Timbalan Yang Dipertua (TYDP)</option>
                            <option value="Naib Yang Dipertua (NYDP)">Naib Yang Dipertua (NYDP)</option>
                            <option value="Setiausaha Agung">Setiausaha Agung</option>
                            <option value="Bendahari Kehormat">Bendahari Kehormat</option>
                            <option value="Exco Akademik & Kerjaya">Exco Akademik & Kerjaya</option>
                            <option value="Exco Kebajikan & Sosial">Exco Kebajikan & Sosial</option>
                            <option value="Exco Sukan & Kebudayaan">Exco Sukan & Kebudayaan</option>
                            <option value="Exco Kerohanian & Moral">Exco Kerohanian & Moral</option>
                            <option value="Exco Multimedia & Publisiti">Exco Multimedia & Publisiti</option>
                        </select>
                    </div>

                    <div class="mb-3 d-none" id="jpkk_section">
                        <label class="form-label fw-bold text-dark">Pilih Jawatan JPKK (Kamsis)</label>
                        <select name="jpkk_role" id="jpkk_role" class="form-select border-success">
                            <option value="Ketua Pelajar Kamsis (KPK)">Ketua Pelajar Kamsis (KPK) / YDP</option>
                            <option value="Timbalan Ketua Pelajar Kamsis">Timbalan Ketua Pelajar Kamsis</option>
                            <option value="Setiausaha">Setiausaha</option>
                            <option value="Bendahari">Bendahari</option>
                            <option value="Biro Disiplin & Keselamatan">Biro Disiplin & Keselamatan</option>
                            <option value="Biro Kerohanian">Biro Kerohanian</option>
                            <option value="Biro Sukan & Riadah">Biro Sukan & Riadah</option>
                            <option value="Biro Kebersihan & Keceriaan">Biro Kebersihan & Keceriaan</option>
                            <option value="Biro Kebajikan">Biro Kebajikan</option>
                            <option value="Ketua Blok / Penghulu">Ketua Blok / Penghulu</option>
                        </select>
                    </div>

                    <div class="mb-3 d-none" id="multimedia_section">
                        <label class="form-label fw-bold text-dark">Pilih Jawatan Kelab Multimedia</label>
                        <select name="multimedia_role" id="multimedia_role" class="form-select border-success">
                            <option value="Pengerusi Kelab Multimedia">Pengerusi Kelab Multimedia</option>
                            <option value="Timbalan Pengerusi">Timbalan Pengerusi</option>
                            <option value="Biro Teknikal">Biro Teknikal</option>
                            <option value="Biro Photographer">Biro Photographer</option>
                            <option value="Biro Editor/Editing">Biro Editor / Editing</option>
                            <option value="Biro Publisiti">Biro Publisiti</option>
                            <option value="Biro Audio">Biro Audio</option>
                        </select>
                    </div>

                    <div class="mb-3 d-none" id="butiran_section">
                        <label class="form-label fw-bold text-dark">Butiran / Keterangan Lanjut</label>
                        <textarea name="description" id="input_description" class="form-control border-primary shadow-sm" rows="2" placeholder="Contoh: CGPA 3.8 / Terlibat event / Ahli aktif / Catatan kesalahan..."></textarea>
                    </div>

                    <div class="mb-3 d-none" id="sesi_section">
                        <label class="form-label fw-bold text-dark"><i class="far fa-calendar-alt text-success"></i> Sesi Akademik</label>
                        <select name="sesi_polycc" id="input_sesi" class="form-select border-success shadow-sm">
                            <option value="Sesi II: 2025/2026" selected>Sesi II: 2025/2026 (Semasa)</option>
                            <option value="Sesi I: 2025/2026">Sesi I: 2025/2026</option>
                            <option value="Sesi II: 2024/2025">Sesi II: 2024/2025</option>
                            <option value="Sesi I: 2024/2025">Sesi I: 2024/2025</option>
                            <option value="Sesi I: 2026/2027">Sesi I: 2026/2027</option>
                        </select>
                    </div>

                    <div class="mb-4 d-none" id="upload_section">
                        <label class="form-label fw-bold text-dark"><i class="fas fa-file-pdf text-danger"></i> Muat Naik Dokumen Bukti</label>
                        <input type="file" name="attachment" id="attachment" class="form-control border-danger shadow-sm" accept=".pdf, .jpg, .jpeg, .png">
                        <small class="text-muted d-block mt-1">Muat naik slip keputusan, surat amaran, atau salinan sijil (PDF/Gambar).</small>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-primary fw-bold px-4 rounded-pill shadow-sm"><i class="fas fa-save me-1"></i> Simpan Rekod</button>
                    </div>
                </form>
            </div>
        </div>
        <?php endif; ?>

        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white p-3 border-bottom d-flex align-items-center">
                <i class="fas fa-folder-open text-primary me-2"></i> <h5 class="mb-0 fw-bold">Fail Peribadi & Rekod Pelajar</h5>
            </div>
            <div class="card-body p-4">
                <?php if ($records->num_rows > 0): ?>
                    <div class="timeline-wrapper">
                        <?php while($rec = $records->fetch_assoc()): 
                            $badgeColor = 'secondary';
                            if(strpos($rec['record_type'], 'Disiplin') !== false) $badgeColor = 'danger'; 
                            if(strpos($rec['record_type'], 'Sijil') !== false || strpos($rec['record_type'], 'Anugerah') !== false) $badgeColor = 'success'; 
                            if(strpos($rec['record_type'], 'Result') !== false) $badgeColor = 'info text-dark'; 
                            if(strpos($rec['record_type'], 'JPP') !== false || strpos($rec['record_type'], 'JPKK') !== false || strpos($rec['record_type'], 'Kelab') !== false) $badgeColor = 'primary'; 
                        ?>
                            <div class="d-flex mb-4">
                                <div class="me-3 mt-1">
                                    <i class="fas fa-circle text-<?php echo str_replace(' text-dark', '', $badgeColor); ?> small"></i>
                                </div>
                                <div class="flex-grow-1 border-start ps-3 border-3 border-<?php echo str_replace(' text-dark', '', $badgeColor); ?> bg-light p-3 rounded-end shadow-sm">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <span class="badge bg-<?php echo $badgeColor; ?> px-2 py-1 fs-6"><?php echo htmlspecialchars($rec['record_type']); ?></span>
                                        
                                        <div class="d-flex align-items-center">
                                            <small class="text-muted fw-bold me-2"><i class="far fa-clock me-1"></i> <?php echo date('d M Y, h:i A', strtotime($rec['created_at'])); ?></small>
                                            
                                            <?php if ($_SESSION['user_role'] === 'admin'): ?>
                                                <form method="POST" action="" class="m-0 p-0" onsubmit="return confirm('Adakah anda pasti mahu memadam rekod ini secara kekal? Fail lampiran juga akan dipadam.');">
                                                    <input type="hidden" name="delete_record_id" value="<?php echo $rec['id']; ?>">
                                                    <button type="submit" class="btn btn-sm btn-outline-danger border-0 p-1" title="Padam Rekod">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    
                                    <p class="mb-3 text-dark fw-semibold"><?php echo nl2br(htmlspecialchars($rec['description'])); ?></p>
                                    
                                    <?php if(!empty($rec['file_path'])): ?>
                                        <div class="mt-2 pt-2 border-top">
                                            <a href="<?php echo htmlspecialchars($rec['file_path']); ?>" target="_blank" class="btn btn-sm btn-outline-danger rounded-pill shadow-sm">
                                                <i class="fas fa-file-download me-1"></i> Muat Turun Lampiran
                                            </a>
                                        </div>
                                    <?php endif; ?>

                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-box-open fa-3x text-secondary opacity-50 mb-3"></i>
                        <p class="mb-0">Belum ada sebarang dokumen, result, atau jawatan direkodkan.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

    </div>
</div>

<script>
function tukarBorang() {
    var kategori = document.getElementById("record_category").value;
    
    document.getElementById("jpp_section").classList.add("d-none");
    document.getElementById("jpkk_section").classList.add("d-none");
    document.getElementById("multimedia_section").classList.add("d-none");
    document.getElementById("upload_section").classList.add("d-none");
    document.getElementById("butiran_section").classList.add("d-none");
    document.getElementById("sesi_section").classList.add("d-none");

    document.getElementById("input_description").removeAttribute("required");
    document.getElementById("input_sesi").removeAttribute("required");
    document.getElementById("attachment").removeAttribute("required");

    var isJawatan = false;
    if (kategori === "Jawatan JPP") {
        document.getElementById("jpp_section").classList.remove("d-none");
        isJawatan = true;
    } 
    else if (kategori === "Jawatan JPKK") {
        document.getElementById("jpkk_section").classList.remove("d-none");
        isJawatan = true;
    } 
    else if (kategori === "Jawatan Kelab Multimedia") {
        document.getElementById("multimedia_section").classList.remove("d-none");
        isJawatan = true;
    }

    if (isJawatan) {
        document.getElementById("sesi_section").classList.remove("d-none");
        document.getElementById("input_sesi").setAttribute("required", "required");
    } else if (kategori !== "") {
        document.getElementById("butiran_section").classList.remove("d-none");
        document.getElementById("input_description").setAttribute("required", "required");
    }
    
    var perlukanFail = ["Result Semester", "Kesalahan Disiplin", "Sijil Penyertaan / AJK", "Anugerah Khas"];
    if (perlukanFail.includes(kategori)) {
        document.getElementById("upload_section").classList.remove("d-none");
    }
}
</script>

<?php require 'footer.php'; ?>