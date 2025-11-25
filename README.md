# Tâches à accomplir

- **Dans l'entité**
    - Modifier l'entité `Produit` pour ajouter un champ `actif` de type boolean avec une valeur par défaut à `true`.

    - Modifier l'entité `Produit` pour ajouter une relation ManyToOne avec l'entité `Categorie`.

    - Modifier l'entité `Produit` pour ajouter une contrainte d'unicité sur le champ `code`.

    - Mettre à jour la base de données avec une migration.

- **Dans le formulaire**
    - Modifier le formulaire `ProduitType` pour inclure le champ `code` et `categorie`.
    - Le champ `code` doit être `obligatoire` et avoir une longueur maximale de `10` caractères.
    - Ajouter un message d'aide au champ `code` indiquant : "Le code doit être unique".
- **Dans la vue**
    - Mettre à jour la vue `index produit` pour afficher le champ `actif` et `categorie`  dans la page d'affichage des produits.
    - Ajouter un bouton dans la vue `index produit` permettant d'ajouter un nouveau produit.
    - Ajouter les deux champs `code` et `categorie` dans la vue du formulaire `new produit` 
    - Ajouter un bouton dans la vue `new produit` permettant de valider le formulaire

- **Dans le controlleur**
    - Mettre en place une redirection vers la page de la liste des produits après l'ajout d'un nouveau produit.
    - Créer une méthode dans le contrôleur `ProduitController` qui récupère le produit avec l'ID 1 et dumpe le résultat à l'écran.
    - Créer une méthode dans le contrôleur `ProduitController` pour envoyer un email listant tous les produits actifs de la catégorie `categorie1` en utilisant la méthode 'sendToTechnique' du service `EmailService`.


- **Comment rajouter une fonctionnalité permettant d’archiver un produit**