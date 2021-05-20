/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
var data;
var patients;
function decreaseDate(){
    var changeMonth=false;
    var dt=document.querySelector('#date').innerHTML.split('/');
    var id='#'+getMonthID(dt[1].toString())+dt[0].toString();
    //alert(id);
    document.querySelector(id).classList.add("notSelected");
    document.querySelector(id).classList.remove("selected");
    
    if(dt[0]>1){
        dt[0]--;
    }else{
        if(dt[1].toString()=='1'){
            dt[1]=12;
            dt[2]--;
        }else{
            dt[1]--;
        }
        dt[0]=daysMonth(dt[1].toString(),dt[2]);
        loadCalendar(dt[1],dt[2],dt[0]);
        changeMonth=true;
    }
    
    if(!changeMonth){
        id='#'+getMonthID(dt[1].toString())+dt[0].toString();
        document.querySelector(id).classList.add("selected");
        document.querySelector(id).classList.remove("notSelected");
    }else{
        changeMonth=false;
    }
    let queryDate=dt[2]+'-'+dt[1]+'-'+dt[0];
    document.querySelector('#date').innerHTML=dt.join('/');
    loadAgenda(document.querySelector('#location').value,document.querySelector('#organize').value,queryDate);
}

function increaseDate(){
    var changeMonth=false;
    var dt=document.querySelector('#date').innerHTML.split('/');
    var id='#'+getMonthID(dt[1].toString())+dt[0].toString();
    document.querySelector(id).classList.add("notSelected");
    document.querySelector(id).classList.remove("selected");
    if(dt[0]<daysMonth(dt[1].toString(),dt[2])){
        dt[0]++;
    }else{
        dt[0]=1;
        if(dt[1].toString()=='12'){
            dt[1]=1;
            dt[2]++;
        }else{
            dt[1]++;
        }
        loadCalendar(dt[1],dt[2],dt[0]);
        changeMonth=true;
    }
    if(!changeMonth){
        id='#'+getMonthID(dt[1].toString())+dt[0].toString();
        document.querySelector(id).classList.remove("notSelected");
        document.querySelector(id).classList.add("selected");
    }else{
        changeMonth=false;
    }
    let queryDate=dt[2]+'-'+dt[1]+'-'+dt[0];
    document.querySelector('#date').innerHTML=dt.join('/');
    loadAgenda(document.querySelector('#location').value,document.querySelector('#organize').value,queryDate);
}

function getMonthID(month){
    switch(month){
        case '1':
            return 'jan';
        case '2':
            return 'feb';
        case '3':
            return 'mar';
        case '4':
            return 'apr';
        case '5':
            return 'may';
        case '6':
            return 'jun';
        case '7':
            return 'jul';
        case '8':
            return 'aug';
        case '9':
            return 'sep';
        case '10':
            return 'oct';
        case '11':
            return 'nov';
        case '12':
            return 'dec';
    }
}

function daysMonth(month,year){
    var days=31;
    switch(month){
        case '2':
            if(year%4==0){
                if(year%100!=0){
                    days=29;
                }else{
                    if(year%400==0){
                        days=29;
                    }else{
                        days=28; 
                    }
                }
            }else{
                days=28;
            }
            break;
        case '4':
        case '6':
        case '9':
        case '11':
            days=30;
            break;
    }
    return days;
}

function buildCalendar(month, year, selectedDay){
    var html="<div>L</div><div>M</div><div>M</div><div>J</div><div>V</div><div>S</div><div>D</div>";
    var firstDayMonth=dayOfWeek(1,month,year);
    var numDiasMes=daysMonth(month.toString(),year);
    var cont=1;
    var i=0;
    var id0=getMonthID(month.toString());
                            
    while(i<firstDayMonth){
        html+= "<div class='prevoiusMonth'>-</div>";
        i++;
    }
    while(cont<=numDiasMes){
        var id=id0+cont.toString();
        if(cont==selectedDay){
            html+="<div id="+id+" class='selected' onclick='selectMe(this.id)'>"+cont+"</div>";
        }else{
            html+="<div id="+id+" class='notSelected' onclick='selectMe(this.id)'>"+cont+"</div>";
        }                                
        cont++;
    }
    var j=0;
    while(cont<42-i+1){
        j++;
        html+="<div class='nextMonth'>"+j+"</div>";
        cont++;
    }
    return html;
}

function monthYear(month,year){
    switch(month){
        case 1:
            return "Enero "+year.toString();
        case 2:
            return "Febrero "+year.toString();
        case 3:
            return "Marzo "+year.toString();
        case 4:
            return "Abril "+year.toString();
        case 5:
            return "Mayo "+year.toString();
        case 6:
            return "Junio "+year.toString();
        case 7:
            return "Julio "+year.toString();
        case 8:
            return "Agosto "+year.toString();
        case 9:
            return "Septiembre "+year.toString();
        case 10:
            return "Octubre "+year.toString();
        case 11:
            return "Noviembre "+year.toString();
        case 12:
            return "Diciembre "+year.toString();
    }
}

function loadCalendar(month,year,selectedDay){
    var calendarDays=buildCalendar(month,year,selectedDay);
    var my = monthYear(month,year);
    document.querySelector('#calendario').innerHTML=calendarDays;
    document.querySelector('#year-month').innerHTML=my;
}

function dayOfWeek(day,month,year){
    var mon=month-2;
    if(mon==0){
        mon=12;
        year--;
        year=year.toString();
    }else{
        if(mon==-1){
            mon=11;
            year--;
            year=year.toString();
        }
    }
    //alert("dia = "+day+"\n"+"mes = "+mon+"\n"+"año = "+year);
    var yr=year.toString();
    var c=parseInt(yr.substring(0,2));
    var d=parseInt(yr.substring(2));
    var zeller=day+Math.floor((13*mon-1)/5)+d+Math.floor(d/4)+Math.floor(c/4)-2*c;
    //alert("zeller = "+zeller);
    var res=(zeller>0)?zeller%7:(((-1)*zeller%7)*(-1)+7);
    res--;
    return (res<0)?6:res;
}

function decreaseMonth(){
    var dt=document.querySelector("#date").innerHTML.split('/');
    dt[1]--;
    if(dt[1]<1){
        dt[1]=12;
        dt[2]--;
    }
    if(parseInt(dt[0])>daysMonth(dt[1].toString(),dt[2])){
        dt[0]=daysMonth(dt[1].toString(),dt[2]);
    }
    loadCalendar(parseInt(dt[1]),parseInt(dt[2]),parseInt(dt[0]));
    let queryDate=dt[2]+'-'+dt[1]+'-'+dt[0];
    document.querySelector("#date").innerHTML=dt.join('/');
    loadAgenda(document.querySelector('#location').value,document.querySelector('#organize').value,queryDate);
}

function increaseMonth(){
    var dt=document.querySelector("#date").innerHTML.split('/');
    dt[1]++;
    if(dt[1]>12){
        dt[1]=1;
        dt[2]++;
    }
    if(parseInt(dt[0])>daysMonth(dt[1].toString(),dt[2])){
        dt[0]=daysMonth(dt[1].toString(),dt[2]);
    }
    loadCalendar(parseInt(dt[1]),parseInt(dt[2]),parseInt(dt[0]));
    let queryDate=dt[2]+'-'+dt[1]+'-'+dt[0];
    document.querySelector("#date").innerHTML=dt.join('/');
    loadAgenda(document.querySelector('#location').value,document.querySelector('#organize').value,queryDate);
}

function selectMe(id){
    var day = parseInt(id.substr(3));
    var unselected=document.querySelector(".selected");
    unselected.classList.add("notSelected");
    unselected.classList.remove("selected");
    var selected = document.querySelector("#"+id);
    selected.classList.add("selected");
    selected.classList.remove("notSelected");
    var dt = document.querySelector("#date").innerHTML.split('/');
    dt[0]=day;
    let queryDate=dt[2]+'-'+dt[1]+'-'+dt[0];
    document.querySelector("#date").innerHTML=dt.join('/');
    loadAgenda(document.querySelector('#location').value,document.querySelector('#organize').value,queryDate);
}
function loadAgenda(branch,orderBy,dt){
    var xmlHttp = new XMLHttpRequest();
    xmlHttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
        data = JSON.parse(this.responseText);
        loadView(document.querySelector('#organize').value,data);
    }
  };
    let url='dbInteractions/llenarAgenda.php?branch='+branch+'&orderBy='+orderBy+'&dt='+dt;
    //alert(url);
    xmlHttp.open('GET',url,true);
    xmlHttp.send();
    
}
function fillAgenda(agenda){
    for(var x in agenda['therapists']){
        //alert('x='+x);
        //alert(agenda['therapists'].length);
        var thID = agenda['therapists'][x]['id'];
        //alert(thID);
        var thInit = agenda['therapists'][x]['initials'];
        //alert(thInit);
        var thName = agenda['therapists'][x]['name'];
        //alert(thName);
        for(var m in agenda['therapists'][x]['attentions']){
            //alert('m='+m);
            var box = agenda['therapists'][x]['attentions'][m]['box'];
            //alert('box='+box);
            var blockS = agenda['therapists'][x]['attentions'][m]['blockS'];
            //alert('blockS='+blockS);
            var blockE = agenda['therapists'][x]['attentions'][m]['blockE'];
            //alert('blockE='+blockE);
            for(let k=parseInt(blockS);k<=parseInt(blockE);k++){
                //alert('k='+k);
                var divIDt = '#b'+String(k)+'bx'+String(box)+'t';
                //alert(divIDt);
                const thDiv = document.querySelector(divIDt);
                thDiv.innerHTML=thInit;
                const attribute1=document.createAttribute('nameID');
                attribute1.value=thID;
                thDiv.setAttributeNode(attribute1);
                const attribute2=document.createAttribute('thName');
                attribute2.value=thName;
                thDiv.setAttributeNode(attribute2);
            }
        }
    }
    patients= new Object();
    for(var y in agenda['patients']){
        patients[agenda['patients'][y]['id']]={
            'initials':agenda['patients'][y]['initials'],
            'name':agenda['patients'][y]['name'], 
            'rut':agenda['patients'][y]['rut'], 
            'email':agenda['patients'][y]['email'],
            'phone':agenda['patients'][y]['phone'], 
            'sex':agenda['patients'][y]['sex'], 
            'program':agenda['patients'][y]['program'], 
            'box':agenda['patients'][y]['box'], 
            'absences':agenda['patients'][y]['absences'], 
            'totalDates':agenda['patients'][y]['totalDates'],
            'justifiedAbsences':agenda['patients'][y]['justifiedAbsences'],
            'programTotalAttentions':agenda['patients'][y]['programTotalAttentions'],
            'therapist':agenda['patients'][y]['therapist'], 
            'block':agenda['patients'][y]['block'],
            'attentionStatus':agenda['patients'][y]['nextDateStatus'],
            'receipt':""
        };
        var divIDp = '#b'+String(agenda['patients'][y]['block'])+'bx'+String(agenda['patients'][y]['box'])+'p';
        const pDiv = document.querySelector(divIDp);
        pDiv.classList.add(agenda['patients'][y]['nextDateStatus']);
        pDiv.innerHTML=agenda['patients'][y]['initials'];
        const attribute=document.createAttribute('pID');
        attribute.value=agenda['patients'][y]['id'];
        pDiv.setAttributeNode(attribute);
        const attribute2=document.createAttribute('data-tooltip');
        pDiv.setAttributeNode(attribute2);
        
        const patientDetails='Nombre:'+agenda['patients'][y]['name'].bold()+"\n\
RUT: "+agenda['patients'][y]['rut'].bold()+"\n\
Correo: "+agenda['patients'][y]['email'].bold()+"\n\
Teléfono: "+agenda['patients'][y]['phone'].bold()+"\n\
Programa: "+agenda['patients'][y]['program'].bold()+"\n\
Citas: "+agenda['patients'][y]['totalDates'].bold()+"\n\
Ausencias: "+agenda['patients'][y]['absences'].bold()+"\n\
Ausencias justificadas: "+agenda['patients'][y]['justifiedAbsences'].bold()+"\n\
Estado: "+agenda['patients'][y]['nextDateStatus'].bold();
        
        
        const attribute3=document.createAttribute('title');
        attribute3.value=patientDetails;
        pDiv.setAttributeNode(attribute3);
        
        const attribute4=document.createAttribute('onclick');
        attribute4.value="showReserveDetails("+agenda['patients'][y]['id']+")";
        pDiv.setAttributeNode(attribute4);
    }
}

function fillAgenda2(agenda){
    for(var x in agenda['therapists']){
        var thID = agenda['therapists'][x]['id'];
        var thName = agenda['therapists'][x]['name'];
        for(var m in agenda['therapists'][x]['attentions']){
            var blockS = agenda['therapists'][x]['attentions'][m]['blockS']
            var blockE = agenda['therapists'][x]['attentions'][m]['blockE'];
            var box = agenda['therapists'][x]['attentions'][m]['box'];
            for(let k=parseInt(blockS);k<=parseInt(blockE);k++){
                var divID = '#b'+String(k)+'bx'+String(box);
                const div = document.querySelector(divID);
                div.classList.add('available');
                const attribute1=document.createAttribute('thID');
                attribute1.value=thID;
                div.setAttributeNode(attribute1);
                const attribute2=document.createAttribute('thName');
                attribute2.value=thName;
                div.setAttributeNode(attribute2);
            }
        }
    }
    patients= new Object();
    for(var y in agenda['patients']){
        patients[agenda['patients'][y]['id']]={
            'initials':agenda['patients'][y]['initials'],
            'name':agenda['patients'][y]['name'], 
            'rut':agenda['patients'][y]['rut'], 
            'email':agenda['patients'][y]['email'],
            'phone':agenda['patients'][y]['phone'], 
            'sex':agenda['patients'][y]['sex'], 
            'program':agenda['patients'][y]['program'], 
            'box':agenda['patients'][y]['box'], 
            'absences':agenda['patients'][y]['absences'], 
            'totalDates':agenda['patients'][y]['totalDates'],
            'justifiedAbsences':agenda['patients'][y]['justifiedAbsences'],
            'programTotalAttentions':agenda['patients'][y]['programTotalAttentions'],
            'therapist':agenda['patients'][y]['therapist'], 
            'block':agenda['patients'][y]['block'],
            'attentionStatus':agenda['patients'][y]['nextDateStatus'],
            'receipt':""
        };
        var divIDp = '#b'+String(agenda['patients'][y]['block'])+'bx'+String(agenda['patients'][y]['box']);
        const pDiv = document.querySelector(divIDp);
        pDiv.addClassList(agenda['patients'][y]['nextDateStatus']);
        pDiv.innerHTML=agenda['patients'][y]['initials'];
        const attribute=document.createAttribute('pID');
        attribute.value=agenda['patients'][y]['id'];
        pDiv.setAttributeNode(attribute);
        const attribute2=document.createAttribute('data-tooltip');
        pDiv.setAttributeNode(attribute2);
        
        const patientDetails='Nombre:'+agenda['patients'][y]['name'].bold()+"\n\
RUT: "+agenda['patients'][y]['rut'].bold()+"\n\
Correo: "+agenda['patients'][y]['email'].bold()+"\n\
Teléfono: "+agenda['patients'][y]['phone'].bold()+"\n\
Programa: "+agenda['patients'][y]['program'].bold()+"\n\
Citas: "+agenda['patients'][y]['totalDates'].bold()+"\n\
Ausencias: "+agenda['patients'][y]['absences'].bold()+"\n\
Ausencias justificadas: "+agenda['patients'][y]['justifiedAbsences'].bold()+"\n\
Estado: "+agenda['patients'][y]['nextDateStatus'].bold();
        
        
        const attribute3=document.createAttribute('title');
        attribute3.value=patientDetails;
        pDiv.setAttributeNode(attribute3);
        
        const attribute4=document.createAttribute('onclick');
        attribute4.value="showReserveDetails("+agenda['patients'][y]['id']+")";
        pDiv.setAttributeNode(attribute4);
    }
}

function loadView(orderBy,datos){
    var html="<table><thead><th>Hora</th>";
    if(orderBy==="box"){
        for(let i=1;i<=datos['numBoxes'];i++){
            html+="<th>Box "+String(i)+"</th>";
        }
        html+="</thead><tbody>";
        let count=8;
        let block=datos['attentionBlocks']['blockS'];
        while(count<22 && block<=datos['attentionBlocks']['blockE']){
            let hour=count<10?"0"+String(count)+":00":String(count)+":00";
            html+="<tr><td><div class='hour'>"+hour+"</div></td>";
            for(let i=1;i<=datos['numBoxes'];i++){
                html+="<td><div class='block'><div class='therapist' id='b"+String(block)+"bx"+String(i)+"t'>AV</div><div class='patient' id='b"+String(block)+"bx"+String(i)+"p'>AV</div></div></td>";
            }
            html+="</tr>";
            count++;
            block++;
        }
        html+="</tbody></table>";
        document.querySelector("#sch-body").innerHTML=html;
        fillAgenda(datos);
    }else{
        //alert('Else');
        //console.log(datos);
        for(let x in datos['therapists']){
            html+="<th>"+datos['therapists'][x]['name']+"</th>";
        }
        html+="</thead><tbody>";
        let count=8;
        let block=datos['attentionBlocks']['blockS'];
        while(count<22 && block<=datos['attentionBlocks']['blockE']){
            let hour=count<10?"0"+String(count)+":00":String(count)+":00";
            html+="<tr><td><div class='hour'>"+hour+"</div></td>";
            for(let y in datos['therapists']){
                //find box
                let box;
                for(let z in datos['therapists'][y]['attentions']){
                    if(block<=datos['therapists'][y]['attentions'][z]['blockE'] && block>=datos['therapists'][y]['attentions'][z]['blockS']){
                        box=datos['therapists'][y]['attentions'][z]['box'];
                        break;
                    }
                }
                //
                if(typeof box === 'undefined'){
                    html+="<td><div class='block'><div class='patient2'>AV</div></div></td>";
                }else{
                    html+="<td><div class='block'><div class='patient2' id='b"+String(block)+"bx"+String(box)+"'>AV</div></div></td>";
                }
                
            }
            html+="</tr>";
            count++;
            block++;
        }
        html+="</tbody></table>";
        document.querySelector("#sch-body").innerHTML=html;
        fillAgenda2(datos);
    }
}

function showReserveDetails(id){
    document.querySelector('#bookingDetails').style.display='block';
    document.querySelector('#pName').innerHTML="<b>"+patients[id]['name']+"</b>";
    document.querySelector('#pRut').innerHTML="<b>"+patients[id]['rut']+"</b>";
    document.querySelector('#pEmail').innerHTML="<b>"+patients[id]['email']+"</b>";
    document.querySelector('#pPhone').innerHTML="<b>"+patients[id]['phone']+"</b>";
    document.querySelector('#pSex').innerHTML="<b>"+patients[id]['sex']+"</b>";
    document.querySelector('#pProgram').innerHTML="<b>"+patients[id]['program']+"</b>";
    document.querySelector('#pDates').innerHTML="<b>"+patients[id]['totalDates']+"</b>";
    document.querySelector('#pAbsences').innerHTML="<b>"+patients[id]['absences']+"</b>";
    document.querySelector('#pJAbsences').innerHTML="<b>"+patients[id]['justifiedAbsences']+"</b>";
    document.querySelector('#pProgramDates').innerHTML="<b>"+patients[id]['programTotalDates']+"</b>";
    document.querySelector('#pTherapist').innerHTML="<b>"+patients[id]['therapist']+"</b>";
    document.querySelector('#pBox').innerHTML="<b>"+patients[id]['box']+"</b>";
    document.querySelector('#pBlock').innerHTML="<b>"+patients[id]['block']+"</b>";
    document.querySelector('#pAttentionStatus').selectedIndex=attentionStatus(patients[id]['attentionStatus']);
    document.querySelector('#receipt').innerHTML="<b>"+patients[id]['receipt']+"</b>";
}

function attentionStatus(as){
    switch(as){
        case "booked":
            return 0;
        case "confirmed":
            return 1;
        case "paid":
            return 2;
        case "attended":
            return 3;
        case "canceled":
            return 4;
    }
}

function changeAttentionStatus(){
    if(document.querySelector('#pAttentionStatus').value=="paid" || document.querySelector('#pAttentionStatus').value=="attended" ){
        document.querySelector('#receipt').readOnly=false;
    }else{
        document.querySelector('#receipt').readOnly=true;
    }
}

function closePanel(){
    document.querySelector('#bookingDetails').style.display='none';
}

function updateData(){
    document.querySelector('#bookingDetails').style.display='none';
}



function changeView(value){
    loadView(value,data);
}

function changeLocation(location){
    var dt = document.querySelector("#date").innerHTML.split('/');
    let queryDate=dt[2]+'-'+dt[1]+'-'+dt[0];
    loadAgenda(location,document.querySelector('#organize').value,queryDate);
}

function loadDataOnLoad(){
    var dt = document.querySelector("#date").innerHTML.split('/');
    let queryDate=dt[2]+'-'+dt[1]+'-'+dt[0];
    loadAgenda(document.querySelector('#location').value,document.querySelector('#organize').value,queryDate);
}
window.onload=loadDataOnLoad;