(function () {
    if (typeof AOS !== 'undefined') {
        AOS.init({ duration: 900, once: true, offset: 80 });
    }
    const scrollTopButton = document.getElementById('scrollTop');
    if (!scrollTopButton) return;
    function toggleScrollTop() { scrollTopButton.classList.toggle('active', window.scrollY > 320); }
    window.addEventListener('scroll', toggleScrollTop, { passive: true });
    toggleScrollTop();
    scrollTopButton.addEventListener('click', function () { window.scrollTo({ top: 0, behavior: 'smooth' }); });
}());