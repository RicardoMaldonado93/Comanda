//#region DECLARACION DE VARIABLES
var URL = "http://localhost/Comanda/api";
var httpReq = new XMLHttpRequest();
var listaMenu = new Array();
var listaEmpleados = new Array();

//#endregion
//#region CALLBACKS
var callback = function () {
    //loading();
    if(httpReq.readyState == 4){
        if(httpReq.status == 200 ){      
            var token = httpReq.responseText;
            var decode = parseJwt(token);
            var tipo = decode['data'][0]['puesto'];
            localStorage.setItem('token',  token.replace('"','',token));
            
            switch(tipo){
                case 'Socio':{ location.href = '#/admin'; break;}
                case 'Bartender': { location.href = '#/bartender'; break;}
                case 'Mozo': {location.href = '#/mozo'; break;}
                case 'Cocinero': {location.href = '#/cocinero'; break;}
            }
            
        }
        
    else
    {
        var msg = JSON.parse(httpReq.responseText);
        alert(msg['msg']);
        //unloading();
    }
    }
    
}

var callback2 = function () {
    //loading();
    if (httpReq.readyState == 4){
        if (httpReq.status == 200){
            //unloading(); 
            if(localStorage.getItem('menu') != httpReq.responseText || localStorage.getItem('menu') == null){
                localStorage.setItem('menu',httpReq.responseText);
                listaMenu = JSON.parse(localStorage.getItem('menu'));
                crearTabla(listaMenu);
                console.log('enviando menu');
            }
            else{
                    for(var j=0; j<3; j++)
                        document.getElementById('tbody'+[j]).innerHTML= '';
                    crearTabla(JSON.parse(localStorage.getItem('menu')));
            }
        }
        else
        {
            alert("OCURRIO UN ERROR EN LA PETICION");
            //unloading();
        }
    }
}
var callback3 = function () {            
    //loading();
    if (httpReq.readyState == 4){
        if (httpReq.status == 200){ 
            //unloading();
            location.href = "";
            alert("REGISTRO ACTUALIZADO!");
            
        }
        else
        {
            //unloading();
            alert("OCURRIO UN ERROR EN LA PETICION");
        }  
    } 
}

var callbackEmpleados = function(){
    console.log(httpReq.readyState + '  ' + httpReq.status);
    if(httpReq.readyState == 4)
        if(httpReq.status ==200){
           
                localStorage.setItem('empleados', httpReq.responseText);
                var listaEmpleados = JSON.parse(localStorage.getItem('empleados'));
                document.getElementById('tbEmpleado').innerHTML='';
                crearTablaEmpleados(listaEmpleados);
                console.log('cargando lista de empleados');
            }
        else
            console.log('es la misma lista de empleados');
        
}

//#endregion  
//#region FUNCIONES DE CARGA

function login()
{
    
    var usr = document.getElementById("usuario").value;
    var pass = document.getElementById("password").value;
    var json = {"usuario":usr, 'pass':pass};

    httpReq.onreadystatechange = callback;
    httpReq.open("POST",URL+'/login',true);
    httpReq.setRequestHeader("Content-Type", "application/json");
    httpReq.send(JSON.stringify(json));
}

function cargar_menu()
{
    httpReq.onreadystatechange = callback2;
    httpReq.open("GET",'http://localhost/Comanda/api/menu', true);
    httpReq.send();
}

function cargarRegistro(ind, event)
{
    var leg = document.getElementById('legajo');
    var nombre = document.getElementById('nombre');
    var materia = document.getElementById('materia');
    var nota = document.getElementById('nota');
    document.getElementById('modificar').style.display = 'block';

    leg.value = lista[ind].legajo;
    nombre.value = lista[ind].nombre;
    materia.value = lista[ind].materia;
    nota.value = lista[ind].nota;

    document.getElementById("guardar").onclick = function() 
    {
        var jsonNuevo = {"id": lista[ind].id, "legajo": leg.value, "nombre": nombre.value, "materia": materia.value, "nota": nota.value};
        httpReq.onreadystatechange = callback3; 
        httpReq.open("post", "http://localhost:3000/editarNota", true);
        httpReq.setRequestHeader("Content-Type", "application/json");
        httpReq.send(JSON.stringify(jsonNuevo));
    }
}

function agregarRegistro(event)
{
    document.getElementById('modificar').style.display = 'block';
    var leg = document.getElementById('legajo');
    var nombre = document.getElementById('nombre');
    var materia = document.getElementById('materia');
    var nota = document.getElementById('nota');
    
/*
    leg.value = lista[ind].legajo;
    nombre.value = lista[ind].nombre;
    materia.value = lista[ind].materia;
    nota.value = lista[ind].nota;
*/
    document.getElementById("guardar").onclick = function() 
    {
        var jsonNuevo = {"id": 9999, "legajo": leg.value, "nombre": nombre.value, "materia": materia.value, "nota": nota.value};
        httpReq.onreadystatechange = callback3; 
        httpReq.open("post", "http://localhost:3000/agregarAlumno", true);
        httpReq.setRequestHeader("Content-Type", "application/json");
        httpReq.send(JSON.stringify(jsonNuevo));
    }
}

function lista(){ 
    /* $.ajax({
       type: "GET",
       url: URL + '/empleado/',
       headers: {"token": localStorage.getItem('token')},
       dataType: "text",
       cache: false,
       success: function(data){
           console.log('here');
           if(httpReq.status == 200) {
               console.log(data);
               listaEmpleados = JSON.parse(data);
               crearTablaEmpleados(listaEmpleados);
               console.log(listaEmpleados);
           } else {
               console.log(httpReq.responseText);
           }
       },
       error: function (XMLHttpRequest, textStatus, errorThrown) {
           alert(textStatus);
           alert(errorThrown);
       }
   });*/
     if(localStorage.getItem('empleados') != httpReq.statusText || localStorage.getItem('empleados') == null){
         httpReq.onreadystatechange = callbackEmpleados;        
         httpReq.open("get",URL+'/empleado',true);
         httpReq.setRequestHeader("Content-Type", "application/json");
         httpReq.setRequestHeader('token', localStorage.getItem('token'));
         
         httpReq.send();
     }
     else{
         crearTablaEmpleados(localStorage.getItem('empleados'));
     }
 };
//#endregion
//#region CARGA DE REGISTROS EN TABLAS 
    
    function crearTabla(menu)
    {
        if(localStorage.getItem('menu') != null){
        var n;
        var items = [  
                            'COMIDAS',
                            'POSTRES',
                            'BEBIDAS'
                        ];

        for( var j = 0; j < items.length; j++){
            
            for( var i = 0; i < items[j].length; i++){
                n = "<td>" +  menu[items[j]][i].nombre.toUpperCase() + "</td>" +
                "<td>" + '$ '+ menu[items[j]][i].precio + "</td>"; 
                document.getElementById('tbody'+[j]).innerHTML+= n;
               
            }
        }

        }
    }

    function crearTablaEmpleados(lista)
    {
        var n;
        //console.log('lista de la funcion ' + lista);
        for( var i = 0; i < lista.length; i++){
            n = "<td>" +  lista[i].ID+ "</td>" +
            "<td>"+ lista[i].usuario.toUpperCase()  + "</td>"
            +"<td>"+ lista[i].puesto.toUpperCase() + "</td>" 
            +"<td>"+ lista[i].estado.toUpperCase()  + "</td>" 
            +"<td>"+ lista[i].nombre.toUpperCase()  + "</td>" 
            +"<td>"+ lista[i].apellido.toUpperCase()  + "</td>" ;
            document.getElementById('tbEmpleado').innerHTML+= n;
               
            
            
        }
    }
//#endregion tablas
//#region FUNCIONES DE SPINNER
    
function loading()
{
    document.getElementById('spinner').style.display = 'block';
    document.getElementById('fondo').style.display = 'block';
}

function unloading()
{
    document.getElementById('spinner').style.display = 'none';
    document.getElementById('fondo').style.display = 'none'; 
}

//#endregion
//#region FUNCIONES EXTRAS
function parseJwt (token) {
    var base64Url = token.split('.')[1];
    var base64 = base64Url.replace(/-/g, '+').replace(/_/g, '/');
    var jsonPayload = decodeURIComponent(atob(base64).split('').map(function(c) {
        return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
    }).join(''));

    return JSON.parse(jsonPayload);
};
//#endregion