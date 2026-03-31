<?php
require 'config.php';
require 'header.php';

// Keselamatan: Hanya Admin boleh akses page ini
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    echo "<div class='alert alert-danger shadow-sm mt-5'><i class='fas fa-ban me-2'></i> Akses Ditolak. Halaman ini khas untuk Admin sahaja.</div>";
    require 'footer.php';
    exit();
}

// 1. Tangkap nilai kelas yang dicari dari URL (jika ada)
$filter_class = isset($_GET['filter_class']) ? $_GET['filter_class'] : '';

// 2. Sediakan Query Database berdasarkan pilihan kelas
if ($filter_class !== '') {
    $stmt = $conn->prepare("SELECT id, name, email, id_number, class_name FROM users WHERE role='student' AND class_name = ? ORDER BY name ASC");
    $stmt->bind_param("s", $filter_class);
} else {
    $stmt = $conn->prepare("SELECT id, name, email, id_number, class_name FROM users WHERE role='student' ORDER BY name ASC");
}

$stmt->execute();
$result = $stmt->get_result();
?>

<style>
    /* =========================================================
       CSS MAGIS: KAD RESPONSIVE TELEFON BIMIT (ANTI-OVERFLOW)
       ========================================================= */
    @media (max-width: 768px) {
        /* 1. Paksa semua elemen jadual menjadi Block (Bukan lagi jadual) */
        .mobile-card-table, 
        .mobile-card-table thead, 
        .mobile-card-table tbody, 
        .mobile-card-table th, 
        .mobile-card-table td, 
        .mobile-card-table tr { 
            display: block; 
            width: 100%; 
        }
        
        /* 2. Sembunyikan Header Jadual di Telefon */
        .mobile-card-table thead tr { 
            display: none;
        }
        
        /* 3. Bentuk Kad Sebenar */
        .mobile-card-table tr { 
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            margin-bottom: 1.5rem;
            padding: 1rem;
            box-shadow: 0 10px 20px -5px rgba(0, 0, 0, 0.08);
        }
        
        /* 4. Baris Maklumat di Dalam Kad */
        .mobile-card-table td { 
            border: none;
            padding: 10px 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        /* Garisan pemisah nipis */
        .mobile-card-table td:not(:first-child):not(:last-child) {
            border-bottom: 1px solid #f1f5f9;
        }

        /* 5. BAHAGIAN 1: Profil (Avatar, Nama, Email) */
        .mobile-card-table td:first-child {
            justify-content: flex-start;
            border-bottom: 2px dashed #e2e8f0;
            padding-bottom: 15px;
            margin-bottom: 10px;
        }
        
        /* 6. Label Automatik di Telefon */
        .mobile-card-table td:nth-child(2)::before { content: "No. Matrik"; font-weight: 700; color: #64748b; font-size: 0.9rem; }
        .mobile-card-table td:nth-child(3)::before { content: "Kelas"; font-weight: 700; color: #64748b; font-size: 0.9rem; }
        
        /* 7. BAHAGIAN BUTANG ACTION */
        .mobile-card-table td:last-child {
            justify-content: center;
            padding-top: 15px;
        }
        .mobile-card-table td:last-child a {
            width: 100%;
            padding: 12px;
            border-radius: 12px;
            font-size: 1rem;
        }
        
        /* 8. HAPUSKAN LEBIHAN SCROLL KANAN */
        .table-responsive {
            overflow: visible !important;
            border: none !important;
        }
    }
</style>

<div class="card border-0 shadow-sm rounded-4 mb-5 mt-4 fade-in-up">
    <div class="card-header bg-white border-bottom p-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center rounded-top-4">
        <h4 class="mb-3 mb-md-0 fw-bold text-dark"><i class="fas fa-users-cog text-primary me-2"></i> Student Directory</h4>

        <form method="GET" action="students.php" class="w-100 w-md-auto">
            <div class="input-group shadow-sm border-primary rounded-3">
                <span class="input-group-text bg-light border-primary d-none d-md-block"><i class="fas fa-filter text-info"></i></span>
                <select name="filter_class" class="form-select border-primary" onchange="this.form.submit()">
                    <option value="">-- Senarai Semua Kelas --</option>
                    <optgroup label="Semester 1">
                        <option value="DIT1A" <?php if($filter_class == 'DIT1A') echo 'selected'; ?>>DIT1A</option>
                        <option value="DIT1B" <?php if($filter_class == 'DIT1B') echo 'selected'; ?>>DIT1B</option>
                    </optgroup>
                    <optgroup label="Semester 2">
                        <option value="DIT2A" <?php if($filter_class == 'DIT2A') echo 'selected'; ?>>DIT2A</option>
                        <option value="DIT2B" <?php if($filter_class == 'DIT2B') echo 'selected'; ?>>DIT2B</option>
                    </optgroup>
                    <optgroup label="Semester 3">
                        <option value="DIT3A" <?php if($filter_class == 'DIT3A') echo 'selected'; ?>>DIT3A</option>
                    </optgroup>
                    <optgroup label="Semester 4">
                        <option value="DIT4A" <?php if($filter_class == 'DIT4A') echo 'selected'; ?>>DIT4A</option>
                        <option value="DIT4B" <?php if($filter_class == 'DIT4B') echo 'selected'; ?>>DIT4B</option>
                    </optgroup>
                    <optgroup label="Semester 5">
                        <option value="DIT5A" <?php if($filter_class == 'DIT5A') echo 'selected'; ?>>DIT5A</option>
                    </optgroup>
                    <optgroup label="Semester 6 (Praktikal / L.I)">
                        <option value="DIT6A" <?php if($filter_class == 'DIT6A') echo 'selected'; ?>>DIT6A</option>
                        <option value="DIT6B" <?php if($filter_class == 'DIT6B') echo 'selected'; ?>>DIT6B</option>
                        <option value="DIT6C" <?php if($filter_class == 'DIT6C') echo 'selected'; ?>>DIT6C</option>
                        <option value="DIT6D" <?php if($filter_class == 'DIT6D') echo 'selected'; ?>>DIT6D</option>
                    </optgroup>
                </select>
                <?php if($filter_class !== ''): ?>
                    <a href="students.php" class="btn btn-danger"><i class="fas fa-times"></i></a>
                <?php endif; ?>
            </div>
        </form>
    </div>
    
    <div class="card-body p-3 p-md-0">
        <?php if($filter_class !== ''): ?>
            <div class="bg-light p-3 border-bottom text-center rounded-3 mb-3 mb-md-0 rounded-bottom-0">
                <span class="text-muted">Menunjukkan rekod pelajar untuk kelas: </span> 
                <span class="badge bg-primary fs-6 shadow-sm"><?php echo htmlspecialchars($filter_class); ?></span>
            </div>
        <?php endif; ?>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 mobile-card-table">
                <thead class="bg-light text-muted">
                    <tr>
                        <th class="ps-4">Student Info</th>
                        <th>ID Number</th>
                        <th>Class</th>
                        <th class="text-center pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>
                                <td class='ps-md-4 py-3'>
                                    <div class='d-flex align-items-center w-100'>
                                        <div class='bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-3 shadow-sm flex-shrink-0' style='width: 45px; height: 45px; font-weight: bold;'>
                                            ".strtoupper(substr($row['name'], 0, 1))."
                                        </div>
                                        <div style='min-width: 0; flex: 1;'>
                                            <h6 class='mb-0 fw-bold text-dark text-truncate'>".htmlspecialchars($row['name'])."</h6>
                                            <small class='text-muted d-block text-truncate'>".htmlspecialchars($row['email'])."</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class='badge bg-light text-dark border p-2 shadow-sm'>".htmlspecialchars($row['id_number'])."</span>
                                </td>
                                <td>
                                    <span class='badge bg-info bg-gradient p-2 shadow-sm'>".htmlspecialchars($row['class_name'])."</span>
                                </td>
                                <td class='text-center pe-md-4'>
                                    <a href='view_record.php?id=".$row['id']."' class='btn btn-sm btn-primary rounded-pill px-4 shadow-sm fw-bold'>
                                        <i class='fas fa-eye me-1'></i> View / Add Record
                                    </a>
                                </td>
                            </tr>";
                        }
                    } else {
                        echo "<tr>
                            <td colspan='4' class='text-center py-5'>
                                <i class='fas fa-search-minus fa-3x text-secondary opacity-50 mb-3 d-block'></i>
                                <h5 class='text-muted fw-bold'>Tiada rekod pelajar dijumpai</h5>
                                <p class='text-muted small'>Sila cuba kelas lain atau pastikan pelajar telah mendaftar.</p>
                            </td>
                        </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php 
$stmt->close();
require 'footer.php'; 
?>