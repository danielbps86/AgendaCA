<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <link type="text/css" rel="stylesheet" href="css/agenda.css">
        <title></title>
    </head>
    <body>
        <header>
            <div class="header-top">
                <img src="images/logo.png" alt="logo_centro_arbol">
                <h3><?php echo "Maryelin Palma"?></h3>
            </div>
            
            <nav class="myBar">
                <a href="/CentroArbol/home.php" alt="home">Home</a>
                <a class="active"  href="#" alt="Agenda">Agenda</a>
                <a href="#" alt=Terapéutas">Terapéutas</a>
                <a href="/CentroArbol/pacientes.php" alt="Pacientes">Pacientes</a>
                <a href="#" alt="Administración">Administración</a>
            </nav>
        </header>
        <main>
            <div class="filters">
                <div class="element">
                    <div><label for="organize">Organizar por:</label></div>
                    <div>
                        <select name="organize" id="organize" onChange="changeView(this.value)">
                            <option value="box" selected>Box</option>
                            <option value="therapist">Terapéuta</option>
                        </select>
                    </div>
                </div>    
                <div class="element">
                    <div><label for="location">Sede:</label></div>
                    <div>
                        <select name="location" id="location" onchange="changeLocation(this.value)">
                            <option value="Las Urbinas" selected>Las Urbinas</option>
                            <option value="Lota">Lota</option>
                            <option value="Sucursal Virtual">Sucursal Virtual</option>
                        </select>
                    </div>
                    
                </div>
                <div class="element" id="calendar" >
                    <!--Calendario-->
                    <div class="year-month">
                        <button class="inc-dec" onclick="decreaseMonth()"><</button>
                        <div id="year-month" class="ym-value"><?php require_once 'utilities.php'; echo Utilities::getMonthYearLabel()?></div>
                        <button class="inc-dec" onclick="increaseMonth()">></button>
                    </div>
                    <?php
                    echo "<div id='calendario' class='calendario'>";
                        echo "<div>L</div>";
                        echo "<div>M</div>";
                        echo "<div>M</div>";
                        echo "<div>J</div>";
                        echo "<div>V</div>";
                        echo "<div>S</div>";
                        echo "<div>D</div>";
                        $fecha= getdate();
                        $dSemana=$fecha["wday"];//dia de la semana
                        for($i=$fecha["mday"];$i>1;$i--){
                            $dSemana--;
                            if($dSemana<0){
                                $dSemana=6;
                            }
                        }
                        $dSemana--;
                        if($dSemana<0){
                            $dSemana=6;
                        }
                        $numDiasMes=31;
                        switch($fecha["mon"]){
                            case 2:
                                if($fecha["year"]%4==0){
                                    if($fecha["year"]%100!=0){
                                        $numDiasMes=29;
                                    }else{
                                        if($fecha["year"]%400==0){
                                            $numDiasMes=29;
                                        }else{
                                            $numDiasMes=28; 
                                        }
                                    }
                                }else{
                                    $numDiasMes=28;
                                }
                                break;
                            case 4:
                            case 6:
                            case 9:
                            case 11:
                                $numDiasMes=30;
                                break;
                        }
                        $cont=1;
                        $b=false;
                        $i=0;
                        $id0= strtolower(substr($fecha['month'],0,3));

                        while($i<$dSemana){
                            echo "<div class='prevoiusMonth'>-</div>";
                            $i++;
                        }
                        while($cont<=$numDiasMes){
                            $id=$id0.$cont;
                            if($cont==$fecha['mday']){
                                echo "<div id=$id class='selected' onclick='selectMe(this.id)'>$cont</div>";
                            }else{
                                echo "<div id=$id class='notSelected' onclick='selectMe(this.id)'>$cont</div>";
                            }                                
                            $cont++;
                        }
                        $j=0;
                        while($cont<42-$i+1){
                            $j++;
                            echo "<div class='nextMonth'>$j</div>";
                            $cont++;
                        }

                    echo "</div>";
                    ?>
                </div>
                <div class="element">
                    <button id="hourSeeker" onclick="searchHours()">Buscar Horas</button> 
                </div>
            </div>
            <div class="schedule">
                <!--Agendamiento-->
                <div id="sch-header">
                    <button id="decrease" onclick="decreaseDate()"><</button>
                    <div id="date"><?php echo getDate()['mday']."/".getDate()['mon']."/".getDate()['year']?></div>
                    <button id="increase" onclick="increaseDate()">></button>
                </div>
                <div id="sch-body">
                    
                </div>
            </div>
        </main>
        <?php
        // put your code here
        ?>
        <div id='bookingDetails'>
            <div class="bookingContainer">
                <div class="left-panel">
                    <div class="panel-element">
                        <p id="tagName">Nombre: </p><p id="pName"></p>
                    </div>
                    <div class="panel-element">
                        <p id="tagRut">RUT: </p><p id="pRut"></p>
                    </div>
                    <div class="panel-element">
                        <p id="tagEmail">Correo: </p><p id="pEmail"></p>
                    </div>
                    <div class="panel-element">
                        <p id="tagPhone">Teléfono: </p><p id="pPhone"></p>
                    </div>
                    <div class="panel-element">
                        <p id="tagSex">Sexo: </p><p id="pSex"></p>
                    </div>
                    <div class="panel-element">
                        <p id="tagProgram">Programa: </p><p id="pProgram"></p>
                    </div>
                    <div class="panel-element">
                        <p id="tagDates">Citas: </p><p id="pDates"></p>
                    </div>
                    <div class="panel-element">
                        <p id="tagAbsences">Ausencias: </p><p id="pAbsences"></p>
                    </div>
                    <div class="panel-element">
                        <p id="tagJAbsences">Ausencias justificadas: </p><p id="pJAbsences"></p>
                    </div>
                    <div class="panel-element">
                        <p id="tagProgramDates">Citas del programa: </p><p id="pProgramDates"></p>
                    </div>
                </div>
                <div class="right-panel">
                    <div class="right-top">
                        <div class="panel-element">
                            <p id="tagTherapist">Terapéuta: </p><p id="pTherapist"></p>
                        </div>
                        <div class="panel-element">
                            <p id="tagBox">Box: </p><p id="pBox"></p>
                        </div>
                        <div class="panel-element">
                            <p id="tagBlock">Hora: </p><p id="pBlock"></p>
                        </div>
                        <div class="panel-element">
                            <label for="attentionStatus">Estado de la atención:</label>
                            <select name="attentionStatus" id="pAttentionStatus" onchange="changeAttentionStatus()">
                                <option value="booked" selected>Agendada</option>
                                <option value="confirmed">Confirmada</option>
                                <option value="paid">Pagada</option>
                                <option value="attended">Asistió</option>
                                <option value="canceled">Cancelada</option>
                            </select>
                        </div>
                        <div class="panel-element">
                            <label for="receipt">Boleta N°:</label>
                            <input type="text" name="receipt" id="receipt" readonly>
                        </div>
                    </div>
                    <div class="right-bottom">
                        <button  class="btnPanel" onclick="updateData()">Aceptar</button>
                        <button class="btnPanel" onclick="closePanel()">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    </body>
    <script src='js/agenda.js'></script>
    <script src='js/Tooltip.js'></script>
</html>
