// Press '/' anywhere to focus the search input; Escape to clear and blur
document.addEventListener('keydown', function (e) {
    var input = document.getElementById('pseudo');
    if (!input) return;
    if (e.key === '/' && document.activeElement !== input) {
        e.preventDefault();
        input.focus();
    }
    if (e.key === 'Escape' && document.activeElement === input) {
        input.value = '';
        input.blur();
    }
});

// Click or Enter/Space on the username heading to copy it to clipboard
var usernameHeading = document.getElementById('username-heading');
if (usernameHeading) {
    function copyUsername() {
        var text = usernameHeading.textContent.trim();
        navigator.clipboard.writeText(text).then(function () {
            usernameHeading.classList.add('copied');
            setTimeout(function () {
                usernameHeading.classList.remove('copied');
            }, 2000);
        });
    }

    usernameHeading.addEventListener('click', copyUsername);
    usernameHeading.addEventListener('keydown', function (e) {
        if (e.key === 'Enter' || e.key === ' ') {
            e.preventDefault();
            copyUsername();
        }
    });
}
