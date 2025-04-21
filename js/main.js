// Mobile Navigation
document.addEventListener('DOMContentLoaded', function() {
    const hamburger = document.querySelector('.hamburger');
    const navLinks = document.querySelector('.nav-links');
    
    hamburger.addEventListener('click', function() {
        this.classList.toggle('active');
        navLinks.classList.toggle('active');
    });
    
    // Close mobile menu when clicking a link
    document.querySelectorAll('.nav-links a').forEach(link => {
        link.addEventListener('click', () => {
            hamburger.classList.remove('active');
            navLinks.classList.remove('active');
        });
    });
    
    // Simple testimonial slider
    const testimonials = [
        {
            quote: "ITAssistNow resolved our server issue in under an hour when no one else could. Lifesavers!",
            name: "Sarah Johnson",
            title: "CEO, TechForward Inc.",
            image: "images/client1.jpg"
        },
        {
            quote: "Their 24/7 support has been invaluable for our global operations. Highly recommended!",
            name: "Michael Chen",
            title: "CTO, Global Solutions",
            image: "images/client2.jpg"
        },
        {
            quote: "Professional, knowledgeable, and always available when we need them. Five stars!",
            name: "Emily Rodriguez",
            title: "IT Manager, Bright Media",
            image: "images/client3.jpg"
        }
    ];
    
    let currentTestimonial = 0;
    const testimonialElement = document.querySelector('.testimonial');
    
    function showTestimonial(index) {
        const testimonial = testimonials[index];
        testimonialElement.innerHTML = `
            <div class="quote">"${testimonial.quote}"</div>
            <div class="client">
                <img src="${testimonial.image}" alt="${testimonial.name}">
                <div>
                    <h4>${testimonial.name}</h4>
                    <p>${testimonial.title}</p>
                </div>
            </div>
        `;
    }
    
    // Initialize with first testimonial
    if (testimonialElement) {
        showTestimonial(currentTestimonial);
        
        // Auto-rotate testimonials every 5 seconds
        setInterval(() => {
            currentTestimonial = (currentTestimonial + 1) % testimonials.length;
            showTestimonial(currentTestimonial);
        }, 5000);
    }
    
    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            
            const targetId = this.getAttribute('href');
            if (targetId === '#') return;
            
            const targetElement = document.querySelector(targetId);
            if (targetElement) {
                window.scrollTo({
                    top: targetElement.offsetTop - 80,
                    behavior: 'smooth'
                });
            }
        });
    });
});