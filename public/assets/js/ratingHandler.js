const stars = document.querySelectorAll('form input[type=radio][name=rating]');
const rForms = document.querySelectorAll('form[name=rating-form]');

rForms.forEach(formRating => {  
    stars.forEach(star => {
        star.addEventListener("click", function() {                  
            formRating.submit();
        })
    });
});
