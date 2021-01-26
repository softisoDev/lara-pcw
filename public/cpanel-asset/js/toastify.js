function run_toastify(text, type, position = "right", gravity = "top", duration = 4000) {
    let customToast = Toastify({
        text: text,
        type: type,
        duration: duration,
        newWindow: false,
        close: true,
        gravity: gravity, // `top` or `bottom`
        position: position, // `left`, `center` or `right`
        /*backgroundColor: "#000",*/
        stopOnFocus: true, // Prevents dismissing of toast on hover
        onClick: function () {
        } // Callback after click
    });

    customToast.showToast();
}
