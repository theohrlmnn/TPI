<?php
/*
 * File: formManager.php
 * Author: Théo Hurlimann
 * Date: 27.05.2020
 * Description: Formulaire à include pour modifier un TPI vue Chef de projet
 * Version: 1.0 
*/
?>
    
    <form class="toggle-class uk-height-viewport" action="modifyTPI.php?tpiId=<?= $tpi->id ?>" method="POST">
    <?php 
    echo displayMessage();
    ?>
        <div class="uk-container uk-margin-top">
            <div class=" uk-child-width-1-3@s uk-grid-column-small" uk-grid>
                <div>
                    <label class="uk-form-label" for="form-horizontal-text">Titre</label>
                    <div class="uk-inline uk-width-1-1">
                        <span class="uk-form-icon uk-form-icon-flip" data-uk-icon="icon: user"></span>
                        <input name="tbxTitle" value="<?= $tpi->title ?>" class="uk-input uk-border-pill" placeholder="Outil de collaboration pour le collège d’experts, modules Répartition et ..." type="text">
                    </div>
                </div>
                <div>
                    <label class="uk-form-label" for="form-horizontal-text">Lieu de travail</label>
                    <div class="uk-inline uk-width-1-1">
                        <span class="uk-form-icon uk-form-icon-flip" data-uk-icon="icon: home"></span>
                        <input name="tbxWorkplace" value="<?= $tpi->workplace ?>" class="uk-input uk-border-pill" placeholder="A domicile" type="text">
                    </div>
                </div>
                <div>
                    <label class="uk-form-label" for="form-horizontal-text">Domaine CFC</label>
                    <div class="uk-inline uk-width-1-1">
                        <span class="uk-form-icon uk-form-icon-flip" data-uk-icon="icon: info"></span>
                        <input name="tbxDomainCFC" value="<?= $tpi->cfcDomain ?>" class="uk-input uk-border-pill" placeholder="Développement d'applications" type="text">
                    </div>
                </div>
                <div>
                    <label class="uk-form-label" for="form-horizontal-text">Date du début de la session :</label>
                    <span class="uk-form-icon uk-form-icon-flip"></span>
                    <input name="tbxDateStartSession" value="<?= $arrDateTime['start']['date'] ?>" class="uk-input uk-border-pill" type="date">
                </div>
                <div>
                    <label class="uk-form-label" for="form-horizontal-text">Date de la fin de la session :</label>
                    <span class="uk-form-icon uk-form-icon-flip"></span>
                    <input name="tbxDateEndSession" value="<?= $arrDateTime['end']['date'] ?>" class="uk-input uk-border-pill" type="date">
                </div>
                <div>
                    <label class="uk-form-label" for="form-horizontal-text">Date de la présentation du TPI :</label>
                    <span class="uk-form-icon uk-form-icon-flip"></span>
                    <input name="tbxDatePresentation" value="<?= $arrDateTime['presentation']['date'] ?>" class="uk-input uk-border-pill" type="date">
                </div>
                <div>
                    <label class="uk-form-label" for="form-horizontal-text">Date du début de la session :</label>
                    <span class="uk-form-icon uk-form-icon-flip"></span>
                    <input name="tbxTimeStartSession" value="<?= $arrDateTime['start']['time'] ?>" class="uk-input uk-border-pill" type="time">
                </div>
                <div>
                    <label class="uk-form-label" for="form-horizontal-text">Date de la fin de la session :</label>
                    <span class="uk-form-icon uk-form-icon-flip"></span>
                    <input name="tbxTimeEndSession" value="<?= $arrDateTime['end']['time'] ?>" class="uk-input uk-border-pill" type="time">
                </div>
                <div>
                    <label class="uk-form-label" for="form-horizontal-text">Date de la présentation du TPI :</label>
                    <span class="uk-form-icon uk-form-icon-flip"></span>
                    <input name="tbxTimePresentation" value="<?= $arrDateTime['presentation']['time'] ?>" class="uk-input uk-border-pill" type="time">
                </div>
                <div>
                    <label class="uk-form-label " for="form-horizontal-text">Année du TPI</label>
                    <div class="uk-inline uk-width-1-1">
                        <span class="uk-form-icon uk-form-icon-flip" data-uk-icon="icon: calendar"></span>
                        <input name="tbxYear" value="<?= $tpi->year ?>" class="uk-input uk-border-pill" placeholder="2020" type="number">
                    </div>
                </div>
                <div>
                    <label class="uk-form-label" for="form-horizontal-text">Résumé</label>
                    <div class="uk-inline uk-width-1-1">
                        <span class="uk-form-icon uk-form-icon-flip"></span>
                        <textarea name="tbxAbstract" rows="17" class="uk-textarea " placeholder="Le but principal de cette application est de donner aux membres du collège ..." type="text"><?= $tpi->abstract ?></textarea>
                    </div>
                </div>
                <div class="uk-width-expand@m">
                    <label class="uk-form-label" for="form-horizontal-text">Description</label>
                    <div class="uk-inline uk-width-1-1">
                        <span class="uk-form-icon uk-form-icon-flip"></span>
                        <textarea id="editor" name="editor"></textarea>
                    </div>
                </div>
                <div>
                    <label class="uk-form-label" for="form-horizontal-text">Numéro du Critère 1</label>
                    <div class="uk-inline uk-width-1-1">
                        <span class="uk-form-icon uk-form-icon-flip"></span>
                        <input name="tbxCriterionNumber1" value="<?= $tpi->evaluationCriterions[0]->criterionNumber ?>" class="uk-input uk-border-pill" placeholder="14" type="text">
                    </div>
                </div>
                <div>
                    <label class="uk-form-label" for="form-horizontal-text">Groupe Critère 1</label>
                    <div class="uk-inline uk-width-1-1">
                        <span class="uk-form-icon uk-form-icon-flip"></span>
                        <input name="tbxCriterionGroup1" value="<?= $tpi->evaluationCriterions[0]->criterionGroup ?>" class="uk-input uk-border-pill" placeholder="A" type="text">
                    </div>
                </div>
                <div>
                    <label class="uk-form-label" for="form-horizontal-text">Description du Critère 1</label>
                    <div class="uk-inline uk-width-1-1">
                        <span class="uk-form-icon uk-form-icon-flip"></span>
                        <input name="tbxCriterionDescription1" value="<?= $tpi->evaluationCriterions[0]->criterionDescription ?>" class="uk-input uk-border-pill" placeholder="Tenue du journal de travail" type="text">
                    </div>
                </div>
                <div>
                    <label class="uk-form-label" for="form-horizontal-text">Numéro du Critère 2</label>
                    <div class="uk-inline uk-width-1-1">
                        <span class="uk-form-icon uk-form-icon-flip"></span>
                        <input name="tbxCriterionNumber2" value="<?= $tpi->evaluationCriterions[1]->criterionNumber ?>" class="uk-input uk-border-pill" placeholder="15" type="text">
                    </div>
                </div>
                <div>
                    <label class="uk-form-label" for="form-horizontal-text">Groupe Critère 2</label>
                    <div class="uk-inline uk-width-1-1">
                        <span class="uk-form-icon uk-form-icon-flip"></span>
                        <input name="tbxCriterionGroup2" value="<?= $tpi->evaluationCriterions[1]->criterionGroup ?>" class="uk-input uk-border-pill" placeholder="A" type="text">
                    </div>
                </div>
                <div>
                    <label class="uk-form-label" for="form-horizontal-text">Description du Critère 2</label>
                    <div class="uk-inline uk-width-1-1">
                        <span class="uk-form-icon uk-form-icon-flip"></span>
                        <input name="tbxCriterionDescription2" value="<?= $tpi->evaluationCriterions[1]->criterionDescription ?>" class="uk-input uk-border-pill" placeholder="Tenue du journal de travail" type="text">
                    </div>
                </div>
                <div>
                    <label class="uk-form-label" for="form-horizontal-text">Numéro du Critère 3</label>
                    <div class="uk-inline uk-width-1-1">
                        <span class="uk-form-icon uk-form-icon-flip"></span>
                        <input name="tbxCriterionNumber3" value="<?= $tpi->evaluationCriterions[2]->criterionNumber ?>" class="uk-input uk-border-pill" placeholder="16" type="text">
                    </div>
                </div>
                <div>
                    <label class="uk-form-label" for="form-horizontal-text">Groupe Critère 3</label>
                    <div class="uk-inline uk-width-1-1">
                        <span class="uk-form-icon uk-form-icon-flip"></span>
                        <input name="tbxCriterionGroup3" value="<?= $tpi->evaluationCriterions[2]->criterionGroup ?>" class="uk-input uk-border-pill" placeholder="A" type="text">
                    </div>
                </div>
                <div>
                    <label class="uk-form-label" for="form-horizontal-text">Description du Critère 3</label>
                    <div class="uk-inline uk-width-1-1">
                        <span class="uk-form-icon uk-form-icon-flip"></span>
                        <input name="tbxCriterionDescription3" value="<?= $tpi->evaluationCriterions[2]->criterionDescription ?>" class="uk-input uk-border-pill" placeholder="Tenue du journal de travail" type="text">
                    </div>
                </div>
                <div>
                    <label class="uk-form-label" for="form-horizontal-text">Numéro du Critère 4</label>
                    <div class="uk-inline uk-width-1-1">
                        <span class="uk-form-icon uk-form-icon-flip"></span>
                        <input name="tbxCriterionNumber4" value="<?= $tpi->evaluationCriterions[3]->criterionNumber ?>" class="uk-input uk-border-pill" placeholder="17" type="text">
                    </div>
                </div>
                <div>
                    <label class="uk-form-label" for="form-horizontal-text">Groupe Critère 4</label>
                    <div class="uk-inline uk-width-1-1">
                        <span class="uk-form-icon uk-form-icon-flip"></span>
                        <input name="tbxCriterionGroup4" value="<?= $tpi->evaluationCriterions[3]->criterionGroup ?>" class="uk-input uk-border-pill" placeholder="A" type="text">
                    </div>
                </div>
                <div>
                    <label class="uk-form-label" for="form-horizontal-text">Description du Critère 4</label>
                    <div class="uk-inline uk-width-1-1">
                        <span class="uk-form-icon uk-form-icon-flip"></span>
                        <input name="tbxCriterionDescription4" value="<?= $tpi->evaluationCriterions[3]->criterionDescription ?>" class="uk-input uk-border-pill" placeholder="Tenue du journal de travail" type="text">
                    </div>
                </div>
                <div>
                    <label class="uk-form-label" for="form-horizontal-text">Numéro du Critère 5</label>
                    <div class="uk-inline uk-width-1-1">
                        <span class="uk-form-icon uk-form-icon-flip"></span>
                        <input name="tbxCriterionNumber5" value="<?= $tpi->evaluationCriterions[4]->criterionNumber ?>" class="uk-input uk-border-pill" placeholder="18" type="text">
                    </div>
                </div>
                <div>
                    <label class="uk-form-label" for="form-horizontal-text">Groupe Critère 5</label>
                    <div class="uk-inline uk-width-1-1">
                        <span class="uk-form-icon uk-form-icon-flip"></span>
                        <input name="tbxCriterionGroup5" value="<?= $tpi->evaluationCriterions[4]->criterionGroup ?>" class="uk-input uk-border-pill" placeholder="A" type="text">
                    </div>
                </div>
                <div>
                    <label class="uk-form-label" for="form-horizontal-text">Description du Critère 5</label>
                    <div class="uk-inline uk-width-1-1">
                        <span class="uk-form-icon uk-form-icon-flip"></span>
                        <input name="tbxCriterionDescription5" value="<?= $tpi->evaluationCriterions[4]->criterionDescription ?>" class="uk-input uk-border-pill" placeholder="Tenue du journal de travail" type="text">
                    </div>
                </div>
                <div>
                    <label class="uk-form-label" for="form-horizontal-text">Numéro du Critère 6</label>
                    <div class="uk-inline uk-width-1-1">
                        <span class="uk-form-icon uk-form-icon-flip"></span>
                        <input name="tbxCriterionNumber6" value="<?= $tpi->evaluationCriterions[5]->criterionNumber ?>" class="uk-input uk-border-pill" placeholder="19" type="text">
                    </div>
                </div>
                <div>
                    <label class="uk-form-label" for="form-horizontal-text">Groupe Critère 6</label>
                    <div class="uk-inline uk-width-1-1">
                        <span class="uk-form-icon uk-form-icon-flip"></span>
                        <input name="tbxCriterionGroup6" value="<?= $tpi->evaluationCriterions[5]->criterionGroup ?>" class="uk-input uk-border-pill" placeholder="A" type="text">
                    </div>
                </div>
                <div>
                    <label class="uk-form-label" for="form-horizontal-text">Description du Critère 6</label>
                    <div class="uk-inline uk-width-1-1">
                        <span class="uk-form-icon uk-form-icon-flip"></span>
                        <input name="tbxCriterionDescription6" value="<?= $tpi->evaluationCriterions[5]->criterionDescription ?>" class="uk-input uk-border-pill" placeholder="Tenue du journal de travail" type="text">
                    </div>
                </div>
                <div>
                    <label class="uk-form-label" for="form-horizontal-text">Numéro du Critère 7</label>
                    <div class="uk-inline uk-width-1-1">
                        <span class="uk-form-icon uk-form-icon-flip"></span>
                        <input name="tbxCriterionNumber7" value="<?= $tpi->evaluationCriterions[6]->criterionNumber ?>" class="uk-input uk-border-pill" placeholder="20" type="text">
                    </div>
                </div>
                <div>
                    <label class="uk-form-label" for="form-horizontal-text">Groupe Critère 7</label>
                    <div class="uk-inline uk-width-1-1">
                        <span class="uk-form-icon uk-form-icon-flip"></span>
                        <input name="tbxCriterionGroup7" value="<?= $tpi->evaluationCriterions[6]->criterionGroup ?>" class="uk-input uk-border-pill" placeholder="A" type="text">
                    </div>
                </div>
                <div>
                    <label class="uk-form-label" for="form-horizontal-text">Description du Critère 7</label>
                    <div class="uk-inline uk-width-1-1">
                        <span class="uk-form-icon uk-form-icon-flip"></span>
                        <input name="tbxCriterionDescription7" value="<?= $tpi->evaluationCriterions[6]->criterionDescription ?>" class="uk-input uk-border-pill" placeholder="Tenue du journal de travail" type="text">
                    </div>
                </div>
            </div>
        </div>
        <div class=" uk-child-width-1-1@s uk-grid-column-small uk-margin-top" uk-grid>
            <div>
                <div class="uk-margin-bottom uk-flex uk-flex-center">
                    <button name="btnModify" value="Send" type="submit" class="uk-button uk-button-primary uk-border-pill uk-width-1-2">Modifier TPI</button>
                </div>
            </div>
        </div>
    </form>
    
