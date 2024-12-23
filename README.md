J'ai choisi de créer une entité EnergyDrink car ce sont les produits de mon projet NextJS

J'ai ajouté dans mon entité les propriétés de mes boissons (nom, image..)

J'ai créer ma base de données

J'ai ensuite lancer et exécuter mes migrations

Voici la requete GET pour visualiser la BDD : http://127.0.0.1:8000/api/energy-drinks

J'ai ensuite générer un contrôleur avec make:crud 

J'ai ensuite créer des fixtures pour que ma base de données ne soit pas vide

J'ai chargé les fixtures

Dans le controleur j'ai créer une méthode POST qui ajoute des energy drinks avec leur nom et leur image dans la BDD

J'ai fait la requete http://127.0.0.1:8000/api/energy-drinks mais cette fois ci en POST dans Postman et j'ai rajouté un form-data avec un nom et une image en file
 
J'ai ensuite implémenter les méthodes PUT et PATCH pour la modification des données existantes.

J'ai ensuite testé les requetes PUT et PATCH avec Postman en renseignant les id des entités.

J'ai créé la méthode DELETE, j'ai ensuite supprimer l'energy drinks avec l'id  = 7 avec Postman.

J'ai ensuite installé le module JWT, j'ai modifié mon fichier security.yaml, j'ai généré une paire de clé publique et privé

j'ai créer mon endpoint dans mon controleur et j'ai enfin créer un token avec Postman

pass phrase : arnold

{
    "username": "root",
    "password": "root"
}

curl -X POST http://127.0.0.1:8000/api/login \
-H "Content-Type: application/json" \
-d '{
    "username": "root",
    "password": "root"
}'
