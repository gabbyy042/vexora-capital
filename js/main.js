// Main JavaScript - VEXORA CAPITAL

// DOM Ready
document.addEventListener('DOMContentLoaded', function() {
    console.log('VEXORA CAPITAL Platform Ready');
    
    // Initialize animations
    initAnimations();
    
    // Handle forms
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', handleFormSubmit);
    });
});

// Form handler
function handleFormSubmit(e) {
    e.preventDefault();
    
    const form = e.target;
    const formData = new FormData(form);
    const action = form.getAttribute('data-action') || form.getAttribute('action');
    
    console.log('Submitting form to:', action);
    
    // Disable submit button
    const submitBtn = form.querySelector('button[type="submit"]');
    if (submitBtn) submitBtn.disabled = true;
    
    // For demo purposes, just show success message
    // In production, actually POST to backend
    setTimeout(() => {
        alert('✅ Form submitted successfully!');
        if (submitBtn) submitBtn.disabled = false;
        form.reset();
    }, 500);
}

// Initialize animations
function initAnimations() {
    // Observe elements for fade-in animation on scroll
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-fade-in');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });
    
    document.querySelectorAll('.card').forEach(card => {
        observer.observe(card);
    });
}

// Copy to clipboard
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        alert('✅ Copied to clipboard!');
    }).catch(() => {
        alert('Failed to copy');
    });
}

// Format money
function formatMoney(amount) {
    return '$' + parseFloat(amount).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
}

// Parse URL params
function getUrlParam(param) {
    const params = new URLSearchParams(window.location.search);
    return params.get(param);
}

console.log('VEXORA CAPITAL JavaScript Loaded');