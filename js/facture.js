$(document).ready(function () {
    $("#annuler").click(function (e) {
        e.preventDefault();

        window.location.href = 'panier.php';
    })
})
//permet de mettre la commande comme payer
$(document).ready(function () {
    $("#envoyer").click(function (e) {
        e.preventDefault();
        window.location.href = 'thanks.php';
        $.ajax({
            type: "GET",
            url: "php/set_payer.php",
            dataType: "json",
            success: function (response) {
                if (response.statu == 'ok') {
                    //permet d'envoyer le mail
            
		
                    $.ajax({
                        type: "GET",
                        url: "php/envoyerMail.php",
                        dataType: "json",
                        
                    });
                    //
                }
                else{
                    console.log(response.statu+"ici");
                }
            }
        });
    })
})
