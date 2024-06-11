<!-- Vue permettant de consulter son dashboard comportant les missions, prestataire assigné, composante et consulter son bon de livraison -->
<div class='dashboard__table'>
    <table>
        <thead>
            <tr>
                <?php foreach ($header as $title): ?>
                    <th><?= e($title) ?></th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($dashboard as $row): ?>
                <tr>
                    <?php 
                    $fullNameDisplayed = false;
                    foreach ($row as $cle => $value):
                        if ($cle == 'prenom' || $cle == 'nom'):
                            if (!$fullNameDisplayed): ?>
                                <td><?= e($row['prenom'] . ' ' . $row['nom']) ?></td>
                                <?php $fullNameDisplayed = true; ?>
                            <?php endif; ?>
                        <?php elseif ($cle != 'id_mission'): ?>
                            <td><?= e($value) ?></td>
                        <?php endif;
                    endforeach; ?>
                    <td style="display: flex; justify-content: space-around;">
                        <div style="text-align: center;">
                            <a href="<?= e($bdlLink) ?><?php if (isset($row['id_prestataire'])): echo '&id-prestataire=' . e($row['id_prestataire']); endif; ?>&id=<?= e($row['id_mission']) ?>"><i class="fa fa-eye" aria-hidden="true"></i></a>
                            <p>Consulter</p>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
