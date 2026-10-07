<?php
  include 'db.php';
  $sql = 'SELECT * FROM cours WHERE dateFin IS NULL OR dateFin >= CURDATE() ORDER BY dateDebut ASC';
  $req = $db->prepare($sql);
  $req->execute();
  $cours = $req->fetchAll();
?>

<style>
  .table-wrap { background: #fff; border: 0.5px solid #e5e5e5; border-radius: 12px; overflow: auto; }
  table { width: 100%; border-collapse: collapse; min-width: 700px; }
  thead tr { border-bottom: 0.5px solid #e5e5e5; }
  thead th { font-size: 11px; text-transform: uppercase; letter-spacing: 0.06em; color: #999; font-weight: 500; padding: 13px 18px; text-align: left; white-space: nowrap; }
  tbody tr { border-bottom: 0.5px solid #f0f0f0; transition: background 0.1s; }
  tbody tr:last-child { border-bottom: none; }
  tbody tr:hover { background: #fafafa; }
  tbody td { font-size: 13.5px; color: #333; padding: 13px 18px; vertical-align: middle; white-space: nowrap; }
  .langue-cell { display: flex; align-items: center; gap: 10px; }
  .langue-icon { width: 32px; height: 32px; border-radius: 8px; background: #EEEDFE; color: #534AB7; display: flex; align-items: center; justify-content: center; font-size: 15px; flex-shrink: 0; }
  .langue-name { font-weight: 500; color: #111; }
  .badge { display: inline-flex; align-items: center; gap: 4px; font-size: 12px; padding: 3px 10px; border-radius: 20px; font-weight: 500; }
  .badge-niveau  { background: #EEEDFE; color: #3C3489; }
  .badge-dispo   { background: #E1F5EE; color: #085041; }
  .badge-complet { background: #FCEBEB; color: #791F1F; }
  .places { display: flex; align-items: center; gap: 8px; }
  .places-bar { width: 80px; height: 5px; background: #f0f0f0; border-radius: 10px; overflow: hidden; }
  .places-fill { height: 100%; border-radius: 10px; }
  .places-text { font-size: 12.5px; color: #888; }
  .prix { font-weight: 500; color: #111; }
  .date { color: #666; font-size: 13px; }
  .empty { padding: 3rem; text-align: center; color: #aaa; font-size: 14px; }
  .empty i { font-size: 32px; display: block; margin-bottom: 8px; color: #ccc; }
</style>

<div class="table-wrap">
  <table>
    <thead>
      <tr>
        <th>Langue</th>
        <th>Niveau</th>
        <th>Prix</th>
        <th>Date début</th>
        <th>Date fin</th>
        <th>Places</th>
        <th>État</th>
      </tr>
    </thead>
    <tbody>
      <?php if (count($cours) > 0): ?>
        <?php foreach ($cours as $c): ?>
          <?php
            $total     = (int) $c['placesTotal'];
            $restantes = (int) $c['placesRestantes'];
            $pct       = $total > 0 ? round(($restantes / $total) * 100) : 0;
            $barColor  = $pct > 50 ? '#1D9E75' : ($pct > 20 ? '#EF9F27' : '#E24B4A');
            $dispo     = $restantes > 0;
          ?>
          <tr>
            <td>
              <div class="langue-cell">
                <div class="langue-icon"><i class="ti ti-flag"></i></div>
                <span class="langue-name"><?= htmlspecialchars($c['langue']) ?></span>
              </div>
            </td>
            <td><span class="badge badge-niveau"><?= htmlspecialchars($c['niveau']) ?></span></td>
            <td><span class="prix"><?= htmlspecialchars($c['prix']) ?> DH</span></td>
            <td><span class="date"><?= htmlspecialchars($c['dateDebut']) ?></span></td>
            <td><span class="date"><?= htmlspecialchars($c['dateFin']) ?></span></td>
            <td>
              <div class="places">
                <div class="places-bar">
                  <div class="places-fill" style="width:<?= $pct ?>%; background:<?= $barColor ?>;"></div>
                </div>
                <span class="places-text"><?= $restantes ?>/<?= $total ?></span>
              </div>
            </td>
            <td>
              <span class="badge <?= $dispo ? 'badge-dispo' : 'badge-complet' ?>">
                <i class="ti <?= $dispo ? 'ti-circle-check' : 'ti-circle-x' ?>"></i>
                <?= $dispo ? 'Disponible' : 'Complet' ?>
              </span>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr>
          <td colspan="7">
            <div class="empty">
              <i class="ti ti-book-off"></i>
              Aucun cours enregistré
            </div>
          </td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>