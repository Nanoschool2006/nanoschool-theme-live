document.addEventListener('DOMContentLoaded', function () {
    var roleElement = document.getElementById('usrl');
    if (roleElement) {
        var roleText = roleElement.textContent;
        var elementsToUpdate = document.querySelectorAll('[data-role="sbusrl"]');
        
        elementsToUpdate.forEach(function (element) {
            element.innerHTML = element.innerHTML.replace(/sbusrl/g, roleText);
        });
    }
});
