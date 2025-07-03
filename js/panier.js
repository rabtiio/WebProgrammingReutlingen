    $(document).ready(function () {
    $("#annuler").click(function (e) {
        e.preventDefault();
        $.ajax({
            type: "GET",
            url: "php/annul_commande.php",
            dataType: "json",
            success: function (response) {
                if (response.statue == 'ok') {
                    window.location.href = "panier.php";
                }
            }
        });
    })
})

$(document).ready(function () {
    $("#valider").click(function (e) {
        e.preventDefault();
        idErreur = document.getElementsByClassName("erreur");

        $.ajax({
            type: "GET",
            url: "php/verif_utilisateur_panier.php",
            dataType: "json",
            success: function (response) {
                if (response.xd == 'ok') {
                    window.location.href = "facture.php";
                }
                if (response.xd == 'rate') {
                    $("#erreur").html("Vous n'êtes pas connecté, veuillez le faire <a href='connexion.php'>ici</a> pour poursuivre votre achat.");
                }
            }
        });
    })
})


$(document).ready(function () {
    $(".bg_none > button").click(function (e) {
        e.preventDefault();
        var Button = $(this).attr('id');


        $.ajax({
            type: "GET",
            url: "php/suppLignePanier.php",
            data: {
                "id": Button
            },
            dataType: "json",
            success: function (response) {
                if (response.status == 'ok') {
                    location.reload();
                }
                else {
                    console.log(response.status)
                }
            }
        });
    })
})

$(document).ready(function () {
    $(".change_quantity > button").click(function (e) {
        e.preventDefault();

        idButton = this.getAttribute('id');
        TrucId = document.getElementById(idButton).parentElement;
        Pinput = TrucId.children[1];
        tableau = TrucId.parentElement.parentElement;

        $.ajax({
            type: "GET",
            url: "php/incrementer.php",
            data: {
                "id": idButton,
                "pinput": parseInt(Pinput.value)
            },
            dataType: "json",
            success: function (response) {
                if (response.stat == 'ok') {
                    console.log(response.stat4);
                    if (response.stat2 == 'plus') {
                        if ((parseInt(response.stat4)) == 1) {
                            Pinput.value = parseInt(Pinput.value) + 1;
                            this.disabled = true;

                        } else {
                            this.disabled = false;
                            Pinput.value = parseInt(Pinput.value) + 1;
                        }
                    }
                    if (response.stat2 == 'minus') {
                        if (parseInt(Pinput.value) == 1) {
                            tableau.remove();
                            console.log('chdvbfd');
                            location.reload();
                        }
                        this.disabled = false;
                        Pinput.value = parseInt(Pinput.value) - 1;

                    }
                }
            }
        });
    })
})

$(document).ready(function () {
    // Désactiver le bouton valider au début
    $("#valider").prop("disabled", true);

    // Activer/désactiver en fonction de la checkbox
    $("#option1").change(function () {
        if ($(this).is(":checked")) {
            $("#valider").prop("disabled", false);
        } else {
            $("#valider").prop("disabled", true);
        }
    });
});