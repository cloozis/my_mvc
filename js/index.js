(function(){
    let navbarSideCollapse = document.querySelector(".navbar-toggler-icon");
    let navbar_collapse = document.querySelector(".navbar-collapse");
    let loginButton = document.querySelector("#loginButton");
    let loginField = document.querySelector("#loginFormControlInput");
    let passwordField = document.querySelector("#passwordFormControlInput");
    let loginModal = document.querySelector("#loginModal");

    loginButton.addEventListener("click", (e) => {
        let request = {
            auth: {
                login: loginField.value,
                password: passwordField.value,
            }
        }

        fetch('/', {
            method: 'POST',
            headers: {
            'Content-Type': 'application/json;charset=utf-8'
            },
            body: JSON.stringify(request)
        }).then(response => response.json())
        .then(data => {
            if(data.auth){
                loginModal.classList.remove("show");
                window.location.reload(true);
            }
        });
    });
})();
