<?php
// Ce fichier contient la mise en forme pour l'impresssion PDF de l'évaluation
// Il a fonctionné avec html2pdf pour la réalisation du jeu de données.

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
                    <span class="label">Nom : </span>CANDIDATE_LAST_NAME<br>
                    <span class="label">Prénom : </span>CANDIDATE_FIRSTNAME<br>
                    <span class="label">Classe : </span>CANDIDATE_CLASSTNAME<br>
                    <span class="label">Téléphone : </span>CANDIDATE_PHONE<br>
                    <span class="label">Email : </span>CANDIDATE_EMAIL
                </p>
            </td>
            <td width="310">
                <h4>Entreprise formatrice</h4>
                <p>
                    <span class="label">Société : </span>MANAGER_COMPANY_NAME<br>
                    <span class="label">Adresse : </span>MANAGER_COMPANY_ADDRESS<br>
                </p>
                <h4>Formateur</h4>
                <p>
                    <span class="label">Nom : </span>MANAGER_LAST_NAME<br>
                    <span class="label">Prénom : </span>MANAGER_FIRST_NAME<br>
                    <span class="label">Téléphone : </span>MANAGER_PHONE<br>
                    <span class="label">Email : </span>MANAGER_EMAIL
                </p>
            </td>
        </tr>
        <?php if (!empty($expert1) || (!empty($expert2))) : ?>
            <tr>
                <td width="310">
                    <h4>1<sup>er</sup> Expert</h4>
                    <p>
                        <span class="label">Nom : </span>EXPERT1_LAST_NAME<br>
                        <span class="label">Prénom : </span>EXPERT1_FIRST_NAME<br>
                        <span class="label">Téléphone : </span>EXPERT1_PHONE<br>
                        <span class="label">Email : </span>EXPERT1_EMAIL
                    </p>
                </td>
                <td width="310">
                    <h4>2<sup>ème</sup> Expert</h4>
                    <p>
                        <span class="label">Nom : </span>EXPERT2_LAST_NAME<br>
                        <span class="label">Prénom : </span>EXPERT2_FIRST_NAME<br>
                        <span class="label">Téléphone : </span>EXPERT2_PHONE<br>
                        <span class="label">Email : </span>EXPERT2_EMAIL
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
                <h4>TPI_TITLE</h4>
            </td>
        </tr>
      
        <tr>
            <th width="100" class="lowerline">Domaine</th>
            <td width="510" class="lowerline">CFC_DOMAIN</td>
        </tr>
        <tr>
            <th width="100" class="lowerline">Dates</th>
            <td width="510" class="lowerline">du JJ MMMM AAAA au JJ MMMM AAAA, de HH:MM à HH:MM</td>
        </tr>
        <tr>
            <th width="100" class="lowerline">Lieu où se déroule le travail</th>
            <td width="510" class="lowerline">WORKPLACE</td>
        </tr>
        <tr>
            <th width="100" class="lowerline">Résumé</th>
            <td width="510" class="lowerline">ABSTRACT</td>
        </tr>
    </table>

    
    <h2 class="attention">Rappel</h2>
    <p class="attention">Il est interdit au candidat de prendre connaissance de l'énoncé du travail de TPI avant le début de celui-ci.
        <br>L'énoncé lui sera transmis par les experts, par messagerie, le matin du 1er jour du TPI avant 7h30</p>
    <hr>
    <p>Document soumis au collège d'experts le : JJ MMMM AAAA à HH:MM</p>

</page>

<page backtop="25mm" backbottom="25mm" backleft="10mm" backright="10mm">
    <page_header>
        <table class="page_header">
            <tr>
                <td style="width: 100%; text-align: right">
                    Procédure de qualification : 88600/1/2/3 Informaticienne CFC/Informaticien CFC (Ordonnance 2014)<br>
                    Enoncé du TPI, Candidat-e : CANDIDATE_LASTNAME CANDIDATE_FIRSTNAME</td>
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

    TPI_DESCRIPTION

    <hr>
</page>

<page backtop="25mm" backbottom="15mm" backleft="10mm" backright="10mm">
    <page_header>
        <table class="page_header">
            <tr>
                <td style="width: 100%; text-align: right">
                    Procédure de qualification : 88600/1/2/3 Informaticienne CFC/Informaticien CFC (Ordonnance 2014)<br>
                    Enoncé du TPI, Candidat-e : CANDIDATE_LASTNAME CANDIDATE_FIRSTNAME</td>
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
        BOUCLE SUR LES CRITERE D'EVALUATION A14 à A20
            <tr>
                <th width="60">Axx</th>
                <td width="580">DESCRIPTION</td>
            </tr>
            <tr>
                <td colspan="2">
                    <hr>
                </td>
            </tr>
        FIN DE BOUCLE
    </table>
</page>
