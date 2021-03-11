/**timeOut sur le flash message **/
jQuery(document).ready(function($) {
    setTimeout(function() {
        $(".alert-success").fadeOut(500);
    }, 4000);
});

/**menu Hamburger */
const navSlide = () => {
    const burger = document.querySelector('.burger');
    const nav = document.querySelector('.nav-links');
    const navLinks = document.querySelectorAll('.nav-links li');

    burger.addEventListener('click', () => {
        //Toggle Nav
        nav.classList.toggle('nav-active');

        //Animate Links
        navLinks.forEach((link, index) => {
            // console.log(index);
            if (link.style.animation) {
                link.style.animation = '';
            } else {
                link.style.animation = `navLinkFade 0.5s ease forwards ${index / 7 + 0.3}s`;
            }
            //console.log(index / 7 + 0.3);
        });
        // burger animation
        burger.classList.toggle('toggle');
    });
}
navSlide();

