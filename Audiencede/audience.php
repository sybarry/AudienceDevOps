<?php
require 'connexpdo.inc.php';

// Connexion à BDD
$db = "audience";
$pdo = connexpdo($db);

// Récupérer dans $audience (type entier) le numéro d'audience du formulaire soumis avec le bouton "EXTRAIRE".
// Fixer $audience à 1 si le formulaire est soumis avec le bouton "MISE A JOUR" ou au premier chargement de la page.
// Résultat attendu pour convocation n°1 :

$audience = 1;
if (isset($_POST["extraire"])) {
    $audience = (int) $_POST["audience"];
}

// Extraire la table PERSONNES sous la forme d'un tableau associatif (ID => NOM) dénommé $personnes.
// Résultat attendu pour convocation n°1 :

$personnes = [];
$query = "SELECT * FROM PERSONNES";
$stt = $pdo->query($query);
$effectif = $stt->fetchAll(PDO::FETCH_NUM);
foreach ($effectif as $p) {
    $personnes[$p[0]] = $p[1];
}

// Extraire les NOMs des ABSENTS à l'audience sous la forme d'un tableau indexé dénommé $absents.
// Résultat attendu pour convocation n°1 :

$absents = [];
$query = "SELECT ID_PERSONNE FROM ABSENTS WHERE AUDIENCE=:audience";
$stt = $pdo->prepare($query);
$stt->execute([
    ':audience' => $audience
]);
$absents_ids = $stt->fetchAll(PDO::FETCH_NUM);
foreach ($absents_ids as $a) {
    $absents[] = $personnes[$a[0]];
}

// Extraire les NOMs des EXEMPTS de l'audience sous la forme d'un tableau indexé dénommé $exempts.
// Résultat attendu pour convocation n°1 :

$exempts = [];
$query = "SELECT ID_PERSONNE FROM EXEMPTS WHERE AUDIENCE=:audience";
$stt = $pdo->prepare($query);
$stt->execute([
    ':audience' => $audience
]);
$exempts_ids = $stt->fetchAll(PDO::FETCH_NUM);
foreach ($exempts_ids as $a) {
    $exempts[] = $personnes[$a[0]];
}

// Générer le tableau indexé, dénommé $convoques, des NOMs des PERSONNES qui ne sont ni EXEMPTS, ni ABSENTS à l'audience.
// Résultat attendu pour convocation n°1 :

$convoques = [];
foreach ($personnes as $N) {
    if (array_search($N, $absents) === false && array_search($N, $exempts) === false) {
        // $N ni absent, ni exempt
        $convoques[] = $N;
    }
}

// Générer le tableau associatif $disabled qui, selon l'audience, prescrit l'activation ou la désactivation des éléments SELECT (via l'attribut "disabled").
// Règles de génération :
// Le tableau $disabled contient autant d'éléments qu'il y a de personnes.
// La i-ème clé est égale à la concaténation de "c" et du nombre i (par ex. "c2" pour i=2).
// La i-ème valeur est égale à "false" si i est inférieur ou égal au nombre de personnes NON absentes à l'audience.
// Résultat attendu pour convocation n°1 :

$disabled = [];
for ($i = 1; $i <= count($personnes); $i ++) {
    $ci = "c" . $i;
    $disabled[$ci] = true;
    if ($i <= count($personnes) - count($absents)) {
        $disabled[$ci] = false;
    }
}

// Générer le tableau associatif $options qui, selon l'audience, prescrit la liste des OPTIONs à insérer dans tous les SELECTs.
// Règles de génération :
// Le tableau $options contient 1+n éléments où n est le nombre de personnes NON absentes à l'audience.
// La 1ère clé est égale à "nemo", la 1ère valeur à "---".
// La i+1-ème valeur est le nom de la i-ème personne non absente à l'audience.
// La i+1-ème clé est la i+1-ème valeur mise en minuscules.
// Résultat attendu pour convocation n°1 :

$options = [
    "nemo" => "---"
];
foreach ($personnes as $N) {
    if (array_search($N, $absents) === false) {
        // $N présent
        $options[strtolower($N)] = $N;
    }
}

/*
 * Renvoie sous forme de chaîne de caractères le fragment HTML qui modélise un élément SELECT
 * (1) dont l'attribut "name" vaut $name (le paramètre d'appel de valeur "c1", "c2", ... ou "c8")
 * (2) qui a l'attribut "disabled" si $disabled[$name]==true
 * (3) dont la liste d'OPTIONs est celle prescrite dans $options
 * (4) et, s'il s'agit du i-ème SELECT où i <= nombre de convoqués, a pour OPTION pré-sélectionnée celle correspondant au i-ème convoqué.
 */
// Résultat attendu pour convocation n°1 : voir menus déroulants dans audition.html 

function genererSelect($name): string
{
    global $convoques, $disabled, $options;
    $html = "";
    $html .= "<select name=\"$name\"";
    if ($disabled[$name]) {
        $html .= "disabled=\"disabled\"";
    }
    $html .= ">";
    $c = (int) substr($name, 1); // "numéro" du convoqué : 1, 2, ...8
    foreach ($options as $value => $nom) {
        $html .= "<option value=\"$value\"";
        if ($value !== "nemo" && ! $disabled[$name] && array_search($nom, $convoques) === $c - 1) {
            $html .= "selected=\"selected\"";
        }
        $html .= ">";
        $html .= "$nom</option>";
    }
    $html .= "</select>";
    return $html;
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Tribunal</title>
<link rel="stylesheet" type="text/css" href="audience.css" />
</head>
<body>
   <form action="audience.php" method="post">
      <!-- BOUTONS -->
      <fieldset>
         <input type="submit" name="extraire" value="EXTRAIRE" />
         <input type="number" name="audience" value="<?= $audience ?>" min="1" max="4" />
         <input type="submit" name="maj" value="METTRE A JOUR" />
      </fieldset>
      <!-- CONVOQUES -->
      <table id="convoques">
         <tr>
            <th>CONVOQUES</th>
         </tr>
         <tr>
            <td class="convoques">
               <?= genererSelect("c".($i=1)); ?>
            </td>
         </tr>
         <tr>
            <td class="convoques">
               <?= genererSelect("c".++$i); ?>
            </td>
         </tr>
         <tr>
            <td class="convoques">
               <?= genererSelect("c".++$i); ?>

            </td>
         </tr>
         <tr>
            <td class="convoques">
               <?= genererSelect("c".++$i); ?>
            </td>
         </tr>
         <tr>
            <td class="convoques">
               <?= genererSelect("c".++$i); ?>
            </td>
         </tr>
         <tr>
            <td class="convoques">
               <?= genererSelect("c".++$i); ?>
            </td>
         </tr>
         <tr>
            <td class="convoques">
               <?= genererSelect("c".++$i); ?>
            </td>
         </tr>
         <tr>
            <td class="convoques">
               <?= genererSelect("c".++$i); ?>
            </td>
         </tr>
      </table>
      <!-- EXEMPTS -->
      <table id="exempts">
         <tr>
            <th>EXEMPTS</th>
         </tr>
         <tr>
            <td class="exempts"><?= (($i=0)<count($exempts)) ? $exempts[$i++] : ""; ?></td>
         </tr>
         <tr>
            <td class="exempts"><?= ($i<count($exempts)) ? $exempts[$i++] : ""; ?></td>
         </tr>
         <tr>
            <td class="exempts"><?= ($i<count($exempts)) ? $exempts[$i++] : ""; ?></td>
         </tr>
         <tr>
            <td class="exempts"><?= ($i<count($exempts)) ? $exempts[$i++] : ""; ?></td>
         </tr>
         <tr>
            <td class="exempts"><?= ($i<count($exempts)) ? $exempts[$i++] : ""; ?></td>
         </tr>
         <tr>
            <td class="exempts"><?= ($i<count($exempts)) ? $exempts[$i++] : ""; ?></td>
         </tr>
         <tr>
            <td class="exempts"><?= ($i<count($exempts)) ? $exempts[$i++] : ""; ?></td>
         </tr>
         <tr>
            <td class="exempts"><?= ($i<count($exempts)) ? $exempts[$i++] : ""; ?></td>
         </tr>
      </table>
      <!-- ABSENTS -->
      <table id="absents">
         <tr>
            <th>ABSENTS</th>
         </tr>
         <tr>
            <td class="absents"><?= (($i=0)<count($absents)) ? $absents[$i++] : ""; ?></td>
         </tr>
         <tr>
            <td class="absents"><?= ($i<count($absents)) ? $absents[$i++] : ""; ?></td>
         </tr>
         <tr>
            <td class="absents"><?= ($i<count($absents)) ? $absents[$i++] : ""; ?></td>
         </tr>
         <tr>
            <td class="absents"><?= ($i<count($absents)) ? $absents[$i++] : ""; ?></td>
         </tr>
         <tr>
            <td class="absents"><?= ($i<count($absents)) ? $absents[$i++] : ""; ?></td>
         </tr>
         <tr>
            <td class="absents"><?= ($i<count($absents)) ? $absents[$i++] : ""; ?></td>
         </tr>
         <tr>
            <td class="absents"><?= ($i<count($absents)) ? $absents[$i++] : ""; ?></td>
         </tr>
         <tr>
            <td class="absents"><?= ($i<count($absents)) ? $absents[$i++] : ""; ?></td>
         </tr>
      </table>
   </form>
   <div id="images">
      <img title="auguste" src="img/auguste-pub.jpg" />
      <img title="caligula" src="img/caligula-pub.jpg" />
      <img title="cesar" src="img/cesar-pub.jpg" />
      <img title="claude" src="img/claude-pub.jpg" />
      <img title="hadrien" src="img/hadrien-pub.jpg" />
      <img title="neron" src="img/neron-pub.jpg" />
      <img title="tibere" src="img/tibere-pub.jpg" />
      <img title="trajan" src="img/trajan-pub.jpg" />
   </div>
   <script src="audience.js"></script>
</body>
</html>
