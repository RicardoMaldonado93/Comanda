
var URL='http://localhost/Comanda/api';
var httpRequest= new XMLHttpRequest();

function parseJwt (token) {
    var base64Url = token.split('.')[1];
    var base64 = base64Url.replace(/-/g, '+').replace(/_/g, '/');
    var jsonPayload = decodeURIComponent(atob(base64).split('').map(function(c) {
        return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
    }).join(''));

    return JSON.parse(jsonPayload);
};

var callback = function(){
    if(httpRequest.readyState == 4){
          if(httpRequest.status == 200 ){      
            var token = httpRequest.responseText;
            var decode = parseJwt(token);
            var tipo = decode['data'][0]['puesto'];
            
            if( tipo == 'Socio'){
                // location.href='http://localhost/Comanda/frontend/vistas/admin/';
                //window.location.assign("http://localhost/Comanda/frontend/vistas/admin/");
            location.href=('#/admin');
                
            }
            
        }
        else 
            console.log('error!');
    }
}

function login(){

    var usr = document.getElementById("usuario").value;
    var pass = document.getElementById("password").value;
    httpRequest.onreadystatechange = callback;
    httpRequest.open("POST",URL+'/login',true);
    httpRequest.setRequestHeader("Content-Type", "application/json");
    var json = {"usuario":usr, 'pass':pass};
    httpRequest.send(JSON.stringify(json));
}

function mostrarAlgo(){
    console.log('estoy mostrando algo');
}