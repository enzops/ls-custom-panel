<?php

function newInvoiceJson($shopName, $employee, $customer, $products, $price)
{
    $date = date('l jS \of F Y h:i:s A');
    $timestamp = date("c", strtotime("now"));

    $json_data = [
        // Message
        "content" => "",

        // Username
        "username" => "$shopName",

        // Avatar URL.
        // Uncoment to replace image set in webhook
        "avatar_url" => "https://sanctu.noroute.fr/ems/img/ems_sanctuary_white.png",

        // Text-to-speech
        "tts" => false,

        // File upload
        // "file" => "",

        // Embeds Array
        "embeds" => [
            [
                // Embed Title
                "title" => "🧾 New invoice",

                // Embed Type
                "type" => "rich",

                // Embed Description
                "description" => "Une nouvelle facture est disponible.",

                // URL of title link
                "url" => '',

                // Timestamp of embed must be formatted as ISO8601
                "timestamp" => $timestamp,

                // Embed left border color in HEX
                "color" => hexdec("2A9FD6"),

                // Footer
                "footer" => [
                    "text" => "Wqnted#9745",
                    "icon_url" => "https://secure.gravatar.com/avatar/01b9050917da6448445eab81fc65ee2b.jpg?size=375"
                ],

                // Image to send
                "image" => [
                    "url" => ""
                ],

                // Thumbnail
                //"thumbnail" => [
                //    "url" => "https://ru.gravatar.com/userimage/28503754/1168e2bddca84fec2a63addb348c571d.jpg?size=400"
                //],

                // Author
                "author" => [
                    "name" => "$shopName",
                    "url" => "https://sanctu.noroute.fr/ems/"
                ],

                // Additional Fields array
                "fields" => [
                    [
                        "name" => "👨‍💼 Employé",
                        "value" => "$employee",
                        "inline" => false
                    ],
                    [
                        "name" => "🧍‍♂️ Client",
                        "value" => "$customer",
                        "inline" => false
                    ],
                    [
                        "name" => "🛒 Produits",
                        "value" => "",
                        "inline" => false
                    ],
                    [
                        "name" => "📅 Fait le",
                        "value" => "$date",
                        "inline" => false
                    ],
                    [
                        "name" => "💵 Total",
                        "value" => "$price $",
                        "inline" => false
                    ]
                ]
            ]
        ]
    ];

    // Boucle à travers les produits
    foreach ($products as $product) {
        $product_name = $product['nom_produit'];
        $product_price = $product['prix_produit'];
        $product_quantity = $product['quantity'];

        // Formatage de l'information du produit
        $product_info = "$product_name - Prix: $product_price $ - Quantité: $product_quantity";

        // Ajouter chaque produit à la valeur du champ des produits
        $json_data['embeds'][0]['fields'][2]['value'] .= "- $product_info\n";
    }

    $json_data = json_encode($json_data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

    return $json_data;
}
;