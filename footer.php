</div> <style>
    .footer-gempak {
        background: #0f172a; 
        color: #94a3b8;
        position: relative;
        overflow: hidden;
        margin-top: auto;
    }
    
    
    .footer-gempak::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(90deg, #3b82f6, #06b6d4, #10b981);
    }

    .footer-title {
        color: #ffffff;
        font-weight: 600;
        letter-spacing: 1px;
        margin-bottom: 20px;
    }

 
    .footer-link {
        color: #94a3b8;
        text-decoration: none;
        transition: all 0.3s ease;
        display: inline-block;
    }
    .footer-link:hover {
        color: #06b6d4;
        transform: translateX(8px); 
    }

    
    .social-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.05);
        color: #ffffff;
        text-decoration: none;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        margin-right: 10px;
    }
    .social-icon:hover {
        background: #3b82f6;
        color: #ffffff;
        transform: translateY(-5px) rotate(8deg) scale(1.1); /* Melantun dan pusing sedikit */
        box-shadow: 0 5px 15px rgba(59, 130, 246, 0.4);
    }
</style>

<footer class="footer-gempak pt-5 pb-3 mt-5 shadow-lg">
    <div class="container">
        <div class="row">
            <div class="col-lg-5 col-md-6 mb-4 mb-lg-0">
                <h5 class="footer-title d-flex align-items-center">
                    <i class="fas fa-graduation-cap fs-4 text-info me-2"></i> PolyCC SMS
                </h5>
                <p class="small lh-lg">
                    Sistem Pengurusan Pelajar yang direka khas untuk memudahkan pemantauan akademik, disiplin, dan penglibatan kokurikulum pelajar secara digital dan efisien.
                </p>
                <div class="mt-4">
                    <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-youtube"></i></a>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-4 mb-lg-0">
                <h5 class="footer-title">Pautan Pantas</h5>
                <ul class="list-unstyled mb-0">
                    <li class="mb-2"><a href="dashboard.php" class="footer-link"><i class="fas fa-angle-right me-2 small"></i> Dashboard Utama</a></li>
                    <?php if(isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                        <li class="mb-2"><a href="students.php" class="footer-link"><i class="fas fa-angle-right me-2 small"></i> Direktori Pelajar</a></li>
                    <?php endif; ?>
                    <li class="mb-2"><a href="#" class="footer-link"><i class="fas fa-angle-right me-2 small"></i> Terma & Syarat</a></li>
                    <li class="mb-2"><a href="#" class="footer-link"><i class="fas fa-angle-right me-2 small"></i> Dasar Privasi</a></li>
                </ul>
            </div>

            <div class="col-lg-4 col-md-12">
                <h5 class="footer-title">Hubungi Kami</h5>
                <ul class="list-unstyled mb-0 small lh-lg">
                    <li class="mb-3 d-flex text-white">
                        <i class="fas fa-map-marker-alt text-info mt-1 me-3 fs-5"></i>
                        <span>Politeknik Besut, Terengganu, Malaysia.</span>
                    </li>
                    <li class="mb-3 d-flex text-white">
                        <i class="fas fa-envelope text-info mt-1 me-3 fs-5"></i>
                        <span>admin@polycc.edu.my</span>
                    </li>
                    <li class="mb-3 d-flex text-white">
                        <i class="fas fa-phone-alt text-info mt-1 me-3 fs-5"></i>
                        <span>+60 9-123 4567</span>
                    </li>
                </ul>
            </div>
        </div>

        <div class="row align-items-center mt-4 border-top border-secondary border-opacity-25 pt-4">
            <div class="col-md-6 text-center text-md-start">
                <p class="small mb-0">&copy; <?php echo date('Y'); ?> PolyCC SMS System. All Rights Reserved.</p>
            </div>
            <div class="col-md-6 text-center text-md-end mt-2 mt-md-0">
                <p class="small mb-0 text-muted">Dicipta dengan <i class="fas fa-heart text-danger mx-1"></i> untuk kecemerlangan.</p>
            </div>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>