<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

<?php
$jsonData = '{
    "status": "OK",
    "message": "Fiche récupérée avec succès",
    "champNames": ["id", "cpi", "fridolin", "état", "paiment", "serviceId", "createdAt", "updatedAt", "état1", "paiment1"]
}';

$data = json_decode($jsonData);

echo '<div class="max-w-md mx-auto mt-10">';

if ($data !== null && isset($data->champNames) && is_array($data->champNames)) {
    
    echo '<form id="myForm" class="bg-white p-8 rounded-md shadow-md">';
    echo '<div class="mb-4">';
    echo '<label class="text-lg text-gray-800">' . htmlspecialchars($data->message) . '</label>';
    echo '</div>';
    
    foreach ($data->champNames as $index => $champName) {
        $fieldName = 'champ_' . $index;
        
        if (!in_array($champName, ["id", "serviceId", "updatedAt"])) {
            echo '<div class="mb-4">';
            echo '<label class="text-lg text-gray-800 uppercase">' . htmlspecialchars($champName) . ' :</label>';
            
            if ($champName === "createdAt") {
                echo '<input type="date" id="' . $fieldName . '" name="' . $fieldName . '" class="w-full mt-2 border-b-2 border-gray-300 focus:outline-none focus:border-blue-500">';
            } else {
                echo '<input type="text" id="' . $fieldName . '" name="' . $fieldName . '" class="w-full mt-2 border-b-2 border-gray-300 focus:outline-none focus:border-blue-500">';
            }
            
            echo '</div>';
        }
    }

    echo '<div class="mt-6">';
    echo '<button type="button" id="btnaddfiche" class="px-4 py-2 bg-blue-500 text-white rounded-md cursor-pointer">Enregistrer</button><input type="submit" value="Soumettre" class="px-4 py-2 bg-blue-500 text-white rounded-md cursor-pointer">';
    echo '</div>';
    echo '</form>';
} else {
    echo '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-md mt-4" role="alert">';
    echo '<strong class="font-bold">Erreur!</strong> Les données JSON ne sont pas valides.';
    echo '</div>';
}

echo '</div>';



?>
<script>
    var jsonData = {
        "status": "OK",
        "message": "Fiche récupérée avec succès",
        "champNames": ["id", "cpi", "fridolin", "état", "paiment", "serviceId", "createdAt", "updatedAt", "état1", "paiment1"]
    };

    document.getElementById("myForm").addEventListener("submit", function (event) {
        event.preventDefault(); // Empêcher le comportement par défaut du formulaire

        // Créer un objet JavaScript pour stocker les paires champ-valeur
        var formDataObject = {};

        // Parcourir chaque élément du formulaire
        var formElements = this.elements;
        for (var i = 0; i < formElements.length; i++) {
            var element = formElements[i];

            // Ignorer les boutons de soumission
            if (element.type !== 'submit') {
                // Récupérer l'ID ou le Name de chaque champ
                var fieldName = element.id || element.name;

                // Remplacer la clé par le nom du champ correspondant
                var fieldIndex = fieldName.split('_')[1]; // Récupérer l'index numérique
                if (fieldIndex !== undefined) {
                    fieldName = jsonData.champNames[fieldIndex];
                }

                // Ajouter la paire champ-valeur à l'objet
                formDataObject[fieldName] = element.value;
            }
        }

        // Afficher les données dans la console
        console.log("Vous avez tapé :", formDataObject);
    });
</script>

<script>
    var jsonData = {
        "status": "OK",
        "message": "Fiche récupérée avec succès",
        "champNames": ["id", "cpi", "fridolin", "état", "paiment", "serviceId", "createdAt", "updatedAt", "état1", "paiment1"]
    };

    document.getElementById("btnaddfiche").addEventListener("click", function () {
        // Créer un objet JavaScript pour stocker les paires champ-valeur
        var formDataObject = {};

        // Parcourir chaque élément du formulaire
        var formElements = document.getElementById("myForm").elements;
        for (var i = 0; i < formElements.length; i++) {
            var element = formElements[i];

            // Ignorer les boutons de soumission
            if (element.type !== 'button') {
                // Récupérer l'ID ou le Name de chaque champ
                var fieldName = element.id || element.name;

                // Remplacer la clé par le nom du champ correspondant
                var fieldIndex = fieldName.split('_')[1]; // Récupérer l'index numérique
                if (fieldIndex !== undefined) {
                    fieldName = jsonData.champNames[fieldIndex];
                }

                // Ajouter la paire champ-valeur à l'objet
                formDataObject[fieldName] = element.value;
            }
        }

        // Afficher les données dans la console
        console.log("Vous avez tapé :", formDataObject);
    });
</script>

