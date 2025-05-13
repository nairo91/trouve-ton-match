<?php
require_once 'models/Statistics.php';
require_once 'models/Tournament.php';
require_once 'utils/Session.php';
require_once 'libs/fpdf/fpdf.php';

class PdfController {
    private $statisticsModel;
    
    public function __construct() {
        $this->statisticsModel = new Statistics();
    }
    
    // Exporter les statistiques globales en PDF
    public function exportGlobalStats() {
        // Vérifier que l'utilisateur est analyste
        Session::requireRole('analyste');
        
        // Récupérer les statistiques globales
        $totalMatchs = $this->statisticsModel->getTotalMatches();
        $moyenneButs = $this->statisticsModel->getAverageGoalsPerMatch();
        $matchPlusScore = $this->statisticsModel->getMostScoredMatch();
        $totalTournois = $this->statisticsModel->getTotalTournaments();
        $teamsParticipation = $this->statisticsModel->getTeamsParticipation();
        
        // Créer le PDF
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial','B',16);
        $pdf->Cell(0, 10, 'Statistiques Globales', 0, 1, 'C');
        $pdf->Ln(5);
        
                // Statistiques sur les matchs
                $pdf->SetFont('Arial','B',12);
                $pdf->Cell(0, 10, 'Statistiques sur les matchs :', 0, 1);
                $pdf->SetFont('Arial','',10);
                $pdf->Cell(0, 10, 'Nombre total de matchs joues : ' . $totalMatchs, 0, 1);
                $pdf->Cell(0, 10, 'Moyenne de buts par match : ' . $moyenneButs, 0, 1);
                
                if ($matchPlusScore) {
                    $matchNom = $matchPlusScore['equipe1'] . " VS " . $matchPlusScore['equipe2'];
                    $maxButs = $matchPlusScore['total_buts'];
                    $pdf->Cell(0, 10, 'Match le plus score : ' . $matchNom . ' avec ' . $maxButs . ' buts cumules', 0, 1);
                }
                
                $pdf->Ln(5);
                
                // Statistiques sur les tournois
                $pdf->SetFont('Arial','B',12);
                $pdf->Cell(0, 10, 'Statistiques sur les tournois :', 0, 1);
                $pdf->SetFont('Arial','',10);
                $pdf->Cell(0, 10, 'Nombre total de tournois organises : ' . $totalTournois, 0, 1);
                $pdf->Ln(5);
                
                // Participation par équipe
                $pdf->SetFont('Arial','B',12);
                $pdf->Cell(0, 10, 'Participation par equipe', 0, 1);
                $pdf->SetFont('Arial','B',10);
                
                // En-têtes du tableau
                $pdf->Cell(25, 8, 'ID Equipe', 1, 0, 'C');
                $pdf->Cell(50, 8, 'Nom Equipe', 1, 0, 'C');
                $pdf->Cell(40, 8, 'Tournois part.', 1, 0, 'C');
                $pdf->Cell(40, 8, 'Taux (%)', 1, 1, 'C');
                
                $pdf->SetFont('Arial','',10);
                foreach ($teamsParticipation as $team) {
                    $pdf->Cell(25, 8, $team['idEquipe'], 1, 0, 'C');
                    $pdf->Cell(50, 8, $team['nomEquipe'], 1, 0, 'C');
                    $pdf->Cell(40, 8, $team['nbParticipations'], 1, 0, 'C');
                    $pdf->Cell(40, 8, round($team['rate'], 2).' %', 1, 1, 'C');
                }
                
                // Nettoyer le buffer de sortie
                if (ob_get_length()) {
                    ob_end_clean();
                }
                
                // Sortie du PDF avec téléchargement
                $pdf->Output('D', 'Statistiques_Globales.pdf');
                exit;
            }
            
            // Exporter les données d'un tournoi en PDF
            public function exportTournament() {
                // Vérifier que l'utilisateur est connecté
                Session::requireLogin();
                
                if (!isset($_GET['tournoi'])) {
                    header('Location: index.php?controller=Tournament&action=show');
                    exit();
                }
                
                $idTournois = intval($_GET['tournoi']);
                
                // Récupérer les informations du tournoi
                $tournamentModel = new Tournament();
                $tournament = $tournamentModel->getById($idTournois);
                
                if (!$tournament) {
                    header('Location: index.php?controller=Tournament&action=show');
                    exit();
                }
                
                // Récupérer les matchs du tournoi par phase
                $phases = ['Quart de finale', 'Demi-finale', 'Finale'];
                $matchesByPhase = [];
                
                foreach ($phases as $phase) {
                    $matchesByPhase[$phase] = $tournamentModel->getMatchesByPhase($idTournois, $phase);
                }
                
                // Récupérer les statistiques des équipes
                $teamStats = $tournamentModel->getTeamStatistics($idTournois);
                
                // Créer le PDF
                $pdf = new FPDF();
                $pdf->AddPage();
                $pdf->SetFont('Arial','B',16);
                $pdf->Cell(0, 10, 'Tournoi: ' . $tournament['nomTournois'], 0, 1, 'C');
                $pdf->SetFont('Arial','',12);
                $pdf->Cell(0, 10, 'Ville: ' . $tournament['villeTournois'], 0, 1, 'C');
                $pdf->Ln(5);
                
                // Afficher les matchs par phase
                foreach ($phases as $phase) {
                    if (!empty($matchesByPhase[$phase])) {
                        $pdf->SetFont('Arial','B',14);
                        $pdf->Cell(0, 10, $phase, 0, 1, 'L');
                        
                        $pdf->SetFont('Arial','B',10);
                        $pdf->Cell(70, 8, 'Equipe 1', 1, 0, 'C');
                        $pdf->Cell(20, 8, 'Score 1', 1, 0, 'C');
                        $pdf->Cell(20, 8, 'Score 2', 1, 0, 'C');
                        $pdf->Cell(70, 8, 'Equipe 2', 1, 1, 'C');
                        
                        $pdf->SetFont('Arial','',10);
                        foreach ($matchesByPhase[$phase] as $match) {
                            $pdf->Cell(70, 8, $match['equipe1'], 1, 0, 'C');
                            $pdf->Cell(20, 8, $match['scoreEquipe1'], 1, 0, 'C');
                            $pdf->Cell(20, 8, $match['scoreEquipe2'], 1, 0, 'C');
                            $pdf->Cell(70, 8, $match['equipe2'], 1, 1, 'C');
                        }
                        
                        $pdf->Ln(5);
                    }
                }
                
                // Afficher les statistiques des équipes
                $pdf->SetFont('Arial','B',14);
                $pdf->Cell(0, 10, 'Statistiques des Equipes', 0, 1, 'L');
                
                $pdf->SetFont('Arial','B',10);
                $pdf->Cell(60, 8, 'Equipe', 1, 0, 'C');
                $pdf->Cell(30, 8, 'Victoires', 1, 0, 'C');
                $pdf->Cell(30, 8, 'Defaites', 1, 0, 'C');
                $pdf->Cell(30, 8, 'Buts Marques', 1, 0, 'C');
                $pdf->Cell(30, 8, 'Buts Encaisses', 1, 1, 'C');
                
                $pdf->SetFont('Arial','',10);
                foreach ($teamStats as $stat) {
                    $pdf->Cell(60, 8, $stat['nomEquipe'], 1, 0, 'C');
                    $pdf->Cell(30, 8, $stat['victoires'], 1, 0, 'C');
                    $pdf->Cell(30, 8, $stat['defaites'], 1, 0, 'C');
                    $pdf->Cell(30, 8, $stat['buts_marques'], 1, 0, 'C');
                    $pdf->Cell(30, 8, $stat['buts_encaisses'], 1, 1, 'C');
                }
                
                // Nettoyer le buffer de sortie
                if (ob_get_length()) {
                    ob_end_clean();
                }
                
                // Sortie du PDF avec téléchargement
                $pdf->Output('D', 'Tournoi_' . $tournament['nomTournois'] . '.pdf');
                exit;
            }
        }
        ?>
        