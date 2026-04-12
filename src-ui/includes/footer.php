<div class="all-rights-reserved">
    <div>
        <p style="color:#F2C94C; font-weight:bold; margin-top:20px">@Tourist Attraction Finder</p>
        <p style="color:white; margin-top:40px">2026 TAF. All rights reserved. l Terms & Conditions I Privacy Policy</p>
    </div>
</div>

<script src="../assets/js/app.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    AOS.init();
    document.querySelectorAll('.smooth-link').forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault();
            document.body.classList.add('fade-out');
            setTimeout(() => {
                window.location.href = this.href;
            }, 500);
        });
    });
</script>
