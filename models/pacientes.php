<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <link type="text/css" rel="stylesheet" href="css/pacientes.css">
        
        <title></title>
    </head>
    <body>
        
        <header>
            <div class="header-top">
                <img src="images/logo.png" alt="logo_centro_arbol">
                <h3><?php echo "Maryelin Palma"?></h3>
            </div>
            
            <nav class="myBar">
                <a href="/CentroArbol/models/home.php" alt="home">Home</a>
                <a href="/CentroArbol/models/agenda.php" alt="home">Agenda</a>
                <a href="#" alt="home">Terapéutas</a>
                <a class="active" href="#" alt="home">Pacientes</a>
                <a href="#" alt="home">Administración</a>
            </nav>
        </header>
        <main>
            <div class="filters">
                <form method="POST" action="/CentroArbol/models/pacientes.php">
                    <div class="element">
                        <h4>Filtros:</h4><br>
                        <label for="status">Status:</label>
                        <select name="status">
                            <option value="0">Todos</option>
                            <option value="1" selected>En Proceso</option>
                            <option value="2">Cerrado</option>
                        </select>
                    </div>
                    <br>
                    <div class="element">
                        <label for="programa">Programa:</label>
                        <select name="programa">
                            <option value="TODOS" selected>Todos</option>
                            <option value="FONASA">FONASA</option>
                            <option value="ISAPRE">ISAPRE</option>
                            <option value="PPS">PPS</option>
                            <option value="PAP">PAP</option>
                            <option value="PARTICULAR">Particular</option>
                        </select>
                    </div>
                    <br>
                    <div class="element">
                        <label for="sexo">Sexo:</label>
                        <select name="sexo">
                            <option value="TODOS" selected>Todos</option>
                            <option value="FEMENINO">Femenino</option>
                            <option value="MASCULINO">Masculino</option>
                            <option value="OTRO">Otro</option>
                        </select>
                    </div>
                    <br>
                    <div class="element">
                        <label for="therarpist">Terapeuta:</label>
                        <select name="therapist">
                            <option value="TODOS" selected>Todos</option>
                            <!--código php para llenar lista con terapeutas activos-->
                        </select>
                    </div>
                    
                    <br>
                    <div class="element">
                        <label for="attentionDay">Día de atención:</label>
                        <select name="attentionDay">
                            <option value="%" selected>Todos</option>
                            <option value="LU">Lunes</option>
                            <option value="MA">Martes</option>
                            <option value="MI">Miercoles</option>
                            <option value="JU">Jueves</option>
                            <option value="VI">Viernes</option>
                            <option value="SA">Sábado</option>
                            <option value="DO">Domingo</option>
                        </select>
                    </div>
                    <br>
                    <div class="element">
                        <label for="attentionHour">Hora de atención:</label>
                        <select name="attentionHour">
                            <option value="%" selected>Todos</option>
                            <option value="08">08:00</option>
                            <option value="09">09:00</option>
                            <option value="10">10:00</option>
                            <option value="11">11:00</option>
                            <option value="12">12:00</option>
                            <option value="13">13:00</option>
                            <option value="14">14:00</option>
                            <option value="15">15:00</option>
                            <option value="16">16:00</option>
                            <option value="17">17:00</option>
                            <option value="18">18:00</option>
                            <option value="19">19:00</option>
                            <option value="20">20:00</option>
                            <option value="21">21:00</option>
                        </select>
                    </div>
                    <br>
                    <div class="subButton">
                        <input type="submit" class="dd" name="default" value="Buscar">
                    </div>
                </form>
                <br><br><br>
                <div class='advancedSearch'>
                    <button id="btnAS">Busqueda Avanzada</button>
                </div>
            </div>
            <div class="data">
                <table>
                    <thead>
                        <th>Nombre</th>
                        <th>RUT</th>
                        <th>Edad</th>
                        <th>Sexo</th>
                        <th>Programa</th>
                        <th>Status</th>
                        <th>Terapeuta</th>
                        <th>Detalles</th>
                    </thead>
                    <tbody>
                        <?php
                            require_once("bd.php");
                            $connection = new CAdatabase();
                            if($_SERVER["REQUEST_METHOD"]=="POST"){
                                $stmt=null;
                                $fillTable=false;
                                $patient=null;
                                switch($_POST){
                                    case "default":
                                        $stmt = $connection->patientsConsult($_POST["status"], $_POST["programa"], $_POST["sexo"], $_POST["therapist"], $_POST["attentionDay"], $_POST["attentionHour"]);
                                        $fillTable=true;
                                        break;
                                    case "pop":
                                        if($_POST["searchType"]=="byName"){
                                            $stmt=$connection->patientConsultByName($_POST["nombre"], $_POST["apellido"]);
                                        }else{
                                            $stmt=$connection->patientConsultByRUT($_POST["rut"]);
                                        }
                                        $fillTable=true;
                                        break;
                                    default:
                                        $patient = $connection->patientsConsultByID($_POST["patientID"]);
                                }
                                if(fillTable){
                                    while($row=$stmt->fetch(PDO::FETCH_OBJ)){
                                        echo "<td>".$row->patientSurname.", ".$row->$patientName."</td>";
                                        echo "<td>".$row->patientRUT."</td>";
                                        echo "<td>".$row->patientAge."</td>";
                                        echo "<td>".$row->patientSex."</td>";
                                        echo "<td>".$row->attentionProgram."</td>";
                                        echo "<td>".$row->programStatus."</td>";
                                        echo "<td>".$row->therapist."</td>";
                                        echo "<td><form action='/CentroArbol/pacientes.php' method='POST' class='patientDetForm'>
                                                    <input type='hidden' value=".$row->patientID." name='patientID'>
                                                    <input type='submit' value='Detalles' name='details'>
                                            </form></td>";
                                    }
                                }
                            }else{
                                $stmt = $connection->patientsConsult();
                                while($row=$stmt->fetch(PDO::FETCH_OBJ)){
                                    echo "<td>".$row->patientSurname.", ".$row->$patientName."</td>";
                                    echo "<td>".$row->patientRUT."</td>";
                                    echo "<td>".$row->patientAge."</td>";
                                    echo "<td>".$row->patientSex."</td>";
                                    echo "<td>".$row->attentionProgram."</td>";
                                    echo "<td>".$row->programStatus."</td>";
                                    echo "<td>".$row->therapist."</td>";
                                    echo "<td><form action='/CentroArbol/pacientes.php'method='POST' class='patientDetForm'>
                                                <input type='hidden' value=".$row->patientID." name='patientID'>
                                                <input type='submit' value='Detalles' name='details'>
                                        </form></td>";
                                }
                            }
                        ?>
                    </tbody>
                        
                        
                    
                </table>
                <!--código php para cargar tabla con datos-->
            </div>
            <div id="popup-dialog">
                <form action="" method="POST" name="customSearch" id="customSearch">
                    <div class="popForm">
                        <div class="formItem">
                            <label for="searchType">Buscar por:</label>
                            <select name="searchType" id="searchType">
                                <option value="byName">Nombre</option>
                                <option value="byRUT">RUT</option>
                            </select>
                        </div>
                        <div class="formItem search1">
                            <label for="nombre">Nombre:</label>
                            <input type="text" placeholder="Nombre" name="nombre" id="nombre">
                        </div>
                        <div class="formItem search1">
                            <label for="apellido">Apellido:</label>
                            <input type="text" placeholder="Apellido" name="apellido" id="apellido">
                        </div>
                        <div class="formItem search2">
                            <label for="rut">RUT:</label>
                            <input type="text" placeholder="RUT" name="rut" id="rut">
                        </div>
                        <div class="formBtns">
                            <button class="btnx btnxSend" type="submit" name="pop" value="buscar">Buscar</button>
                            <button class="btnx btnxCancel">Cancelar</button>
                        </div>
                    </div>    
                </form>   
            </div>
            <div id="patientDetails">
                <?php
                ?>
                <div class="patientDet">
                    <div id="patientName">
                        <h2><?php $patient["patientName"]." ".$patient["patientSurname"] ?></h2>
                    </div>
                    <div id="patientData">
                        <div id="personalData" class="patientDat">
                            RUT: <?php echo $patient["patientRUT"]?><br>
                            Teléfono: <?php echo $patient["patientPhone"]?><br>
                            Email: <?php echo $patient["patientEmail"]?><br>
                            Sexo: <?php echo $patient["patientSex"]?>
                        </div>
                        <div id="patientAddress" class="patientDat">
                            Región: <?php echo $patient["patientRegion"]?><br>
                            Ciudad: <?php echo $patient["patientCity"]?><br>
                            Comuna: <?php echo $patient["patientComune"]?><br>
                            Dirección: <?php echo $patient["patientAddress"]?><br>
                        </div>
                    </div>
                    <div id="process">
                        <div id="processLeftPanel" class="patientDat">
                            Terapéuta: <?php echo $patient["therapist"]?><br>
                            Horario: <?php echo $patient["attentionBlock"]?><br>
                            Programa: <?php echo $patient["attentionProgram"]?><br>
                            Status: <?php echo $patient["programStatus"]?><br>
                            Citas: <?php echo $patient["totalDates"]?><br>
                            Ausencias: <?php echo $patient["absences"]?><br>
                            Inicio de proceso: <?php echo $patient["processStartDate"]?><br>
                            Fin de proceso: <?php echo $patient["processEndDate"]?><br>
                        </div>
                        <div id="processRightPanel" class="patientDat">
                            <div id="files">
                                <button>Boton1</button>
                                <button>Boton2</button>
                            </div>
                            <div id="closeButton">
                                <button id="close">Cerrar</button>
                            </div>
                        </div>
                    </div>
                </div>
                
                    
                
                <!--Organizar datos del paciente-->
                <?php/* }*/?>
            </div>
        </main>
        
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script>
            function validateRUT(rut){
                let symbolCount=-1;
                let result=true;
                for(i=rut.length-1;i>=0;i--){
                    if(i==rut.length-1){
                        if(isNumber(rut[i]) or (rut[i]=="k" or rut[i]=="K")){
                            continue;
                        }else{
                            result=false;
                            break;
                        }
                    }
                    if(i==rut.length-2){
                        if(rut[i]=="-" or isNumber(rut[i])){
                            if(rut[i]=="-"){
                                symbolCount=3;
                            }else{
                                symbolCount=2;
                            }
                            continue;
                        }else{
                            result=false;
                            break;
                        }
                    }
                    if(symbolCount>0){
                        if(isNumber(rut[i])){
                            symbolCount--;
                            continue;
                        }else{
                            result=false;
                            break;
                        }
                    }else{
                        if(rut[i]=="."){
                            symbolCount=3;
                            continue;
                        }else if(isNumber(rut[i])){
                            symbolCount=2;
                            continue;
                        }else{
                            result=false;
                            break;
                        }
                    }
                    
                }
                return result;
            }
            function validateName(name){
                let pattern=/\s+/;
                name=deleteFirstSpaces(name);
                let nameArr=name.split(pattern);
                let result=true;
                for(let i=0;i<nameArr.length;i++){
                    if(onlyLetters(nameArr[i])){
                        continue;
                    }else{
                        result=false;
                        break;
                    }
                }
                return result;
            }
            
            function deleteFirstSpaces(value){
                let pattern=/\s+/;
                return name.replace(pattern,"");
            }
            
            function onlyLetters(value){
                let pattern=/^[a-zA-ZÀ-ÿñÑ']+$/g;
                return pattern.test(value);
            }
            
            
            function isNumber(character){
                let pattern = /\d/;
                return pattern.test(character);
            }
            
            function allowedSymbol(character){
                return character=="." or character=="-";
            }
            
            $(document).ready(function(){
                $("#btnAS").click(function(){
                    //alert("YES");
                    $("#popup-dialog").show();
                });
                $("#searchType").change(()=>{
                    let st = $("#searchType").val();
                    if(st=="byRUT"){
                        $(".search2").css({
                            "display":"flex",
                            "width":"100%",
                            "flex-direction": "column",
                            "justify-content": "space-around"
                        });
                        $(".search1").css("display","none");
                    }else{
                        $(".search1").css({
                            "display":"flex",
                            "width":"100%",
                            "flex-direction": "column",
                            "justify-content": "space-around"
                        });
                        $(".search2").css("display","none");
                    }
                });
                $(".btnxCancel").click((event)=>{
                    event.preventDefault();
                    $("#popup-dialog").css("display","none");
                });
                $("#customSearch").submit((event)=>{
                    event.preventDefault();
                    let valid=false;
                    if($("#searchType").val()=="byRUT"){
                        let rut=deleteFirstSpaces($("#rut").val());
                        $("#rut").val(rut);
                        if(validateRUT(rut){
                            valid=true;
                        }else{
                            alert("Formato de RUT inválido");
                        }
                    }else{
                        let name=deleteFirstSpaces($("#nombre").val());
                        let surname=deleteFirstSpaces($("#apellido").val());
                        $("#nombre").val(name);
                        $("#surname").val(surname);
                        if(name!="" and surname!=""){
                            if(validateName(nombre) and validateName(surname)){
                                valid=true;
                            }else{
                                alert("Nombre y/o Apellido inválido");
                            }
                        }else if(name!="" and surname==""){
                            if(validateName(name)){
                                valid=true;
                            }else{
                                alert("Nombre inválido");
                            }
                        }else if(name=="" and surname!=""){
                            if(validateName(surname)){
                                valid=true;
                            }else{
                                alert("Apellido inválido");
                            }
                        }else{
                            alert("Debe ingresar al menos un Nombre o Apellido");
                        }
                    }
                    if(valid){
                        this.submit();
                    }
                });
                
                ///////////////////////////////////////
                $(".patientDetForm").submit((event)=>{
                    $("#patientDetails").show();
                });
                $("#close").click((event)=>{
                    $("#patientDetails").css("display","none");
                });
            });
        </script>
        
    </body>
</html>
<!--Pendientes:
-Contenedor con los datos del paciente (OK)
-Verificar que tablas debo consultar (OK)
-Código para mostrar datos del paciente una vez hecho Clic en Detalles (OK)
-Código JS para mostrar Contenedor con los detalles del paciente y enviar datos de busqueda (OK)
-Validar Nombre, Apellido y RUT en busqueda avanzada(OK)
-Mostrar un alert si no se encuentra nada en la busqueda anterior
-LLenar BBDD para probar datos
-->
