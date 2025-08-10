</main>

<!-- Footer -->
    <footer class="footer">
        <p>&copy; 2025 Your Dashboard. All rights reserved.</p>
        <div>
            <a href="#">Privacy Policy</a>
            <a href="#">Terms of Service</a>
        </div>
    </footer>
</div>
    <script>
        document.getElementById('navbarToggle').onclick = function() {
            var navbarMenu = document.getElementById('navbarMenu');
            navbarMenu.classList.toggle('active');
        };

        // Close sub-menus when clicking outside
        document.addEventListener('click', function(event) {
            var subMenus = document.querySelectorAll('.sub-menu');
            subMenus.forEach(function(menu) {
                if (!menu.parentNode.contains(event.target)) {
                    menu.style.display = 'none';
                }
            });
        });

        // Toggle sub-menu display on click for mobile/tablet
        document.querySelectorAll('header .menu > li > a').forEach(function(link) {
            link.addEventListener('click', function(event) {
                if (window.innerWidth <= 768) { // Apply only on smaller screens
                    var subMenu = this.nextElementSibling;
                    if (subMenu && subMenu.classList.contains('sub-menu')) {
                        event.preventDefault(); // Prevent default link navigation
                        if (subMenu.style.display === 'block') {
                            subMenu.style.display = 'none';
                        } else {
                            // Close other open sub-menus
                            document.querySelectorAll('header .sub-menu').forEach(function(otherMenu) {
                                if (otherMenu !== subMenu) {
                                    otherMenu.style.display = 'none';
                                }
                            });
                            subMenu.style.display = 'block';
                        }
                    }
                }
            });
        });

    </script>

<script src="/js/debug/pretty.js"></script>
<script>{{{script}}}</script>
<script src="/{{{model}}}/js/script.js"></script>
</body>
</html>