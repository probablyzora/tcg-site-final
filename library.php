<?php
try {
    $pdo = new PDO('mysql:host=localhost;dbname=librairie', 'root', ''); // connexion a la base de donnee librairie
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo 'Connection reussie';
    $query = $pdo->query('SELECT * FROM livres');
    $livres = $query->fetchAll(); // prendre tous les données dans la table livre
    echo "<ul>";
    foreach ($livres as $livre) {
        echo "<li>";
        echo $livre["titre"] . " - " . $livre["auteur"] . " - " . $livre["annee_publication"];
        echo '<li>';
    }
    echo '</ul>';
} catch (PDOException $e) {
    echo 'error' . $e->getMessage();
} // afficher erreur 

// livres apres 2000
$req = $pdo->prepare('SELECT * FROM livres WHERE annee_publication > :annee ORDER BY titre ASC'); // prendre tous les livres dont l'annee_pub est superieure a annee (2000), par titre ascendant
$req->execute([
    'annee' => '2000',
]);
$livresRecents = $req->fetchAll(PDO::FETCH_ASSOC);
echo '<h1> Livres publiés apres 2000 </h1>';
echo '<ul>';
foreach ($livresRecents as $livreRecent) {
    echo '<li>';
    echo $livreRecent['titre'] . ' - ' . $livreRecent['auteur'] . ' - ' . $livreRecent['annee_publication'];
    echo '<li>';
}
echo '</ul>';
// ajouteur un livre
$request = $pdo->prepare('INSERT INTO livres (titre,auteur,annee_publication,disponible) VALUES (:titre, :auteur, :annee, :disponible )');
$request->execute([
    'titre' => 'La Philosophie pour les Nuls',
    'auteur' => 'Christian Godin',
    'annee' => '2006',
    'disponible' => 1
]);
$id = $pdo->lastInsertId();
echo 'Nouvel id ajouté : ' . $id;
echo '<br>';

// modification disponibilité livre
$update = $pdo->exec('UPDATE livres SET disponible = 0 WHERE id = 6');
echo 'Nombres de lignes modifiées : ' . $update;

// supprimer un livre
$delete = $pdo->prepare('DELETE FROM livres WHERE id = :id');
$delete->execute([
    'id' => '7'
]);


?>