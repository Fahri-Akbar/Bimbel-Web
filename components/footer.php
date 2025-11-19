<?php
// components/footer.php
?>
        </main>
    </div>
</div>

<!-- JavaScript untuk interaktivitas -->
<script>
    // Toggle Sidebar Mobile
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('sidebar');
    const mobileMenuOverlay = document.getElementById('mobileMenuOverlay');
    
    if (sidebarToggle && sidebar && mobileMenuOverlay) {
        sidebarToggle.addEventListener('click', () => {
            sidebar.classList.toggle('-translate-x-full');
            mobileMenuOverlay.classList.toggle('hidden');
        });
        
        mobileMenuOverlay.addEventListener('click', () => {
            sidebar.classList.add('-translate-x-full');
            mobileMenuOverlay.classList.add('hidden');
        });
    }
    
    // User Dropdown Menu
    const userMenuButton = document.getElementById('userMenuButton');
    const userDropdown = document.getElementById('userDropdown');
    
    if (userMenuButton && userDropdown) {
        userMenuButton.addEventListener('click', (e) => {
            e.stopPropagation();
            userDropdown.classList.toggle('hidden');
        });
        
        // Close dropdown ketika klik di luar
        document.addEventListener('click', (e) => {
            if (!userMenuButton.contains(e.target) && !userDropdown.contains(e.target)) {
                userDropdown.classList.add('hidden');
            }
        });
    }
    
    // Close sidebar ketika resize window ke desktop
    window.addEventListener('resize', () => {
        if (window.innerWidth >= 1024) {
            sidebar?.classList.remove('-translate-x-full');
            mobileMenuOverlay?.classList.add('hidden');
        } else {
            sidebar?.classList.add('-translate-x-full');
        }
    });
</script>
</body>
</html>