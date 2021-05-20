<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <link type="text/css" rel="stylesheet" href="css/home.css">
        
        <title></title>
    </head>
    <body>
        <header>
            <div class="header-top">
                <img src="images/logo.png" alt="logo_centro_arbol">
                <h3><?php echo "Maryelin Palma"?></h3>
            </div>
            
            <nav class="myBar">
                <a class="active" href="#" alt="home">Home</a>
                <a href="/CentroArbol/models/agenda.php" alt="home">Agenda</a>
                <a href="#" alt="home">Terapéutas</a>
                <a href="/CentroArbol/models/pacientes.php" alt="home">Pacientes</a>
                <a href="#" alt="home">Administración</a>
            </nav>
        </header>
        <main>
            <div class="main-buttons">
                <button class="buttons"><a href="#" alt="Agenda"><div><img src="images/calendar.png"><h4>Agenda</h4></div></a></button>
                <button class="buttons"><a href="#" alt="Estadisticas"><div><img src="images/accounting.png"><h4>Estadisticas</h4></div></a></button>
            </div>
            <div class="main-buttons">
                <button class="buttons"><a href="#" alt="Terapeutas"><div><img src="images/terapeuta.png"><h4>Terapéutas</h4></div></a></button>
                <button class="buttons"><a href="/CentroArbol/pacientes.php" alt="Pacientes"><div><img src="images/paciente.png"><h4>Pacientes</h4></div></a></button>
            </div>
        </main>
    </body>
</html>
