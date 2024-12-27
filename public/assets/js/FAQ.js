// Ne s'exécute que sur la page contact
if (document.body.getAttribute('data-page') === 'contact') {
    const faqQuestions = document.querySelectorAll('.faq-question');
        
    faqQuestions.forEach(question => {
        question.addEventListener('click', () => {
            const answer = question.nextElementSibling;
            const icon = question.querySelector('.faq-icon');
            
            // Toggle active class
            question.classList.toggle('active');
            answer.classList.toggle('active');
            
            // Adjust max-height for smooth animation
            if (answer.classList.contains('active')) {
                answer.style.maxHeight = "fit-content";
            } else {
                answer.style.maxHeight = 0;
            }
        });
    });
}