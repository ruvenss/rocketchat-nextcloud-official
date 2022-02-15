document.addEventListener("DOMContentLoaded", function () {
    var elements = document.getElementsByClassName('messenger--add-members-info');
    console.log('Hello from JS');
    if (elements.length > 0) {
        setTimeout(function () {
            elements[0].classList.add('messenger--hidden');
        }, 7000);
    }

    var token = document.querySelector('input[name=rocketchat_token]').value;
    console.log(token);

    document.querySelector('iframe').addEventListener("load", function() {
        this.contentWindow.postMessage({
            event: 'login-with-token',
            loginToken: token,
        }, '*');
    });
});
