function toggleMessage1() {
    const checkbox1 = document.getElementById('checkbox1');
    const checkbox2 = document.getElementById('checkbox2');
    if (checkbox1.checked) {
        checkbox2.checked = false;
    }
}
function toggleMessage2() {
    const checkbox1 = document.getElementById('checkbox1');
    const checkbox2 = document.getElementById('checkbox2');
    if (checkbox2.checked) {
        checkbox1.checked = false;
    }
}



function Click() {
    let passwordField = document.getElementById("password1");
    let icon = document.getElementById("toggle-password");

    if (passwordField.type === "password") {
        passwordField.type = "text";
        icon.src = "https://cdn-icons-png.flaticon.com/512/158/158746.png"; // Icône œil barré
    } else {
        passwordField.type = "password";
        icon.src = "https://cdn-icons-png.flaticon.com/512/565/565655.png"; // Icône œil normal
    }
};