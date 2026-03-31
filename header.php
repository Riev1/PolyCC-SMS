<?php
ob_start();
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Pengurusan Pelajar Pro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        
        body { 
            background-color: #f1f5f9; 
            font-family: 'Poppins', sans-serif; 
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        
        
        .fade-in-up {
            animation: fadeInUp 0.6s ease-out forwards;
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        
        .navbar-wrapper {
            padding: 15px 20px;
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        .navbar-gempak {
            background: rgba(15, 23, 42, 0.85) ; 
            backdrop-filter: blur(12px); 
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            transition: all 0.3s ease;
        }
        
        
        .navbar-brand { font-weight: 700; letter-spacing: 1px; }
        .navbar-brand i { transition: transform 0.4s ease; color: #3b82f6; }
        .navbar-brand:hover i { transform: rotate(20deg) scale(1.2); }

        
        .nav-item .nav-link {
            color: #cbd5e1 ;
            font-weight: 500;
            position: relative;
            padding: 8px 15px ;
            margin: 0 5px;
            transition: color 0.3s ease;
        }
        .nav-item .nav-link:hover, .nav-item .nav-link.active {
            color: #ffffff ;
        }
        .nav-item .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 3px;
            bottom: 0;
            left: 50%;
            background: linear-gradient(90deg, #3b82f6, #06b6d4);
            transition: all 0.3s ease;
            transform: translateX(-50%);
            border-radius: 5px;
        }
        .nav-item .nav-link:hover::after { width: 80%; }

        
        .btn-logout {
            background: linear-gradient(45deg, #ef4444, #f43f5e);
            border: none;
            border-radius: 50px;
            box-shadow: 0 4px 15px rgba(239, 68, 68, 0.4);
            transition: all 0.3s ease;
        }
        .btn-logout:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(239, 68, 68, 0.6);
            color: white ;
        }
    </style>
</head>
<body>

<div class="navbar-wrapper">
    <nav class="navbar navbar-expand-lg navbar-dark navbar-gempak container">
        <div class="container-fluid px-2">
            <a class="navbar-brand d-flex align-items-center" href="dashboard.php">
                <div class="bg-white p-2 rounded-circle me-2 shadow-sm d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                    <i class="fas fa-graduation-cap fs-4"></i>
                </div>
                PolyCC SMS
            </a>
            
            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <i class="fas fa-bars text-white fs-3"></i>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center mt-3 mt-lg-0">
                    <?php if(isset($_SESSION['user_id'])): ?>
                        
                        <li class="nav-item">
                            <a class="nav-link" href="dashboard.php"><i class="fas fa-home me-1"></i> Dashboard</a>
                        </li>
                        
                        <?php if($_SESSION['user_role'] === 'admin'): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="students.php"><i class="fas fa-users-cog me-1"></i> Directory</a>
                            </li>
                        <?php else: ?>
                            <li class="nav-item">
                                <a class="nav-link" href="view_record.php?id=<?php echo $_SESSION['user_id']; ?>"><i class="fas fa-id-card me-1"></i> My Profile</a>
                            </li>
                        <?php endif; ?>
                        
                        <li class="nav-item ms-lg-3 me-lg-3 my-2 my-lg-0">
                            <div class="d-flex align-items-center bg-white bg-opacity-10 rounded-pill px-3 py-1 border border-light border-opacity-25">
                                <i class="fas fa-user-circle fs-4 me-2 text-info"></i>
                                <span class="text-white fw-semibold small"><?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
                            </div>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link btn btn-logout text-white px-4 py-2" href="logout.php">
                                <i class="fas fa-power-off me-1"></i> Logout
                            </a>
                        </li>
                        
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link" href="login.php"><i class="fas fa-sign-in-alt me-1"></i> Login</a></li>
                        <li class="nav-item ms-lg-2"><a class="nav-link btn btn-primary text-white rounded-pill px-4" style="background: linear-gradient(45deg, #3b82f6, #06b6d4); border:none;" href="register.php"><i class="fas fa-user-plus me-1"></i> Register</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
</div>

<div class="container mb-5 flex-grow-1 fade-in-up">