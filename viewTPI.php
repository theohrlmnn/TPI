<?php
/*
 * File: viewPdf.php
 * Author: Théo Hurlimann
 * Date: 26.05.2020
 * Description: Page pour l'affichage du pdf d'un candidat
 * Version: 1.0 
*/
require_once("php/inc.all.php");

require "vendor/autoload.php";

use Spipu\Html2Pdf\Html2Pdf;

if (!islogged()) {

    $messages = array(
        array("message" => "Vous devez être connecté pour voir ceci.", "type" => AL_DANGER)
    );
    setMessage($messages);
    setDisplayMessage(true);

    header('Location: login.php');
    exit;
}

$id = filter_input(INPUT_GET, "tpiId", FILTER_SANITIZE_NUMBER_INT);

$btnPdf = filter_input(INPUT_POST, "btnPdf", FILTER_SANITIZE_STRING);

$arrRoles = getRoleUserSession();
$role = min($arrRoles);
$tpi = getTpiByIDAllInfo($id);



?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vue TPI</title>
    <!-- CSS FILES -->
    <link rel="stylesheet" type="text/css" href="css/uikit.css">
    <link rel="stylesheet" href="css/cssNavBar.css">
</head>

<body>
    <?php include_once("php/includes/nav.php");
    ?>
    <div class="toggle-class uk-height-viewport uk-margin-bottom">
        <div class="uk-container uk-margin-top">
            <div class=" uk-child-width-1-3@s uk-grid-column-small" uk-grid>
                <div>
                    <label class="uk-form-label" for="form-horizontal-text">Titre</label>
                    <div class="uk-inline uk-width-1-1">
                        <span class="uk-form-icon uk-form-icon-flip" data-uk-icon="icon: user"></span>
                        <input name="tbxTitle" class="uk-input uk-border-pill" placeholder="<?= $tpi->title?>" type="text" disabled>
                    </div>
                </div>
                <div>
                    <label class="uk-form-label" for="form-horizontal-text">Lieu de travail</label>
                    <div class="uk-inline uk-width-1-1">
                        <span class="uk-form-icon uk-form-icon-flip" data-uk-icon="icon: home"></span>
                        <input name="tbxWorkPlace" class="uk-input uk-border-pill" placeholder="A domicile" type="text" disabled>
                    </div>
                </div>
                <div>
                    <label class="uk-form-label" for="form-horizontal-text">Domaine CFC</label>
                    <div class="uk-inline uk-width-1-1">
                        <span class="uk-form-icon uk-form-icon-flip" data-uk-icon="icon: info"></span>
                        <input name="tbxDomainCFC" class="uk-input uk-border-pill" placeholder="Développement d'applications" type="text" disabled>
                    </div>
                </div>
                <div>
                    <label class="uk-form-label " for="form-horizontal-text">Année du TPI</label>
                    <div class="uk-inline uk-width-1-1">
                        <span class="uk-form-icon uk-form-icon-flip" data-uk-icon="icon: calendar"></span>
                        <input name="tbxYear" class="uk-input uk-border-pill" required placeholder="2020" type="number" disabled>
                    </div>
                </div>
                <div>
                    <label class="uk-form-label" for="form-horizontal-text">Titre</label>
                    <div class="uk-inline uk-width-1-1">
                        <span class="uk-form-icon uk-form-icon-flip" data-uk-icon="icon: user"></span>
                        <input name="tbxTitle" class="uk-input uk-border-pill" placeholder="Outil de collaboration pour le collège d’experts, modules Répartition et ..." type="text" disabled>
                    </div>
                </div>
                <div>
                    <label class="uk-form-label" for="form-horizontal-text">Domaine CFC</label>
                    <div class="uk-inline uk-width-1-1">
                        <span class="uk-form-icon uk-form-icon-flip" data-uk-icon="icon: info"></span>
                        <input name="tbxDomainCFC" class="uk-input uk-border-pill" placeholder="Développement d'applications" type="text" disabled>
                    </div>
                </div>
                <div>
                <label class="uk-form-label" for="form-horizontal-text">Experts :  </label>
                    <div class="uk-margin-top"uk-form-custom="target:> * > span:first-child\">
                        <button class="uk-button uk-button-default" type="button" tabindex="-1\">
                            <span>Candidat</span>
                        </button>
                    </div>
                    <div disable class="uk-margin-top uk-margin-left"uk-form-custom="target:> * > span:first-child\">   
                        <button  class="uk-button uk-button-default" type="button" tabindex="-1\">
                            <span>Candidat</span>
                        </button>
                    </div>
                </div>
                <div>
                <label class="uk-form-label" for="form-horizontal-text">Chef de projet :  </label>
                    <div class="uk-margin-top"uk-form-custom="target:> * > span:first-child\"> 
                        <button class="uk-button uk-button-default" type="button" tabindex="-1\">
                            <span>Candidat</span>
                        </button>
                    </div>
                </div>
                <div>
                <label class="uk-form-label" for="form-horizontal-text">Candidat :  </label>
                    <div class="uk-margin-top"uk-form-custom="target:> * > span:first-child\"> 
                        <button class="uk-button uk-button-default" type="button" tabindex="-1\">
                            <span>Candidat</span>
                        </button>
                    </div>
                </div>
                <div>
                    <label class="uk-form-label" for="form-horizontal-text">Date du début de la session :</label>
                    <span class="uk-form-icon uk-form-icon-flip"></span>
                    <input name="tbxDateStartSession" class="uk-input uk-border-pill" type="date" disabled>
                </div>
                <div>
                    <label class="uk-form-label" for="form-horizontal-text">Date de la fin de la session :</label>
                    <span class="uk-form-icon uk-form-icon-flip"></span>
                    <input name="tbxDateEndSession" class="uk-input uk-border-pill" type="date" disabled>
                </div>
                <div>
                    <label class="uk-form-label" for="form-horizontal-text">Date de la présentation du TPI :</label>
                    <span class="uk-form-icon uk-form-icon-flip"></span>
                    <input name="tbxDatePresentation" class="uk-input uk-border-pill" type="date" disabled>
                </div>
                <div>
                    <label class="uk-form-label" for="form-horizontal-text">Numéro du Critère 1</label>
                    <div class="uk-inline uk-width-1-1">
                        <span class="uk-form-icon uk-form-icon-flip"></span>
                        <input name="tbxCriterionNumber1" value="14" class="uk-input uk-border-pill" placeholder="14" type="text" disabled>
                    </div>
                </div>
                <div>
                    <label class="uk-form-label" for="form-horizontal-text">Groupe Critère 1</label>
                    <div class="uk-inline uk-width-1-1">
                        <span class="uk-form-icon uk-form-icon-flip"></span>
                        <input name="tbxCriterionGroup1" class="uk-input uk-border-pill" placeholder="A" type="text" disabled>
                    </div>
                </div>
                <div>
                    <label class="uk-form-label" for="form-horizontal-text">Description du Critère 1</label>
                    <div class="uk-inline uk-width-1-1">
                        <span class="uk-form-icon uk-form-icon-flip"></span>
                        <input name="tbxCriterionDescription1" class="uk-input uk-border-pill" placeholder="Tenue du journal de travail" type="text" disabled>
                    </div>
                </div>
                <div>
                    <label class="uk-form-label" for="form-horizontal-text">Numéro du Critère 2</label>
                    <div class="uk-inline uk-width-1-1">
                        <span class="uk-form-icon uk-form-icon-flip"></span>
                        <input name="tbxCriterionNumber2" value="15" class="uk-input uk-border-pill" placeholder="15" type="text" disabled>
                    </div>
                </div>
                <div>
                    <label class="uk-form-label" for="form-horizontal-text">Groupe Critère 2</label>
                    <div class="uk-inline uk-width-1-1">
                        <span class="uk-form-icon uk-form-icon-flip"></span>
                        <input name="tbxCriterionGroup2" class="uk-input uk-border-pill" placeholder="A" type="text" disabled>
                    </div>
                </div>
                <div>
                    <label class="uk-form-label" for="form-horizontal-text">Description du Critère 2</label>
                    <div class="uk-inline uk-width-1-1">
                        <span class="uk-form-icon uk-form-icon-flip"></span>
                        <input name="tbxCriterionDescription2" class="uk-input uk-border-pill" placeholder="Tenue du journal de travail" type="text" disabled>
                    </div>
                </div>
                <div>
                    <label class="uk-form-label" for="form-horizontal-text">Numéro du Critère 3</label>
                    <div class="uk-inline uk-width-1-1">
                        <span class="uk-form-icon uk-form-icon-flip"></span>
                        <input name="tbxCriterionNumber3" value="16" class="uk-input uk-border-pill" placeholder="16" type="text" disabled>
                    </div>
                </div>
                <div>
                    <label class="uk-form-label" for="form-horizontal-text">Groupe Critère 3</label>
                    <div class="uk-inline uk-width-1-1">
                        <span class="uk-form-icon uk-form-icon-flip"></span>
                        <input name="tbxCriterionGroup3" class="uk-input uk-border-pill" placeholder="A" type="text" disabled>
                    </div>
                </div>
                <div>
                    <label class="uk-form-label" for="form-horizontal-text">Description du Critère 3</label>
                    <div class="uk-inline uk-width-1-1">
                        <span class="uk-form-icon uk-form-icon-flip"></span>
                        <input name="tbxCriterionDescription3" class="uk-input uk-border-pill" placeholder="Tenue du journal de travail" type="text" disabled>
                    </div>
                </div>
                <div>
                    <label class="uk-form-label" for="form-horizontal-text">Numéro du Critère 4</label>
                    <div class="uk-inline uk-width-1-1">
                        <span class="uk-form-icon uk-form-icon-flip"></span>
                        <input name="tbxCriterionNumber4" value="17" class="uk-input uk-border-pill" placeholder="17" type="text" disabled>
                    </div>
                </div>
                <div>
                    <label class="uk-form-label" for="form-horizontal-text">Groupe Critère 4</label>
                    <div class="uk-inline uk-width-1-1">
                        <span class="uk-form-icon uk-form-icon-flip"></span>
                        <input name="tbxCriterionGroup4" class="uk-input uk-border-pill" placeholder="A" type="text" disabled>
                    </div>
                </div>
                <div>
                    <label class="uk-form-label" for="form-horizontal-text">Description du Critère 4</label>
                    <div class="uk-inline uk-width-1-1">
                        <span class="uk-form-icon uk-form-icon-flip"></span>
                        <input name="tbxCriterionDescription4" class="uk-input uk-border-pill" placeholder="Tenue du journal de travail" type="text" disabled>
                    </div>
                </div>
                <div>
                    <label class="uk-form-label" for="form-horizontal-text">Numéro du Critère 5</label>
                    <div class="uk-inline uk-width-1-1">
                        <span class="uk-form-icon uk-form-icon-flip"></span>
                        <input name="tbxCriterionNumber5" value="18" class="uk-input uk-border-pill" placeholder="18" type="text" disabled>
                    </div>
                </div>
                <div>
                    <label class="uk-form-label" for="form-horizontal-text">Groupe Critère 5</label>
                    <div class="uk-inline uk-width-1-1">
                        <span class="uk-form-icon uk-form-icon-flip"></span>
                        <input name="tbxCriterionGroup5" class="uk-input uk-border-pill" placeholder="A" type="text" disabled>
                    </div>
                </div>
                <div>
                    <label class="uk-form-label" for="form-horizontal-text">Description du Critère 5</label>
                    <div class="uk-inline uk-width-1-1">
                        <span class="uk-form-icon uk-form-icon-flip"></span>
                        <input name="tbxCriterionDescription5" class="uk-input uk-border-pill" placeholder="Tenue du journal de travail" type="text" disabled>
                    </div>
                </div>
                <div>
                    <label class="uk-form-label" for="form-horizontal-text">Numéro du Critère 6</label>
                    <div class="uk-inline uk-width-1-1">
                        <span class="uk-form-icon uk-form-icon-flip"></span>
                        <input name="tbxCriterionNumber6" value="19" class="uk-input uk-border-pill" placeholder="19" type="text" disabled>
                    </div>
                </div>
                <div>
                    <label class="uk-form-label" for="form-horizontal-text">Groupe Critère 6</label>
                    <div class="uk-inline uk-width-1-1">
                        <span class="uk-form-icon uk-form-icon-flip"></span>
                        <input name="tbxCriterionGroup6" class="uk-input uk-border-pill" placeholder="A" type="text" disabled>
                    </div>
                </div>
                <div>
                    <label class="uk-form-label" for="form-horizontal-text">Description du Critère 6</label>
                    <div class="uk-inline uk-width-1-1">
                        <span class="uk-form-icon uk-form-icon-flip"></span>
                        <input name="tbxCriterionDescription6" class="uk-input uk-border-pill" placeholder="Tenue du journal de travail" type="text" disabled>
                    </div>
                </div>
                <div>
                    <label class="uk-form-label" for="form-horizontal-text">Numéro du Critère 7</label>
                    <div class="uk-inline uk-width-1-1">
                        <span class="uk-form-icon uk-form-icon-flip"></span>
                        <input name="tbxCriterionNumber7" value="20" class="uk-input uk-border-pill" placeholder="20" type="text" disabled>
                    </div>
                </div>
                <div>
                    <label class="uk-form-label" for="form-horizontal-text">Groupe Critère 7</label>
                    <div class="uk-inline uk-width-1-1">
                        <span class="uk-form-icon uk-form-icon-flip"></span>
                        <input name="tbxCriterionGroup7" class="uk-input uk-border-pill" placeholder="A" type="text" disabled>
                    </div>
                </div>
                <div>
                    <label class="uk-form-label" for="form-horizontal-text">Groupe Critère 7</label>
                    <div class="uk-inline uk-width-1-1">
                        <span class="uk-form-icon uk-form-icon-flip"></span>
                        <input name="tbxCriterionGroup7" class="uk-input uk-border-pill" placeholder="A" type="text" disabled>
                    </div>
                </div>

            </div>
            <div class=" uk-child-width-1-1@s uk-grid-column-small" uk-grid>
                <div>
                    <label class="uk-form-label" for="form-horizontal-text">Résumé</label>
                    <div class="uk-inline uk-width-1-1">
                        <span class="uk-form-icon uk-form-icon-flip" data-uk-icon="icon: comment"></span>
                        <textarea name="tbxAbstract" class="uk-input " placeholder="Le but principal de cette application est de donner aux membres du collège ..." type="text" disabled></textarea>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- JS FILES -->
    <script src="js/uikit.js"></script>
    <script src="js/uikit-icons.js"></script>
</body>

</html>