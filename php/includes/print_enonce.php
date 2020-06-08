<?php
// Ce fichier contient la mise en forme pour l'impresssion PDF de l'évaluation
// Il a fonctionné avec html2pdf pour la réalisation du jeu de données.
$var  = "sjdkdfjms";
?>
<style>
    <!--
    * {
        font-family: Arial, sans-serif
    }

    td {
        vertical-align: top;
        padding: 2mm;
    }

    th {
        vertical-align: top;
        text-align: left;
        padding: 2mm;
    }

    .attention {
        color: red;
    }

    .cartouche,
    .cartouche td,
    .cartouche th {
        border: 1px solid black;
        border-collapse: collapse;
    }

    .lowerline {
        border-bottom: 1px solid black;
        border-collapse: collapse;
    }

    td, th, table, thead, tfoot, tbody {
        border-collapse: collapse;
    }

    .label {
        display: inline-block;
        width: 100px;
    }

    h4 {
        margin: 0;
    }

    p {
        margin-top: 1mm;
        margin-bottom: 1mm;
    }

    table.page_footer {
        width: 100%;
        border: none;
        border-top: 1px solid red;
        padding: 2mm
    }

    table.page_header {
        width: 100%;
        border: none;
        border-bottom: 1px solid grey;
        padding: 2mm
    }
    hr {
        margin: 0;
  
    }
    -->
</style>


<page backtop="10mm" backbottom="25mm" backleft="10mm" backright="10mm">
    <page_footer>
        <table class="page_footer">
            <tr>
                <td style="width: 50%; text-align: left">
                    page <strong>[[page_cu]]</strong> sur <strong>[[page_nb]]</strong>
                </td>
                <td style="width: 50%; text-align: right">
                    Version de développement
                </td>
            </tr>
        </table>
    </page_footer>
    <table>
        <tr>
            <td width="60"><img src="img/GE50px.png" alt="logo GE"></td>
            <td width="460">
                <p>République et canton de Genève<br>Département de l'instruction publique, <br>de la formation et de la jeunesse<br><strong>Office pour l'orientation, <br>la formation professionnelle et continue</strong>
                </p>
            </td>
            <td width="100"><img src="img/LogoExpertsDev100px.png" alt="logo Expert"></td>
        </tr>
    </table>

    <h2 style="text-align:center">Travail pratique individuel (TPI)</h2>
    <p style="text-align:center">
        <h4>Informaticien-ne CFC<br>
            Dossier d'inscription et description du travail</h4>
    </p>



    <table class="cartouche">
        <tr>
            <td width="310">
                <h4>Candidat</h4>
                <p>
                    <span class="label">Nom : </span><?=$candidate->lastName?><br>
                    <span class="label">Prénom : </span><?=$candidate->firstName?><br>
                    <span class="label">Classe : </span><?=$classe?><br>
                    <span class="label">Téléphone : </span><?=$candidate->phone?><br>
                    <span class="label">Email : </span><?=$candidate->email?>
                </p>
            </td>
            <td width="310">
                <h4>Entreprise formatrice</h4>
                <p>
                    <span class="label">Société : </span><?=$manager->compagnyName?><br>
                    <span class="label">Adresse : </span><?=$manager->address?><br>
                </p>
                <h4>Formateur</h4>
                <p>
                    <span class="label">Nom : </span><?=$manager->lastName?><br>
                    <span class="label">Prénom : </span><?=$manager->firstName?><br>
                    <span class="label">Téléphone : </span><?=$manager->phone?><br>
                    <span class="label">Email : </span><?=$manager->email?>
                </p>
            </td>
        </tr>
        <?php if (!empty($expert1) || (!empty($expert2))) : ?>
            <tr>
                <td width="310">
                    <h4>1<sup>er</sup> Expert</h4>
                    <p>
                        <span class="label">Nom : </span><?=$expert1->lastName?><br>
                        <span class="label">Prénom : </span><?=$expert1->firstName?><br>
                        <span class="label">Téléphone : </span><?=$expert1->phone?><br>
                        <span class="label">Email : </span><?=$expert1->email?>
                    </p>
                </td>
                <td width="310">
                    <h4>2<sup>ème</sup> Expert</h4>
                    <p>
                        <span class="label">Nom : </span><?=$expert2->lastName?><br>
                        <span class="label">Prénom : </span><?=$expert2->firstName?><br>
                        <span class="label">Téléphone : </span><?=$expert2->phone?><br>
                        <span class="label">Email : </span><?=$expert2->email?>
                    </p>
                </td>
            </tr>
        <?php endif; ?>
    </table>

    <div><br></div>
    <table>
        <tr>
            <th width="100" class="lowerline">Titre du travail</th>
            <td width="510" class="lowerline">
                <h4><?=$tpi->title?></h4>
            </td>
        </tr>
      
        <tr>
            <th width="100" class="lowerline">Domaine</th>
            <td width="510" class="lowerline"><?=$tpi->cfcDomain?></td>
        </tr>
        <tr>
            <th width="100" class="lowerline">Dates</th>
            <td width="510" class="lowerline">du <?=$dateTpi["start"]["date"] ?> au <?=$dateTpi["end"]["date"] ?>, de <?=$dateTpi["start"]["time"] ?> à <?=$dateTpi["start"]["time"] ?></td>
        </tr>
        <tr>
            <th width="100" class="lowerline">Lieu où se déroule le travail</th>
            <td width="510" class="lowerline"><?=$tpi->workplace?></td>
        </tr>
        <tr>
            <th width="100" class="lowerline">Résumé</th>
            <td width="510" class="lowerline"><?=$tpi->abstract?></td>
        </tr>
    </table>

    
    <h2 class="attention">Rappel</h2>
    <p class="attention">Il est interdit au candidat de prendre connaissance de l'énoncé du travail de TPI avant le début de celui-ci.
        <br>L'énoncé lui sera transmis par les experts, par messagerie, le matin du 1er jour du TPI avant 7h30</p>
    <hr>
    <p>Document soumis au collège d'experts le : <?=$dateTpi["submission"]["date"] ?> à <?=$dateTpi["submission"]["time"] ?></p>

</page>

<page backtop="25mm" backbottom="25mm" backleft="10mm" backright="10mm">
    <page_header>
        <table class="page_header">
            <tr>
                <td style="width: 100%; text-align: right">
                    Procédure de qualification : 88600/1/2/3 Informaticienne CFC/Informaticien CFC (Ordonnance 2014)<br>
                    Enoncé du TPI, Candidat-e : <?=$candidate->lastName?> <?=$candidate->firstName?></td>
            </tr>
        </table>
    </page_header>
    <page_footer>
        <table class="page_footer">
            <tr>
                <td style="width: 50%; text-align: left">
                    page <strong>[[page_cu]]</strong> sur <strong>[[page_nb]]</strong>
                </td>
                <td style="width: 50%; text-align: right">
                    Version de développement
                </td>
            </tr>
        </table>
    </page_footer>

    <h1>TPI - Cahier de charges</h1>
    <p class="attention">Ce document sera connu du candidat uniquement au commencement du TPI. Il est interdit d'en communiquer le contenu au candidat avant la date de TPI convenue.</p>

    <?=$tpi->description?>

    <hr>
</page>

<page backtop="25mm" backbottom="15mm" backleft="10mm" backright="10mm">
    <page_header>
        <table class="page_header">
            <tr>
                <td style="width: 100%; text-align: right">
                    Procédure de qualification : 88600/1/2/3 Informaticienne CFC/Informaticien CFC (Ordonnance 2014)<br>
                    Enoncé du TPI, Candidat-e : <?=$candidate->lastName?> <?=$candidate->firstName?></td>
            </tr>
        </table>
    </page_header>
    <page_footer>
        <table class="page_footer">
            <tr>
                <td style="width: 50%; text-align: left">
                    page <strong>[[page_cu]]</strong> sur <strong>[[page_nb]]</strong>
                </td>
                <td style="width: 50%; text-align: right">
                    Version de développement
                </td>
            </tr>
        </table>
    </page_footer>
    <h2>Points techniques spécifiques au projet (points A14 à A20 du formulaire d'évaluation)</h2>
    <hr>
    <table>
    <?php 
    foreach ($tpi->evaluationCriterions as $ec) {?>
        
            <tr>
                <th width="60"><?= $ec->criterionNumber?></th>
                <td width="580"><?= $ec->criterionDescription?></td>
            </tr>
            <tr>
                <td colspan="2">
                    <hr>
                </td>
            </tr>
       <?php }?>
        
    </table>
</page>
