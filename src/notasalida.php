<div class="alert alert-info" role="alert">
    <strong>Estimado usuario!</strong> Los campos remarcados con <label class="text-danger">*</label> son necesarios.
    <br>
    <strong>En esta ventana puede salir los productos del stock por DETERIORO o VENCIMIENTO.</strong>
</div>

<div class="card shadow-lg">
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow-lg">
                    <div class="card-header bg-primary text-white">
                        Salida de Productos del kardex
                    </div>
                    <div class="card-body">
                        <form action="nsalida.php" method="post" autocomplete="off" id="formulario">
                            <?php echo isset($alert) ? $alert : ''; ?>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <?PHP
                                        $query_id = mysqli_query($conexion, "SELECT RIGHT(codigomov,9) as codigo FROM tmovim
                                                ORDER BY codigomov DESC LIMIT 1");
                                        $count = mysqli_num_rows($query_id);
                                        if ($count <> 0) {
                                            $data_id = mysqli_fetch_assoc($query_id);
                                            $codigo = $data_id['codigo'] + 1;
                                        } else {
                                            $codigo = 1;
                                        }
                                        $tahun          = date("Ym");
                                        $buat_id = str_pad($codigo, 9, "0", STR_PAD_LEFT);
                                        $codigo  = "TS-$tahun-$buat_id";
                                        ?>
                                        <?PHP
                                        $cidm = mysqli_query($conexion, "SELECT idmov as codigo FROM tmovim
                                                ORDER BY idmov DESC LIMIT 1");
                                        $count = mysqli_num_rows($cidm);
                                        if ($count <> 0) {
                                            $data_idm = mysqli_fetch_assoc($cidm);
                                            $cidmovs = $data_idm['codigo'] + 1;
                                        } else {
                                            $cidmovs= 1;
                                        }
                                        ?>
                                        <label for="codigomovs" class=" text-dark font-weight-bold">Cod. Transferencia<span class="text-danger">*</span></label>
                                        <input type="text" name="codigomovs" id="codigomovs" value="<?php echo $codigo; ?>" class="form-control" readonly>
                                        <input type="hidden" id="tts" name="tts" value="S">
                                        <input type="hidden" id="idmovs" name="idmovs" value="<?php echo $cidmovs;?>">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="cods" class=" text-dark font-weight-bold">Cod. Producto<span class="text-danger">*</span></label>
                                        <input type="text" name="codmeds" id="codmeds"  class="form-control" readonly>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="codigobarras" class=" text-dark font-weight-bold"><i class="fas fa-barcode"></i> Código de Barras</label>
                                        <input type="text" placeholder="código de barras" name="codigobarras" id="codigobarras" class="form-control" readonly>
                                        <input type="hidden" id="ids" name="ids">
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label for="buscarproducto" class=" text-dark font-weight-bold">Producto<span class="text-danger">*</span></label>
                                        <input type="text" placeholder="Ingrese nombre del producto" name="prosalida" id="prosalida" class="form-control" readonly>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="presentacion" class=" text-dark font-weight-bold">Presentación</label>
                                        <input type="text" id="preses" class="form-control" name="preses" readonly>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="cnc" class=" text-dark font-weight-bold">Concentración</label>
                                        <input type="text" id="cncs" class="form-control" name="cncs" readonly>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="ff" class=" text-dark font-weight-bold">F. Farmaceutica</label>
                                        <input type="text" id="ffs" class="form-control" name="ffs" readonly>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="laboratorio" class=" text-dark font-weight-bold">Laboratorio</label>
                                        <select id="laboratorio" class="form-control" name="laboratorio" readonly>

                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="lotes" class=" text-dark font-weight-bold">Lote<span class="text-danger">*</span></label>
                                        <input type="text" id="lotes" class="form-control" name="lotes" readonly>
                                       <!-- <select class="form-control" id="lotess" name="lotess">
                                            <?php
                                            $lotes = mysqli_query($conexion, "SELECT * FROM mstockalm where med like '%$prosalida%'");
                                            while ($datoslo = mysqli_fetch_assoc($lotes)) { ?>
                                                <option value="<?php echo $datoslo['lote'] ?>"><?php echo $datoslo['lote']?></option>
                                            <?php } ?>
                                        </select> -->
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="regsans" class=" text-dark font-weight-bold">REG. SAN.<span class="text-danger">*</span></label>
                                        <input type="text" id="regsans" name="regsans" class="form-control" readonly>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="precios" class=" text-dark font-weight-bold">Precio<span class="text-danger">*</span></label>
                                        S/.<input type="text" placeholder="precio" class="form-control" name="precios" id="precios" readonly>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="stocks" class=" text-dark font-weight-bold">Stock<span class="text-danger">*</span></label>
                                        <input type="number" placeholder="" class="form-control" name="stocks" id="stocks" oninput="calcula()" readonly>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="cantidads" class=" text-dark font-weight-bold">Cantidad<span class="text-danger">*</span></label>
                                        <input type="number" placeholder="cantidad a salir" class="form-control" min="0" name="cantidads" id="cantidads" oninput="calcula()" required >
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="ts" class=" text-dark font-weight-bold">Total stock<span class="text-danger">*</span></label>
                                        <input type="number" name="ts" id="ts" class="form-control" min="0" max="99999999" step="1" pattern="^[0-9]+" required readonly>
                                    </div>
                                </div>
                                <script>
                                    function calcula(){
                                        var a = Number (document.getElementById('stocks').value );
                                        var b = Number (document.getElementById('cantidads').value );
                                        var c
                                        //var c = a - b;
                                        //document.getElementById('ts').value = c;
                                        if(b>a){
                                            c =  "0" ;
                                            document.getElementById('ts').value = c;
                                        }else{
                                            c = a - b;
                                            document.getElementById('ts').value = c;
                                        }
                                    }
                                </script>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="vencs" class=" text-dark font-weight-bold">Vencimiento<span class="text-danger">*</span></label>
                                        <input id="vencs" class="form-control" type="date" name="vencs" readonly required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="vencs" class=" text-dark font-weight-bold">Concepto<span class="text-danger">*</span></label>
                                        <select id="conceptos" name="conceptos" class="form-control" required>
                                            <option value="">--seleccione--</option>
                                            <option value="DETERIORO">DETERIORO</option>
                                            <option value="VENCIMENTO">VENCIMIENTO</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <input type="submit" value="Registrar" class="btn btn-primary" id="btnAccion">
                                    <input type="button" value="Nuevo" onclick="limpiar()" class="btn btn-success" id="btnNuevo">
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table table-striped table-bordered" id="tbl">
                    <thead class="thead-dark">
                    <tr>
                        <th>Cod Med</th>
                        <th>Producto</th>
                        <th>Lote</th>
                        <th>Reg. San</th>
                        <th>Precio</th>
                        <th>Fech. Vcto</th>
                        <th>Stock</th>
                        <th>Acción</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    include "../conexion.php";

                    $query = mysqli_query($conexion, "select m.*, m.codmed, m.med, m.lote, r.codigoreg as regsan, m.precio, m.vencimiento, m.stock from mstockalm m inner join regsan r on m.idregsan = r.idreg");
                    $result = mysqli_num_rows($query);
                    if ($result > 0) {
                        while ($data = mysqli_fetch_assoc($query)) { ?>
                            <tr>
                                <td><?php echo $data['codmed']; ?></td>
                                <td><?php echo $data['med']; ?></td>
                                <td><?php echo $data['lote']; ?></td>
                                <td><?php echo $data['regsan']; ?></td>
                                <td>S/.<?php echo $data['precio']; ?></td>
                                <td><?php echo $data['vencimiento']; ?></td>
                                <td><?php echo $data['stock']; ?></td>
                                <td>
                                    <a href="#" onclick="nsalidapro(<?php echo $data['idstock']; ?>)" class="btn btn-primary"><i class='fas fa-edit'></i></a>
                                </td>
                            </tr>
                        <?php }
                    } ?>
                    </tbody>

                </table>
            </div>
        </div>
    </div>
</div>