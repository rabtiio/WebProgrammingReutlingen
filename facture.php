<?php
session_start();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/header2.css">
    <link rel="stylesheet" href="css/facture.css">
    <title>Votre Facture</title>
    <script src="https://kit.fontawesome.com/82e270d318.js" crossorigin="anonymous"></script>
</head>

<body>

        <?php include('php/header2.php') ?>
        <div class="facture">
                <?php
                $recup_commande = $bdd->prepare("SELECT img,code,quantite,nom,prix,reference from produit inner join commande on produit.code=commande.code_produit where id_user='{$_SESSION['id_user']}' and payer = 1;");
                $recup_commande->execute();
                $commande = $recup_commande->fetchAll(PDO::FETCH_ASSOC);
                
                if ($recup_commande->rowCount() > 0) {
    echo '<table class="cart-table">'; // Added a table tag for better structure
    echo '<thead>';
    echo '<tr>';
    echo '<th>Image</th>';
    echo '<th>Name</th>';
    echo '<th>Quantity</th>';
    echo '<th>Code</th>';
    echo '<th>Price Excluding taxes (20% promotion)</th>';
    echo '<th>Unit Price incl. tax</th>';
    echo '<th>Total Product incl. tax</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';

    $prixtotHT = 0;
    $prixtot = 0;
    for ($i = 0; $i < $recup_commande->rowCount(); $i++) {
        echo '<tr class="produit">';
        echo '<td><img src="' . $commande[$i]['img'] . '" width="200" height="200" alt="' . htmlspecialchars($commande[$i]['nom']) . '"></td>';
        echo '<td>' . htmlspecialchars($commande[$i]['nom']) . '</td>';
        echo '<td>
                <div class="quantite">
                    <input type="text" size="5" id="text_' . htmlspecialchars($commande[$i]["code"]) . '" readonly="readonly" class="input_btn" value="' . htmlspecialchars($commande[$i]["quantite"]) . '"/>
                </div>
              </td>';
        echo '<td>' . htmlspecialchars($commande[$i]["code"]) . '</td>';
        echo '<td>' . number_format($commande[$i]["prix"] * (1 - 20 / 100), 2) . ' €</td>'; // Format to 2 decimal places
        echo '<td>' . number_format($commande[$i]['prix'], 2) . ' €</td>'; // Format to 2 decimal places
        echo '<td class="totalprix"><b>' . number_format($commande[$i]["prix"] * $commande[$i]['quantite'], 2) . ' €</b></td>'; // Format to 2 decimal places
        echo '</tr>';
        $prixtotHT += $commande[$i]["prix"] * $commande[$i]['quantite'] * (1 - 20 / 100) * (100 - $commande[$i]['quantite'])/100  ;
        $prixtot += $commande[$i]["prix"] * $commande[$i]['quantite'] * (100 - $commande[$i]['quantite'])/100;
        
    }
    echo '</tbody>';
    echo '<tfoot>'; // Use tfoot for summary rows
    echo '<tr>
            <td colspan="6"></td>
            <td class="total">' . number_format($prixtotHT, 2) . ' € HT with reduction</td>
          </tr>';
    echo '<tr>
            <td colspan="6"></td>
            <td class="total"><b>' . number_format($prixtot, 2) . ' € TTC with reduction</b></td>
          </tr>';
    echo '</tfoot>';
    echo '</table>';
    echo '<hr>'; // Horizontal rule after the table

    echo '
    <h3>Choose shipment method</h3>
    <form id="livraison_form">
        <label>
            <input type="radio" name="livraison" value="1" checked> Standard shipment : DPD (1 €)
        </label><br>
        <label>
            <input type="radio" name="livraison" value="20"> DHL Shipment (20 €)
        </label><br>
        <label>
            <input type="radio" name="livraison" value="64"> DHL Express (64 €)
        </label>
    </form>

    <h3>Total TTC + Shipment : <span id="total_with_shipping">' . number_format($prixtot + 1, 2) . ' €</span></h3>'; // Initial value with default shipping
    ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get the base total price from PHP (this value is fixed after page load)
            const baseTotalPrice = <?php echo json_encode($prixtot); ?>;

            // Get references to the shipping radio buttons and the total display span
            const shippingOptions = document.querySelectorAll('input[name="livraison"]');
            const totalWithShippingSpan = document.getElementById('total_with_shipping');

            // Function to update the total price
            function updateTotalPrice() {
                let selectedShippingCost = 0;
                // Find the currently selected shipping option
                shippingOptions.forEach(option => {
                    if (option.checked) {
                        selectedShippingCost = parseFloat(option.value);
                    }
                });

                const newTotal = baseTotalPrice + selectedShippingCost;
                totalWithShippingSpan.textContent = newTotal.toFixed(2) + ' €'; // Format to 2 decimal places
            }

            // Add event listeners to all shipping radio buttons
            shippingOptions.forEach(option => {
                option.addEventListener('change', updateTotalPrice);
            });

            // Call the function once initially to ensure the correct price is shown if the default is not 1€
            updateTotalPrice();
        });
    </script>                    
                <div class="validation">
                    <button id="annuler">Cancel</button>
                    <button id="envoyer">Send</button>
                </div>
                <div class="message_info"></div>
        </div>
        </div>

        <?php include('php/footer.php') ?>

    <?php
    } else {
        echo '<h2> Empty cart, please leave this page</h2>';
    }
    ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="js/facture.js"></script>
</body>

</html>