<?php
require 'view_begin.php';
$menu = [['link' => '?controller=gestionnaire&action=missions', 'name' => 'Missions'],
    ['link' => '?controller=gestionnaire&action=clients', 'name' => 'Clients'],
    ['link' => '?controller=gestionnaire&action=prestataires', 'name' => 'Prestataires'],
    ['link' => '?controller=gestionnaire&action=commerciaux', 'name' => 'Commerciaux']];
require 'view_header.php';
?>

<div class='main-contrainer'>
    <div class="dashboard-container">
        <h1>Missions</h1>
        <?php require_once 'view_dashboard.php'; ?>
        <div class="add-mission-container">
            <button type="button" class="button-primary" onclick="">+ Créer Mission</button>
        </div>
    </div>
</div>

<?php
require 'view_end.php';
?>
