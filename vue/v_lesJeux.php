<!-- page start-->
<div class="col-sm-6">
	<section class="panel">
		<div class="chat-room-head">
			<h3><i class="fa fa-angle-right"></i> Gérer les jeux</h3>
		</div>
		<div class="panel-body">
			<table class="table table-striped table-advance table-hover">
				<thead>
					<tr class="tableau-entete">
						<th><i class="fa fa-bullhorn"></i> Référence</th>
						<th><i class="fa fa-bookmark"></i> Jeux</th>
						<th><i class="fa fa-bullhorn"></i> Identifiant Plateforme</th>
						<th><i class="fa fa-bullhorn"></i> Identifiant Pegi</th>
						<th><i class="fa fa-bullhorn"></i> Identifiant Genre</th>
						<th><i class="fa fa-bullhorn"></i> Identifiant Marque</th>
						<th><i class="fa fa-bullhorn"></i> Prix</th>
						<th><i class="fa fa-bullhorn"></i> Date de Parution</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<!-- formulaire pour ajouter un nouveau genre-->
					<tr>
						<form action="index.php?uc=gererJeux" method="post">
							<td>
								<input type="text" id="txtLibJeu" name="txtLibJeu" size="24" required minlength="1" maxlength="24" placeholder="refJeu" title="De 1 à 24 caractères" />
							</td>
							<td>
								<input type="text" id="txtLibJeu" name="txtLibJeu" size="24" required minlength="1" maxlength="24" placeholder="Nom" title="De 1 à 24 caractères" />
							</td>
							<td>
								<input type="text" id="txtLibJeu" name="txtLibJeu" size="24" required minlength="1" maxlength="24" placeholder="idPlateforme" title="De 1 à 24 caractères" />
							</td>
							<td>
								<input type="text" id="txtLibJeu" name="txtLibJeu" size="24" required minlength="1" maxlength="24" placeholder="idPegi" title="De 1 à 24 caractères" />
							</td>
							<td>
								<input type="text" id="txtLibJeu" name="txtLibJeu" size="24" required minlength="1" maxlength="24" placeholder="idGenre" title="De 1 à 24 caractères" />
							</td>
							<td>
								<input type="text" id="txtLibJeu" name="txtLibJeu" size="24" required minlength="1" maxlength="24" placeholder="idMarque" title="De 1 à 24 caractères" />
							</td>
							<td>
								<input type="text" id="txtLibJeu" name="txtLibJeu" size="24" required minlength="1" maxlength="24" placeholder="prix" title="De 1 à 24 caractères" />
							</td>
							<td>
								<input type="text" id="txtLibJeu" name="txtLibJeu" size="24" required minlength="1" maxlength="24" placeholder="dateParution" title="De 1 à 24 caractères" />
							</td>
							<td>
								<button class="btn btn-primary btn-xs" type="submit" name="cmdAction" value="ajouterNouveauJeu" title="Enregistrer nouveau jeu"><i class="fa fa-save"></i></button>
								<button class="btn btn-info btn-xs" type="reset" title="Effacer la saisie"><i class="fa fa-eraser"></i></button>
							</td>
						</form>
					</tr>

					<?php
					foreach ($tbJeux as $jeu) {
					?>
						<tr>

							<!-- formulaire pour modifier et supprimer les genres-->
							<form action="index.php?uc=gererJeux" method="post">
								<td><?php echo $jeu->refJeu; ?><input type="hidden" name="txtIdJeu" value="<?php echo $jeu->refJeu; ?>" /></td>
								<td><?php
									if ($jeu->nom != $idJeuModif) {
										echo $jeu->nom;
									?>
								</td>
								<td><?php
										echo $jeu->idPlateforme;
									?>
								</td>
								<td><?php
										echo $jeu->idPegi;
									?>
								</td>
								<td><?php
										echo $jeu->idGenre;
									?>
								</td>
								<td><?php
										echo $jeu->idMarque;
									?>
								</td>
								<td><?php
										echo $jeu->prix;
									?>
								</td>
								<td><?php
										echo $jeu->dateParution;
									?>
								</td>
								<td>
									<?php if ($notification != 'rien' && $jeu->identifiant == $idJeuNotif) {
											echo '<button class="btn btn-success btn-xs"><i class="fa fa-check"></i>' . $notification . '</button>';
										} ?>
									<button class="btn btn-primary btn-xs" type="submit" name="cmdAction" value="demanderModifierJeu" title="Modifier"><i class="fa fa-pencil"></i></button>
									<button class="btn btn-danger btn-xs" type="submit" name="cmdAction" value="supprimerJeu" title="Supprimer" onclick="return confirm('Voulez-vous vraiment supprimer ce Jeu ?');"><i class="fa fa-trash-o "></i></button>
								</td>

							<?php
									} else {
							?><input type="text" id="txtLibJeu" name="txtLibJeu" size="24" required minlength="4" maxlength="24" value="<?php echo $jeu->nom; ?>" />
								</td>
								<td>
									<button class="btn btn-primary btn-xs" type="submit" name="cmdAction" value="validerModifierJeu" title="Enregistrer"><i class="fa fa-save"></i></button>
									<button class="btn btn-info btn-xs" type="reset" title="Effacer la saisie"><i class="fa fa-eraser"></i></button>
									<button class="btn btn-warning btn-xs" type="submit" name="cmdAction" value="annulerModifierJeu" title="Annuler"><i class="fa fa-undo"></i></button>
								</td>
							<?php
									}
							?>

							</form>

						</tr>
					<?php
					}
					?>
				</tbody>
			</table>

		</div><!-- fin div panel-body-->
	</section><!-- fin section genres-->
</div><!--fin div col-sm-6-->