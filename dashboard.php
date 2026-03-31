<?php
require 'config.php';
require 'header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}


$role = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : 'student';
$user_id = $_SESSION['user_id'];
?>

<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center bg-white p-4 rounded-4 shadow-sm border-start border-5 border-primary">
            <div>
                <h2 class="fw-bold mb-0 text-dark">Welcome back, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h2>
                <p class="text-muted mb-0">
                    <span class="badge bg-<?php echo ($role == 'admin') ? 'danger' : 'success'; ?> px-3 py-2 mt-2">
                        <i class="fas fa-<?php echo ($role == 'admin') ? 'shield-alt' : 'user-graduate'; ?>"></i> 
                        <?php echo strtoupper($role); ?> ACCOUNT
                    </span>
                </p>
            </div>
            <div class="d-none d-md-block text-primary opacity-50">
                <i class="fas fa-university fa-4x"></i>
            </div>
        </div>
    </div>
</div>

<?php if ($role === 'admin'): ?>
    <?php
    $total_students = $conn->query("SELECT COUNT(*) as total FROM users WHERE role='student'")->fetch_assoc()['total'];
    $total_records = $conn->query("SELECT COUNT(*) as total FROM student_records")->fetch_assoc()['total'];
    ?>
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm rounded-4 bg-primary bg-gradient text-white h-100">
                <div class="card-body p-4 d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="text-uppercase fw-bold opacity-75">Total Students</h6>
                        <h1 class="display-4 fw-bold mb-0"><?php echo $total_students; ?></h1>
                    </div>
                    <i class="fas fa-users fa-4x opacity-50"></i>
                </div>
                <div class="card-footer bg-transparent border-0 pb-4 px-4">
                    <a href="students.php" class="btn btn-light text-primary fw-bold rounded-pill px-4">Manage Students <i class="fas fa-arrow-right ms-2"></i></a>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm rounded-4 bg-warning bg-gradient text-dark h-100">
                <div class="card-body p-4 d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="text-uppercase fw-bold opacity-75">Total Disciplinary/Merit Records</h6>
                        <h1 class="display-4 fw-bold mb-0"><?php echo $total_records; ?></h1>
                    </div>
                    <i class="fas fa-file-alt fa-4x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>

<?php else: ?>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-5 text-center">
                    <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-4" style="width: 100px; height: 100px;">
                        <i class="fas fa-user-graduate fa-3x text-success"></i>
                    </div>
                    <h3 class="fw-bold text-dark">Your Student Portal</h3>
                    <p class="text-muted fs-5 mb-4">Access your personal academic and disciplinary records easily securely.</p>
                    <a href="view_record.php?id=<?php echo $user_id; ?>" class="btn btn-success bg-gradient btn-lg rounded-pill px-5 shadow-sm">
                        <i class="fas fa-search me-2"></i> View My Records
                    </a>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php require 'footer.php'; ?>