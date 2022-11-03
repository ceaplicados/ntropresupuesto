<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once("../../dep/interface_admin.php");
require_once("../../dep/clases/DaoEstados.php");
require_once("../../dep/clases/DaoVersionesPresupuesto.php");
$DaoEstados=new DaoEstados();
$DaoVersionesPresupuesto=new DaoVersionesPresupuesto();

interfaceHeader("Presupuestos");

?>
	<div class="container" id="listPresupuestos">
		<h5>Presupuestos cargados</h5>
		<p class="codigo">
			<span class="version"></span> Id de la versión
			<b class="actual"><span class="version"></span></b> Versión oficial del año <b>OG</b>: Por Objeto de Gasto <b>PP</b>: Por Programa Presupuestal
		</p>
		<?php foreach($DaoEstados->showAll() as $Estado){ ?>
		<div class="estado" data-id="<?php echo($Estado->getId()); ?>">
		<h6><?php echo($Estado->getNombre()); ?></h6>
		<?php 
			$presupuestos=$DaoVersionesPresupuesto->getByEstado($Estado->getId()); 
			if(count($presupuestos)>0){
				$years=array();
				$years_clave=array();
				foreach($presupuestos as $presupuesto){
					if(!isset($years[$presupuesto->getAnio()])){
						$years[$presupuesto->getAnio()]=array();
						array_push($years_clave, $presupuesto->getAnio());
					}
					if(!isset($years[$presupuesto->getAnio()][$presupuesto->getNombre()])){
						$years[$presupuesto->getAnio()][$presupuesto->getNombre()]=array();
					}
					array_push($years[$presupuesto->getAnio()][$presupuesto->getNombre()],$presupuesto);
				}
				sort($years_clave);
				?>
				<table id="versionesPresupuestos" class="bordered">
					<thead>
						<tr>
							<th>Año</th>
							<th>Proyecto</th>
							<th>Autorizado</th>
							<th>Modificado</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach($years_clave as $year){ ?>
						<tr>
							<td class="anio"><?php echo($year); ?></td>
							<td>
								<?php if(isset($years[$year]["Proyecto"])){ ?>
								<ul><?php foreach($years[$year]["Proyecto"] as $version){ ?>
									<li <?php if($version->getActual()==1){ echo('class="actual"'); } ?> data-id="<?php echo($version->getId()); ?>" data-tipo="Proyecto">
										<span class="version"><?php echo($version->getId()); ?></span> 
										<span class="ObjetoGasto">OG: <?php if($version->getObjetoGasto()==1){ echo('<i class="fa fa-check-circle teal-text" aria-hidden="true"></i>'); }else{ echo('<i class="fa fa-times-circle red-text" aria-hidden="true"></i>'); } ?></span>
										<span class="ProgramaPresupuestal">PP: <?php if($version->getProgramaPresupuestal()==1){ echo('<i class="fa fa-check-circle teal-text" aria-hidden="true"></i>'); }else{ echo('<i class="fa fa-times-circle red-text" aria-hidden="true"></i>'); } ?></span>
										<a class="inconsistencias" target="_blank" href="inconsistencias_presupuestos.php?Id=<?php echo($version->getId()); ?>"><i class="fa fa-heartbeat amber-text" aria-hidden="true"></i></a>
										<span class="fecha">Publicado el <?php echo($DaoEstados->formatFecha($version->getFecha(), 1)); ?></span>
									</li>
								<?php } ?>
								</ul>
								<?php } ?>
							</td>
							<td>
								<?php if(isset($years[$year]["Autorizado"])){ ?>
								<ul><?php foreach($years[$year]["Autorizado"] as $version){ ?>
									<li <?php if($version->getActual()==1){ echo('class="actual"'); } ?> data-id="<?php echo($version->getId()); ?>" data-tipo="Autorizado">
									<span class="version"><?php echo($version->getId()); ?></span> 
									<span class="ObjetoGasto">OG: <?php if($version->getObjetoGasto()==1){ echo('<i class="fa fa-check-circle teal-text" aria-hidden="true"></i>'); }else{ echo('<i class="fa fa-times-circle red-text" aria-hidden="true"></i>'); } ?></span>
									<span class="ProgramaPresupuestal">PP: <?php if($version->getProgramaPresupuestal()==1){ echo('<i class="fa fa-check-circle teal-text" aria-hidden="true"></i>'); }else{ echo('<i class="fa fa-times-circle red-text" aria-hidden="true"></i>'); } ?></span>
									<a class="inconsistencias" target="_blank" href="inconsistencias_presupuestos.php?Id=<?php echo($version->getId()); ?>"><i class="fa fa-heartbeat amber-text" aria-hidden="true"></i></a>
									<span class="fecha">Publicado el <?php echo($DaoEstados->formatFecha($version->getFecha(), 1)); ?></span>
								</li>
								<?php } ?>
								</ul>
								<?php } ?>
							</td>
							<td>
								<?php if(isset($years[$year]["Modificado"])){ ?>
								<ul><?php foreach($years[$year]["Modificado"] as $version){ ?>
									<li <?php if($version->getActual()==1){ echo('class="actual"'); } ?> data-id="<?php echo($version->getId()); ?>" data-tipo="Modificado">
									<span class="version"><?php echo($version->getId()); ?></span> 
									<span class="ObjetoGasto">OG: <?php if($version->getObjetoGasto()==1){ echo('<i class="fa fa-check-circle teal-text" aria-hidden="true"></i>'); }else{ echo('<i class="fa fa-times-circle red-text" aria-hidden="true"></i>'); } ?></span>
									<span class="ProgramaPresupuestal">PP: <?php if($version->getProgramaPresupuestal()==1){ echo('<i class="fa fa-check-circle teal-text" aria-hidden="true"></i>'); }else{ echo('<i class="fa fa-times-circle red-text" aria-hidden="true"></i>'); } ?></span>
									<a class="inconsistencias" target="_blank" href="inconsistencias_presupuestos.php?Id=<?php echo($version->getId()); ?>"><i class="fa fa-heartbeat amber-text" aria-hidden="true"></i></a>
									<span class="fecha">Publicado el <?php echo($DaoEstados->formatFecha($version->getFecha(), 1)); ?></span>
								</li>
								<?php } ?>
								</ul>
								<?php } ?>
							</td>
						</tr>
						<?php } ?>
					</tbody>
				</table>
				<?php
			}else{
				echo('<p>Sin presupuestos cargados</p>');
			} ?>
		</div>
		<?php } ?>
		<p class="right-align">
			Cargar nuevo presupuesto:
		</p>
		<p class="right-align">
			<a class="btn waves-effect waves-light" onclick="show_addPresupestosOG()">1. Por Objeto de Gasto</a>
		</p>
		<p class="right-align">
			<a class="btn waves-effect waves-light" onclick="show_addPresupestosPP()">2. Por Programa Presupuestal</a>
		</p>
		<p class="">
			<br/>
			<br/>
			<a class="btn waves-effect waves-light" onclick="consolidarProgramasDuplicados()">Consolidar programas duplicados</a>
		</p>
	</div>
	<div class="container" id="addPresupestosOG">
		<h5>Añadir presupuestos por Objeto de Gasto</h5>
		<div class="row" id="paso1">
			<div class="col s12">
				<h6>1. Selecciona el archivo</h6>
				<div class="file-field input-field">
					<div class="btn">
						<span>Archivo</span>
						<input type="file" id="archivoExcel" accept=".xlsx">
					</div>
					<div class="file-path-wrapper">
						<input class="file-path validate" type="text">
					</div>
				</div>
			</div>
			<div class="col s12 m6 input-field">
				<select id="estado">
					<option value="" disabled selected>Selecciona un estado</option>
					<?php foreach($DaoEstados->showAll() as $Estado){ ?>
					<option value="<?php echo($Estado->getId()); ?>"><?php echo($Estado->getNombre()); ?></option>
					<?php } ?>
				</select>
				<label for="estado">Estado</label>
			</div>
			<div class="col s12 m6 input-field">
				<input type="number" id="anio" placeholder="yyyy">
				<label for="anio">Año del presupuesto</label>
			</div>
			<div class="col s12 tipoPresupuesto">
				<p class="label">Tipo de presupuesto:</p>
				<div>
					<input name="nombre" type="radio" id="nombre_proyecto" value="Proyecto"/>
					<label for="nombre_proyecto">Proyecto</label>
				</div>
				<div>
					<input name="nombre" type="radio" id="nombre_autorizado"  value="Autorizado"/>
					<label for="nombre_autorizado">Autorizado</label>
				</div>
				<div>
					<input name="nombre" type="radio" id="nombre_modificado"  value="Modificado"/>
					<label for="nombre_modificado">Modificado</label>
				</div>
			</div>
			<div class="col s12 input-field">
				<input type="text" id="descripcion">
				<label for="descripcion">Descripción del presupuesto</label>
			</div>
			<div class="col s12 m6 input-field">
				<input type="text" class="datepicker" id="fecha">
				<label for="fecha">Fecha de publicación</label>
			</div>
			<div class="col s12 right-align">
				<a class="btn waves-effect waves-light" id="procesarExcel" onclick="procesarExcel()">Procesar</a>
			</div>
		</div>
		<div class="row" id="paso2">
			<div class="col s12">
				<h6>2. Selecciona las columnas</h6>
				<table>
					<thead>
						<tr>
							<th>Dato</th>
							<th>Columna</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>Clave Unidad Presupuestal</td>
							<td>
								<div class="input-field">
									<select id="columnaUP">
										<option value="" disabled selected>Selecciona una columna</option>
									</select>
								</div>
							</td>
						</tr>
						<tr>
							<td>Clave Unidad Responsable</td>
							<td>
								<div class="input-field">
									<select id="columnaUR">
										<option value="" disabled selected>Selecciona una columna</option>
									</select>
								</div>
							</td>
						</tr>
						<tr>
							<td>Clave Objeto de Gasto</td>
							<td>
								<div class="input-field">
									<select id="columnaClaveOG">
										<option value="" disabled selected>Selecciona una columna</option>
									</select>
								</div>
							</td>
						</tr>
						<tr>
							<td>Descripción Objeto de Gasto</td>
							<td>
								<div class="input-field">
									<select id="columnaDescripcionOG">
										<option value="" disabled selected>Selecciona una columna</option>
									</select>
								</div>
							</td>
						</tr>
						<tr>
							<td>Monto asignado</td>
							<td>
								<div class="input-field">
									<select id="columnaMonto">
										<option value="" disabled selected>Selecciona una columna</option>
									</select>
								</div>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="col s12 right-align">
				<a class="btn waves-effect waves-light" id="seleccionarColumnas" onclick="seleccionarColumnas()">Guardar</a>
			</div>
		</div>
		<div class="row" id="paso3">
			<div class="col s12">
				<h6>3. Completa la información faltante</h6>
				<p>Filas analizadas: <b class="rowsCount"></b></p>
			</div>
			<div class="col s12 unidadesPresupuestales">
				<p>Unidades Presupuestales:</p>
				<table>
					<thead>
						<tr>
							<th>Clave</th>
							<th>Nombre</th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
			</div>
			<div class="col s12 unidadesResponsables">
				<p>Unidades Responsables:</p>
				<table>
					<thead>
						<tr>
							<th>Unidad Presupuestal</th>
							<th>Clave</th>
							<th>Nombre</th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
			</div>
			<div class="col s12 sinNuevas">
				<p>No existen Unidades Presupuestales ni Unidades Responsables nuevas.</p>
			</div>
			<div class="col s12 right-align">
				<a class="btn waves-effect waves-light" id="crearUP_UR" onclick="crearUP_UR()">Procesar</a>
			</div>
		</div>
	</div>
	<div class="container" id="addPresupestosPP">
		<h5>Añadir presupuestos por Programa Presupuestal</h5>
		<div class="row" id="paso1PP">
			<div class="col s12">
				<h6>1. Selecciona el archivo</h6>
				<div class="file-field input-field">
					<div class="btn">
						<span>Archivo</span>
						<input type="file" id="archivoExcelPP" accept=".xlsx">
					</div>
					<div class="file-path-wrapper">
						<input class="file-path validate" type="text">
					</div>
				</div>
			</div>
			<div class="col s12 m6 input-field">
				<select id="estadoPP" onchange="getVersionesEstado()">
					<option value="" disabled selected>Selecciona un estado</option>
					<?php foreach($DaoEstados->showAll() as $Estado){ ?>
					<option value="<?php echo($Estado->getId()); ?>"><?php echo($Estado->getNombre()); ?></option>
					<?php } ?>
				</select>
				<label for="estado">Estado</label>
			</div>
			<div class="col s12">
				<p class="label">Versión del presupuesto a cargar:</p>
				<div class="versionPresupuestoPP">
					<p>Selecciona un estado.</p>
				</div>
			</div>
			<div class="col s12 right-align">
				<a class="btn waves-effect waves-light" id="procesarExcelPP" onclick="procesarExcelPP()">Procesar</a>
			</div>
		</div>
		<div class="row" id="paso2PP">
			<div class="col s12">
				<h6>2. Selecciona las columnas</h6>
				<table >
					<thead>
						<tr>
							<th>Dato</th>
							<th>Columna</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>Clave Unidad Presupuestal</td>
							<td>
								<div class="input-field">
									<select id="columnaUP_PP">
										<option value="" disabled selected>Selecciona una columna</option>
									</select>
								</div>
							</td>
						</tr>
						<tr>
							<td>Clave Unidad Responsable</td>
							<td>
								<div class="input-field">
									<select id="columnaUR_PP">
										<option value="" disabled selected>Selecciona una columna</option>
									</select>
								</div>
							</td>
						</tr>
						<tr>
							<td>Clave Programa Presupuestal</td>
							<td>
								<div class="input-field">
									<select id="columnaClavePP">
										<option value="" disabled selected>Selecciona una columna</option>
									</select>
								</div>
							</td>
						</tr>
						<tr>
							<td>Nombre Programa Presupuestal</td>
							<td>
								<div class="input-field">
									<select id="columnaNombre_PP">
										<option value="" disabled selected>Selecciona una columna</option>
									</select>
								</div>
							</td>
						</tr>
						<tr>
							<td>Monto asignado</td>
							<td>
								<div class="input-field">
									<select id="columnaMontoPP">
										<option value="" disabled selected>Selecciona una columna</option>
									</select>
								</div>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="col s12 right-align">
				<a class="btn waves-effect waves-light" id="seleccionarColumnasPP" onclick="seleccionarColumnasPP()">Guardar</a>
			</div>
		</div>
		<div class="row" id="paso3PP">
			<div class="col s12">
				<h6>3. Revisa la información</h6>
				<p>Filas analizadas: <b class="rowsCount"></b></p>
			</div>
			<div class="col s12 programasPresupuestales">
				<p>Programas Presupuestales nuevos:</p>
				<table id="missingPP">
					<thead>
						<tr>
							<th>Clave</th>
							<th>Nombre</th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
			</div>
			<div class="col s12 sinNuevas">
				<p>No existen Programas Presupuestales nuevos.</p>
			</div>
			<div class="col s12 right-align">
				<a class="btn waves-effect waves-light" id="writePP" onclick="writePP()">Procesar</a>
			</div>
		</div>
	</div>
	<div id="modalActual" class="modal">
		<div class="modal-content">
			<h5>Cambiar versión principal</h5>
			<p>¿Estás segurx de querer establecer la versión <b class="version"></b> como principal para el <b class="anio"></b> en <b class="estado"></b>?</p>
		</div>
		<div class="modal-footer">
			<a class="btn waves-effect waves-light" onclick="setPrincipal()" id="setPrincipal">Aceptar</a>
		</div>
	</div>
<?php 
interfaceFooter();