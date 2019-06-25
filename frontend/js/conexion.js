var URL = "http://localhost:8080/Comanda/api";
var httpReq = new XMLHttpRequest();
var listaMenu = new Array();

    var callback = function () {
        loading();
        if (httpReq.readyState == 4){
            console.log(httpReq.status);
            if (httpReq.status == 200){ 
                var tipo = JSON.parse(httpReq.responseText);

                if(tipo.type != "error")
                {
                    unloading(); 
                    var str= {"type" :tipo.type , "email" :document.getElementById('email').value };
                    localStorage.setItem("user", JSON.stringify(str));
                    alert(window.location);
                    location.href ="./index.html";
            
                }

                if(tipo.type == "error")
                {
                   unloading();
                   document.getElementById('err').style.display = 'block';
                   document.getElementById('email').style.border = 'red solid 2px';
                   document.getElementById('pass').style.border = 'red solid 2px';
                   document.getElementById('email').style.backgroundColor = '#FBB6A6';
                   document.getElementById('pass').style.backgroundColor = '#FBB6A6';
                   

                }
            }
            else
            {
                alert("OCURRIO UN ERROR EN LA PETICION");
                unloading();
            }
        }
    }

    var callback2 = function () {
        //loading();
        if (httpReq.readyState == 4){
            if (httpReq.status == 200){
                //unloading(); 
                localStorage.setItem("menu", httpReq.responseText);
                listaMenu = JSON.parse(localStorage.getItem('menu'));
                crearTabla(listaMenu);
                console.log('enviando menu');
            }
            else
            {
                alert("OCURRIO UN ERROR EN LA PETICION");
                //unloading();
            }
        }
    }
    var callback3 = function () {            
        loading();
        if (httpReq.readyState == 4){
            if (httpReq.status == 200){ 
                unloading();
                location.href = "";
                alert("REGISTRO ACTUALIZADO!");
                
            }
            else
            {
                unloading();
                alert("OCURRIO UN ERROR EN LA PETICION");
            }  
        } 
    }
    function Login(usuario,pass)
    {
        httpReq.onreadystatechange = callback; 
        httpReq.open("post", URL + '/login', true);
        httpReq.setRequestHeader("Content-Type", "application/json");
        var obj = {"usuario":'admin' ,"password":'1234'};
        httpReq.send(JSON.stringify(obj));

    }

    function cargar_menu()
    {
        httpReq.onreadystatechange = callback2;
        httpReq.open("GET",'http://localhost/Comanda/api/menu', true);
        httpReq.send();
    }

    function crearTabla(menu)
    {

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